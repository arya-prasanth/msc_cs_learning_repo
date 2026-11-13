
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <conio.h>
#include <windows.h>

#define W 64
#define H 10
#define GROUND_ROW (H - 1)
#define DINO_X 6
#define DINO_H 2
#define QCAP 8
#define SCAP 5
#define TOPN 5

static const int JUMP_PROFILE[] = {1,2,3,4,4,4,4,3,2,1};
#define JUMP_LEN 10

enum { KEY_NONE, KEY_JUMP, KEY_PAUSE, KEY_RESTART, KEY_QUIT };

static int map_char(int c)
{
    switch(c)
    {
        case ' ': case 'w': case 'W': return KEY_JUMP;
        case 'p': case 'P': return KEY_PAUSE;
        case 'r': case 'R': return KEY_RESTART;
        case 'q': case 'Q': return KEY_QUIT;
    }
    return KEY_NONE;
}

static void sleep_ms(int ms)
{
    Sleep(ms);
}

static int poll_key(void)
{
    int result = KEY_NONE;

    while(_kbhit())
    {
        int c = _getch();
        int k = KEY_NONE;

        if(c == 0 || c == 224)
        {
            int c2 = _getch();
            if(c2 == 72) k = KEY_JUMP;
        }
        else
        {
            k = map_char(c);
        }

        if(k == KEY_QUIT)
            result = KEY_QUIT;
        else if(k != KEY_NONE && result != KEY_QUIT)
            result = k;
    }

    return result;
}

/* QUEUE */

typedef struct
{
    int data[QCAP];
    int head, tail, count;
} Queue;

static void q_init(Queue *q)
{
    q->head = q->tail = q->count = 0;
}

static int q_push(Queue *q, int v)
{
    if(q->count == QCAP) return 0;

    q->data[q->tail] = v;
    q->tail = (q->tail + 1) % QCAP;
    q->count++;

    return 1;
}

static int q_pop(Queue *q, int *v)
{
    if(q->count == 0) return 0;

    *v = q->data[q->head];
    q->head = (q->head + 1) % QCAP;
    q->count--;

    return 1;
}

/* STACK */

typedef struct
{
    int data[SCAP];
    int top;
} Stack;

static void s_init(Stack *s)
{
    s->top = 0;
}

static void s_push(Stack *s, int v)
{
    if(s->top == SCAP)
    {
        memmove(s->data, s->data + 1, (SCAP - 1) * sizeof(int));
        s->top--;
    }

    s->data[s->top++] = v;
}

static int s_pop(Stack *s, int *v)
{
    if(s->top == 0) return 0;

    *v = s->data[--s->top];
    return 1;
}

/* LINKED LIST */

enum { OB_CACTUS_S, OB_CACTUS_L, OB_BIRD_LOW, OB_BIRD_HIGH };

typedef struct Obstacle
{
    int type;
    int x, w, h, base;
    struct Obstacle *next;
} Obstacle;

static Obstacle *ob_head = NULL;
static Obstacle *ob_tail = NULL;

static void ob_add(int type)
{
    Obstacle *n = malloc(sizeof(Obstacle));

    if(!n) return;

    n->type = type;
    n->x = W;
    n->next = NULL;

    switch(type)
    {
        case OB_CACTUS_S:
            n->w = 1; n->h = 1; n->base = 0;
            break;

        case OB_CACTUS_L:
            n->w = 2; n->h = 2; n->base = 0;
            break;

        case OB_BIRD_LOW:
            n->w = 2; n->h = 1; n->base = 1;
            break;

        default:
            n->w = 2; n->h = 1; n->base = 3;
    }

    if(!ob_head)
        ob_head = ob_tail = n;
    else
    {
        ob_tail->next = n;
        ob_tail = n;
    }
}

static void ob_move_and_prune(void)
{
    Obstacle *prev = NULL;
    Obstacle *cur = ob_head;

    while(cur)
    {
        cur->x--;

        if(cur->x + cur->w <= 0)
        {
            Obstacle *dead = cur;
            cur = cur->next;

            if(prev)
                prev->next = cur;
            else
                ob_head = cur;

            if(dead == ob_tail)
                ob_tail = prev;

            free(dead);
        }
        else
        {
            prev = cur;
            cur = cur->next;
        }
    }
}

static void ob_clear(void)
{
    while(ob_head)
    {
        Obstacle *n = ob_head->next;
        free(ob_head);
        ob_head = n;
    }

    ob_tail = NULL;
}

static int ob_collides(int dh)
{
    Obstacle *o;

    int d_lo = dh + 1;
    int d_hi = dh + DINO_H;

    for(o = ob_head; o; o = o->next)
    {
        int o_lo = o->base + 1;
        int o_hi = o->base + o->h;

        int x_hit = (o->x <= DINO_X + 1) &&
                    (o->x + o->w - 1 >= DINO_X);

        int y_hit = (d_lo <= o_hi) && (o_lo <= d_hi);

        if(x_hit && y_hit)
            return 1;
    }

    return 0;
}

/* HIGH SCORE ARRAY */

static int top_scores[TOPN];

