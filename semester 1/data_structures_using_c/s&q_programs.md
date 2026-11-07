# Stack and Queue Data Structure Implementations in C
---

## 1. Stack Implementation (Menu Driven)
```c
#include <stdio.h> 
#define MAX 5 //Setting up MAX constant to 5
int stack [MAX]; //Declaring array stack of MAX size
int top = -1; //Declaring variable top and initializing it with value -1

// Push () -- increment top and then insert value
void push(int val)
{
    if (top == MAX -1) //Evaluating Overflow Condition
    {
        printf ("\n Stack Overflow:");
        return; //Coming out of the Push Function
    }
    top++; //Incrementing top variable by 1.
    stack [top] = val; //Placing the data-element on the top of the stack
}

//Pop -- Take value then decrement top
int pop()
{
    int tmp;
    if (top == -1) // Checking for Underflow condition
    {
        printf("\n Stack Underflow:");
        return 0; //Retuning 0 when stack underflows
    }
    tmp = stack[top]; //Copying data-element in the tmp variable
    top--; //Decreasing top variable by 1
    return tmp; //Returning the value back to the caller function
}

// just displaying the current value on top.
int peek()
{
    int tmp;
    if (top == -1)
    {
        printf ("\n Stack Underflow:");
        return 0;
    }
    tmp = stack[top];
    return tmp;
}

int main()
{
    int ch,val;
    do
    {
        printf("Enter Stack Operations: 1. PUSH() , 2. POP() , 3. PEEK() , 4. Exit.\n");
        scanf("%d",&ch);
        switch(ch)
        {
            case 1: printf("Enter the element to be pushed:");
                    scanf("%d",&val);
                    push(val);
                    break;
            case 2: val=pop();
                    printf("The element popped out is: %d\n", val);
                    break;
            case 3: val=peek();
                    printf("The current top value is : %d\n", val);
                    break;
            case 4: printf("Exiting..");
                    return 0;
            default: printf("Enter  a valid option\n");
                    break;
        }
    }while(ch!=4);
   
}
```

## 2. Stack Application: String Reversal
```c
#include<stdio.h> 
#define SIZE 10 
char stack[SIZE]; 
int top=-1; 

void push(char ch) 
{ 
    if(top==SIZE-1)
        return; 
    top++; 
    stack[top]=ch; 
} 

char pop() 
{ 
    char tmp; 
    if(top==-1) 
        return '\0'; 
    tmp=stack[top]; 
    top--; 
    return tmp; 
} 

void main() 
{ 
    char name[10]; 
    int i; 
    printf("Enter Any String:"); 
    scanf("%s", name); 
    
    for(i=0;name[i]!='\0';i++) 
        push(name[i]); 
        
    printf("Reverse String is:"); 
    while(top>-1) 
    { 
        printf("%c",pop()); 
    } 
} 
```

## 3. Linear Queue Implementation
```c
#include <stdio.h> 
#include <stdbool.h> 
#define MAXSIZE 5 
int queue[MAXSIZE]; 
int front = 0; 
int rear = -1;

//-------------------- Is Full -------------------- 
bool isfull() 
{ 
    if (rear == MAXSIZE - 1) 
        return true; 
    else 
        return false; 
} 

//-------------------- Is Empty -------------------- 
bool isempty() 
{ 
    if (front > rear) 
        return true; 
    else 
        return false; 
} 

//-------------------- Enqueue -------------------- 
int enqueue(int data) 
{ 
    if (isfull()) 
        return 0; 
    rear = rear + 1; 
    queue[rear] = data; 
    return 1; 
} 

//-------------------- Dequeue -------------------- 
int dequeue() 
{ 
    if (isempty()) 
        return -1; 
    int data = queue[front]; 
    front = front + 1; 
    return data; 
} 

//-------------------- Peek --------------------
int peek() 
{ 
    if (isempty()) 
        return -1; 
    return queue[front]; 
} 

//-------------------- Display -------------------- 
void display() 
{ 
    if (isempty()) 
    { 
        printf("\nQueue is Empty.\n"); 
        return; 
    } 
    printf("\nQueue Elements : "); 
    for (int i = front; i <= rear; i++) 
        printf("%d ", queue[i]); 
    printf("\n"); 
} 

//-------------------- Main -------------------- 
int main() 
{ 
    int choice, value, result; 
    do 
    { 
        printf("\n------ QUEUE MENU ------\n"); 
        printf("1. Enqueue\n"); 
        printf("2. Dequeue\n"); 
        printf("3. Peek\n"); 
        printf("4. Display\n"); 
        printf("5. Exit\n"); 
        printf("Enter your choice : "); 
        scanf("%d", &choice); 
        switch (choice)
        { 
            case 1: 
                printf("Enter value : "); 
                scanf("%d", &value); 
                result = enqueue(value); 
                if (result == 1) 
                    printf("Element inserted successfully.\n"); 
                else 
                    printf("Queue is Full.\n"); 
                break; 
            case 2: 
                result = dequeue(); 
                if (result == -1) 
                    printf("Queue is Empty.\n"); 
                else 
                    printf("Deleted element = %d\n", result); 
                break; 
            case 3: 
                result = peek(); 
                if (result == -1) 
                    printf("Queue is Empty.\n"); 
                else 
                    printf("Front element = %d\n", result); 
                break; 
            case 4: 
                display(); 
                break; 
            case 5: 
                printf("Program Ended.\n"); 
                break; 
            default: 
                printf("Invalid Choice.\n");
        } 
    } while (choice != 5); 
    return 0; 
} 
```

