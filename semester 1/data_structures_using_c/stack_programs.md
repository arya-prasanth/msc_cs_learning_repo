# C Stack Practice Programs
---

## 1. Stack Operations Menu (Push, Pop, Peek)
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

## 2. Infix to Postfix Conversion
```c
#include <stdio.h>
#include <ctype.h>
#define MAX 50

char stack[MAX];
int top = -1;

void push(char val)
{
    if (top == MAX - 1) 
    {
        printf("\n Stack Overflow:");
        return; 
    }
    top++; 
    stack[top] = val; 
}
char pop()
{
    char tmp;
    if (top == -1)
    {
        printf("\n Stack Underflow:");
        return '\0';
    }
    tmp = stack[top]; 
    top--; 
    return tmp; 
}
char peek()
{
    if (top == -1)
    {
        return '\0';
    }
    return stack[top];
}

int precedence(char ch)
{
    if (ch == '^') return 3;
    if (ch == '*' || ch == '/'|| ch == '%') return 2;
    if (ch == '+' || ch == '-') return 1;
    return 0; 
}
int main()
{
    char infix[MAX];
    char postfix[MAX];
    int ch;
    int i=0,j=0;
    
    printf("Enter the Infix Expression :");
    scanf("%s",infix);
    
    while(infix[i] != '\0')
    {
    	char token = infix[i];
    	if(isalnum(token))
    	{
    	postfix[j]=token;
    	j++;
  		}
    	else if(token =='(')
    	{
    	push(token);
 	    }
    	else if(token ==')')
		{
    	while (top != -1 && peek() != '(')
    	{
    		postfix[j]=pop();
    		j++;
		}
		pop();
		}
		else
		{
		 while (top != -1 && precedence(peek()) >= precedence(token))
		 {
		 	postfix[j]=pop();
		 	j++;
		 }
		 push(token);
		}
		i++;
	}
    while (top != -1)
                {
                    postfix[j++] = pop();
                }

    postfix[j] = '\0'; 
    printf("Postfix Expression: %s\n", postfix);
	return 0;
}
```

## 3. Postfix Expression Evaluation
```c
#include <stdio.h>
#include <ctype.h> 
#define MAX 50 

int stack[MAX]; 
int top = -1; 

void push(int val)
{
    if (top == MAX - 1) 
    {
        printf("\n Stack Overflow:");
        return; 
    }
    top++; 
    stack[top] = val; 
} 

int pop()
{
    int tmp;
    if (top == -1)
    {
        printf("\n Stack Underflow:");
        return 0; 
    }
    tmp = stack[top]; 
    top--;
    return tmp; 
}

int main()
{
    char postfix[MAX];
    int i = 0;
    int op1, op2, result;

    printf("Enter the postfix expression: ");
    scanf("%s", postfix);

    while (postfix[i] != '\0')
    {
        char ch = postfix[i];

        // Rule 1: If it's a number, convert character to integer and push
        if (isdigit(ch)) 
        {
            push(ch - '0'); // '6' becomes the integer 6
        }
        // Rule 2: If it's an operator, pop two elements, solve, and push result
        else 
        {
            op2 = pop(); // Second operand comes out first
            op1 = pop(); // First operand comes out second

            switch (ch) 
            {
                case '+': result = op1 + op2; break;
                case '-': result = op1 - op2; break;
                case '*': result = op1 * op2; break;
                case '/': result = op1 / op2; break;
                default:
                    printf("\nInvalid operator encountered!");
                    return 1;
            }
            push(result); // Push the calculated answer back onto the stack
        }
        i++;
    }

    // The final remaining value on the stack is the final answer
    printf("ANS: %d\n", pop());

    return 0;
}
```

## 4. String Reversal using Stack
```c
#include <stdio.h>
#include <string.h> 

#define MAX 15 

char stack[MAX]; 
int top = -1; 

// Push function
void push(char val) 
{
    if (top == MAX - 1) 
    {
        printf("\n Stack Overflow:");
        return; 
    }
    top++; 
    stack[top] = val; 
}

// Pop function
char pop()
{
    char tmp; 
    if (top == -1) 
    {
        printf("\n Stack Underflow:");
        return '\0'; 
    }
    tmp = stack[top]; 
    top--; 
    return tmp; 
}

void reverse(char str[])
{
    int len = strlen(str);
    int i;
    
    for (i = 0; i < len; i++)
    {
        push(str[i]);
    }
    
    for (i = 0; i < len; i++)
    {
        str[i] = pop(); 
    }
}

int main()
{ 
    char str[MAX] = "Arya";
    
    printf("Original String: %s\n", str);
    
    reverse(str);
    
    printf("Reversed String: %s\n", str);
    
    return 0;
}
```