static void top_insert(int score)
{
    int i, j;

    for(i = 0; i < TOPN; i++)
    {
        if(score > top_scores[i])
        {
            for(j = TOPN - 1; j > i; j--)
                top_scores[j] = top_scores[j - 1];

            top_scores[i] = score;
            return;
        }
    }
}

/* RENDERING */

enum { C_DEF, C_GREEN, C_RED, C_YELLOW, C_DIM, C_CYAN, C_WHITE };

static const char *COLORS[] = {
    "\033[0m",
    "\033[32m",
    "\033[31m",
    "\033[33m",
    "\033[90m",
    "\033[36m",
    "\033[1;37m"
};

enum { ST_START, ST_PLAY, ST_PAUSE, ST_OVER };

static int score, frame, ground_off;
static int jumping, jidx, crashed;

static int cloud_x[3] = {10,30,52};
static const int cloud_row[3] = {1,3,2};

static char cell[H][W];
static unsigned char ccol[H][W];

static char out[24000];
static int outlen;

static void emit(const char *s)
{
    int n = (int)strlen(s);

    if(outlen + n < (int)sizeof(out) - 1)
    {
        memcpy(out + outlen, s, n);
        outlen += n;
        out[outlen] = '\0';
    }
}

static void out_flush(void)
{
    fputs(out, stdout);
    fflush(stdout);

    outlen = 0;
    out[0] = '\0';
}

static void put(int row, int col, char ch, int color)
{
    if(row < 0 || row >= H || col < 0 || col >= W)
        return;

    cell[row][col] = ch;
    ccol[row][col] = color;
}

static void put_text(int row, const char *s, int color)
{
    int i;
    int len = (int)strlen(s);
    int start = (W - len) / 2;

    for(i = 0; i < len; i++)
        put(row, start + i, s[i], color);
}

static int level(void)
{
    return 1 + score / 100;
}

static int frame_delay(void)
{
    int d = 80 - level() * 5;
    return d < 40 ? 40 : d;
}

static void render(int dh, int state)
{
    int r, c, i;
    char tmp[256];
    Obstacle *o;

    for(r = 0; r < H; r++)
        for(c = 0; c < W; c++)
        {
            cell[r][c] = ' ';
            ccol[r][c] = C_DEF;
        }

    for(i = 0; i < 3; i++)
    {
        put(cloud_row[i], cloud_x[i], '(', C_DIM);
        put(cloud_row[i], cloud_x[i] + 1, '~', C_DIM);
        put(cloud_row[i], cloud_x[i] + 2, '~', C_DIM);
        put(cloud_row[i], cloud_x[i] + 3, ')', C_DIM);
    }

    for(c = 0; c < W; c++)
        put(GROUND_ROW, c,
            ((c + ground_off) % 11 == 0) ? '.' : '_', C_DIM);

    for(o = ob_head; o; o = o->next)
    {
        int rr, cc;

        for(rr = 0; rr < o->h; rr++)
        {
            for(cc = 0; cc < o->w; cc++)
            {
                int row = GROUND_ROW - (o->base + 1 + rr);

                if(o->type == OB_CACTUS_S)
                    put(row, o->x + cc, 'I', C_GREEN);

                else if(o->type == OB_CACTUS_L)
                    put(row, o->x + cc,
                        (rr == o->h - 1) ? 'M' : '#', C_GREEN);

                else
                {
                    int up = (frame / 3) % 2;
                    char ch = (cc == 0) ?
                        (up ? '^' : 'v') : (up ? 'v' : '^');

                    put(row, o->x + cc, ch, C_YELLOW);
                }
            }
        }
    }

    {
        int head = GROUND_ROW - (dh + 2);
        int body = GROUND_ROW - (dh + 1);
        int col = crashed ? C_RED : C_CYAN;

        put(head, DINO_X, crashed ? 'X' : '@', col);
        put(head, DINO_X + 1, '>', col);

        if(dh > 0 || (frame / 3) % 2 == 0)
        {
            put(body, DINO_X, '/', col);
            put(body, DINO_X + 1, '\\', col);
        }
        else
        {
            put(body, DINO_X, '\\', col);
            put(body, DINO_X + 1, '/', col);
        }
    }

    if(state == ST_START)
    {
        put_text(2, "D I N O   R U N", C_WHITE);
        put_text(4, "Press SPACE to start", C_YELLOW);
    }
    else if(state == ST_PAUSE)
    {
        put_text(3, "- PAUSED - (press P)", C_YELLOW);
    }
    else if(state == ST_OVER)
    {
        put_text(2, "G A M E   O V E R", C_RED);
        put_text(4, "R = play again    Q = quit", C_WHITE);
    }

    emit("\033[H");

    snprintf(tmp, sizeof(tmp),
        "  \033[1;37mDINO RUN\033[0m    Score: \033[33m%05d\033[0m    Hi: \033[32m%05d\033[0m    Level: \033[36m%d\033[0m\033[K\n",
        score, top_scores[0] > score ? top_scores[0] : score, level());

    emit(tmp);

    emit(COLORS[C_DIM]);
    emit("+");

    for(c = 0; c < W; c++)
        emit("-");

    emit("+\033[0m\033[K\n");

    for(r = 0; r < H; r++)
    {
        int cur = -1;

        emit(COLORS[C_DIM]);
        emit("|");
        emit(COLORS[C_DEF]);

        for(c = 0; c < W; c++)
        {
            if(ccol[r][c] != cur)
            {
                cur = ccol[r][c];
                emit(COLORS[cur]);
            }

            {
                char s[2] = {cell[r][c],0};
                emit(s);
            }
        }

        emit(COLORS[C_DIM]);
        emit("|\033[0m\033[K\n");
    }

    emit(COLORS[C_DIM]);
    emit("+");

    for(c = 0; c < W; c++)
        emit("-");

    emit("+\033[0m\033[K\n");

    emit("  \033[90m[SPACE/W/UP] jump   [P] pause   [R] restart   [Q] quit\033[0m\033[K\n");

    if(state != ST_OVER)
        emit("\033[J");

    out_flush();
}