## 4. Circular Queue Implementation
```c
#include <stdio.h> 
#include <stdbool.h> 
#define MAXSIZE 5 
int queue[MAXSIZE]; 
int front = -1; 
int rear = -1; 

//-------------------- Is Full -------------------- 
bool isfull() 
{ 
    // Queue is full when the next position after rear equals front  
    return (rear + 1) % MAXSIZE == front; 
} 

//-------------------- Is Empty -------------------- 
bool isempty() 
{ 
    return front == -1; 
} 

//-------------------- Enqueue -------------------- 
int enqueue(int data) 
{ 
    if (isfull()) 
        return 0; 
    if (isempty()) 
    {
        // First element being added 
        front = 0; 
        rear = 0; 
    } 
    else 
    { 
        // Advance rear circularly 
        rear = (rear + 1) % MAXSIZE; 
    } 
    queue[rear] = data; 
    return 1; 
} 

//-------------------- Dequeue -------------------- 
int dequeue() 
{ 
    if (isempty()) 
        return -1; 
    int data = queue[front]; 
    if (front == rear) 
    { 
        // Queue becomes empty after removing the last element  
        front = -1; 
        rear = -1; 
    } 
    else 
    { 
        // Advance front circularly 
        front = (front + 1) % MAXSIZE; 
    } 
    return data; 
} 

//-------------------- Peek -------------------- 
int peek() 
{
    if (isempty()) 
        return -1; 
    return queue[front]; 
} 

//-------------------- Display -------------------- 
void display() 
{ 
    if (isempty()) 
    { 
        printf("\nQueue is Empty.\n"); 
        return; 
    } 
    printf("\nQueue Elements : "); 
    int i = front; 
    while (true) 
    { 
        printf("%d ", queue[i]); 
        if (i == rear) 
            break; 
        i = (i + 1) % MAXSIZE; 
    } 
    printf("\n"); 
} 

//-------------------- Main -------------------- 
int main() 
{ 
    int choice, value, result; 
    do 
    { 
        printf("\n------ CIRCULAR QUEUE MENU ------\n");  
        printf("1. Enqueue\n"); 
        printf("2. Dequeue\n"); 
        printf("3. Peek\n");
        printf("4. Display\n"); 
        printf("5. Exit\n"); 
        printf("Enter your choice : "); 
        scanf("%d", &choice); 
        switch (choice) 
        { 
            case 1: 
                printf("Enter value : "); 
                scanf("%d", &value); 
                result = enqueue(value); 
                if (result == 1) 
                    printf("Element inserted successfully.\n");  
                else 
                    printf("Queue is Full.\n"); 
                break; 
            case 2: 
                result = dequeue(); 
                if (result == -1) 
                    printf("Queue is Empty.\n"); 
                else 
                    printf("Deleted element = %d\n", result);  
                break; 
            case 3: 
                result = peek(); 
                if (result == -1) 
                    printf("Queue is Empty.\n"); 
                else 
                    printf("Front element = %d\n", result);  
                break;
            case 4: 
                display(); 
                break; 
            case 5: 
                printf("Program Ended.\n"); 
                break; 
            default: 
                printf("Invalid Choice.\n"); 
        } 
    } while (choice != 5); 
    return 0; 
} 
```

## 5. Simpler Circular Queue Interface
```c
#include <stdio.h> 
#include <stdbool.h> 
#define MAXSIZE 5 
int queue[MAXSIZE]; 
int front = -1; 
int rear = -1; 

//-------------------- Enqueue -------------------- 
void enqueue(int data) 
{ 
    // Overflow Check 
    if ((rear + 1) % MAXSIZE == front) 
    { 
        printf("\nOVERFLOW: Queue is Full! Cannot insert %d.\n", data);  
        return; 
    }
    if (front == -1) /* Initially empty */ 
    { 
        front = 0; 
        rear = 0; 
    } 
    else 
    { 
        rear = (rear + 1) % MAXSIZE; // Circular step  
    } 
    queue[rear] = data; 
    printf("\nElement %d inserted successfully.\n", data); 
} 

//-------------------- Dequeue -------------------- 
int dequeue() 
{ 
    // Underflow Check 
if (front == -1)
{
printf("\nUNDERFLOW: Queue is Empty.\n");
return -1;
}
int data = queue[front];
if (front == rear) /* Last remaining element removed */
{
front = -1;
rear = -1;
}
else
{
front = (front + 1) % MAXSIZE; // Circular step
}
return data;
}
//-------------------- Peek --------------------
void peek()
{
if (front == -1)
{
printf("\nQueue is Empty.\n");
return;
}
printf("\nFront element = %d\n", queue[front]);
}
//-------------------- Display --------------------
void display()
{
if (front == -1)
{
printf("\nQueue is Empty.\n");
return;
}
printf("\nQueue Elements : ");
int i = front;
while (true)
{
printf("%d ", queue[i]);
if (i == rear)
break;
i = (i + 1) % MAXSIZE;
}
printf("\n");
}
//-------------------- Main --------------------
int main()
{
int choice, value, deleted;
do
{
printf("\n------ CIRCULAR QUEUE MENU ------\n");
printf("1. Enqueue\n");
printf("2. Dequeue\n");
printf("3. Peek\n");
printf("4. Display\n");
printf("5. Exit\n");
printf("Enter your choice: ");
scanf("%d", &choice);
switch(choice)
{
case 1:
printf("Enter value to insert: ");
scanf("%d", &value);
enqueue(value);
break;
case 2:
deleted = dequeue();
if(deleted != -1)
printf("\nPopped element: %d\n", deleted);
break;
case 3:
peek();
break;
case 4:
display();
break;
case 5:
printf("Exiting Program.\n");
break;
default:
printf("Invalid Choice.\n");
}
} while (choice != 5);
return 0;
}