/* GAME LOGIC */

static void fill_queue(Queue *q)
{
    while(q->count < QCAP)
    {
        int roll = rand() % 100;
        int type;

        if(score < 150)
            type = (roll < 60) ? OB_CACTUS_S : OB_CACTUS_L;
        else if(roll < 30)
            type = OB_CACTUS_S;
        else if(roll < 55)
            type = OB_CACTUS_L;
        else if(roll < 78)
            type = OB_BIRD_LOW;
        else
            type = OB_BIRD_HIGH;

        q_push(q, type);
    }
}

static void show_stats(Stack *history)
{
    Stack copy = *history;

    int v, i, n = 1;
    char tmp[128];

    emit("\n  \033[1;37mLast runs (newest first)\033[0m\033[K\n");

    if(copy.top == 0)
        emit("   -\n");

    while(s_pop(&copy, &v))
    {
        snprintf(tmp, sizeof(tmp), "   #%d  %05d\033[K\n", n++, v);
        emit(tmp);
    }

    emit("\n  \033[1;37mTop scores\033[0m\033[K\n");

    for(i = 0; i < TOPN; i++)
    {
        snprintf(tmp, sizeof(tmp), "   %d.  %05d\033[K\n", i + 1, top_scores[i]);
        emit(tmp);
    }

    out_flush();
}

static int play_round(void)
{
    Queue q;

    int paused = 0;
    int spawn_timer = 40;

    score = frame = ground_off = jumping = jidx = crashed = 0;

    ob_clear();
    q_init(&q);
    fill_queue(&q);

    printf("\033[2J");

    for(;;)
    {
        int key = poll_key();
        int dh, i;

        if(key == KEY_QUIT)
            return 1;

        if(key == KEY_RESTART)
            return 2;

        if(key == KEY_PAUSE)
            paused = !paused;

        if(paused)
        {
            render(jumping ? JUMP_PROFILE[jidx > 0 ? jidx - 1 : 0] : 0, ST_PAUSE);
            sleep_ms(100);
            continue;
        }

        if(key == KEY_JUMP && !jumping)
        {
            jumping = 1;
            jidx = 0;
        }

        dh = jumping ? JUMP_PROFILE[jidx] : 0;

        if(jumping && ++jidx >= JUMP_LEN)
            jumping = 0;

        frame++;

        if(frame % 2 == 0)
            score++;

        ground_off++;

        if(frame % 4 == 0)
        {
            for(i = 0; i < 3; i++)
                if(--cloud_x[i] < -4)
                    cloud_x[i] = W + rand() % 10;
        }

        ob_move_and_prune();

        if(--spawn_timer <= 0)
        {
            int type;

            if(q.count < 3)
                fill_queue(&q);

            if(q_pop(&q, &type))
                ob_add(type);

            spawn_timer = 17 + rand() % 18;
        }

        if(ob_collides(dh))
        {
            crashed = 1;
            render(dh, ST_OVER);
            return 0;
        }

        render(dh, ST_PLAY);
        sleep_ms(frame_delay());
    }
}

static int wait_for_key(int accept_jump)
{
    int k;

    for(;;)
    {
        k = poll_key();

        if(k == KEY_QUIT)
            return 0;

        if(k == KEY_RESTART || (accept_jump && k == KEY_JUMP))
            return 1;

        sleep_ms(30);
    }
}

static void cleanup(void)
{
    ob_clear();
    printf("\033[0m\033[?25h\n");
    fflush(stdout);
}

int main(void)
{
    Stack history;

    srand((unsigned)time(NULL));

    s_init(&history);
    memset(top_scores, 0, sizeof(top_scores));

    printf("\033[2J\033[?25l");

    score = frame = ground_off = jumping = jidx = crashed = 0;

    render(0, ST_START);

    if(!wait_for_key(1))
    {
        cleanup();
        return 0;
    }

    for(;;)
    {
        int result = play_round();

        if(result == 1)
            break;

        if(result == 2)
            continue;

        s_push(&history, score);
        top_insert(score);

        show_stats(&history);

        sleep_ms(500);

        while(poll_key() != KEY_NONE) { }

        if(!wait_for_key(1))
            break;
    }

    cleanup();

    return 0;
}