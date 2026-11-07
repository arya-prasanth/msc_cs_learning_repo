# C Programming Basics & Control Flows
---

## 1. Read and Print Student Info
```c
#include<stdio.h>
int main()
{
	char name[10];
	int age;
	printf("Enter the name:");
	scanf(" %s", name);
	printf("Enter age:");
	scanf("%d",&age);
	printf("Hello %s, you are %d years old",name,age);
	return 0;
}
```

## 2. Notebook Bill Calculator
```c
#include<stdio.h>
int main()
{
	int price,amt,total;
	printf("Enter the price of the notebook: \t");
	scanf("%d",&price);
	printf("Quantity purchased:");
	scanf("%d",&amt);
	printf("Price : %d Quantity : %d",price,amt);
	total= amt*price;
	printf("\t Total Bill : %d",total);	
	return 0;
}
```

## 3. Square Area and Perimeter
```c
#include<stdio.h>
int main()
{
	int side,area,peri;
	printf("Enter a side:");
	scanf("%d",&side);
	area = side*side;
	peri = 4 * side;
	printf("\t Area : %d",area);
	printf("\t Perimeter : %d",peri);
	return 0;
}
```

## 4. Gross Salary Calculator (DA & HRA)
```c
#include<stdio.h>
int main()
{
	float sal,da,hra,tsal;
	printf("Enter the basic salary:");
	scanf("%f",&sal);
	da = sal * 0.10;
	hra = sal * 0.15;
	tsal = sal + da + hra;
	printf("\t Gross Salary : %f",tsal);
	return 0;
}
```

## 5. Count Total Digits in a Number
```c
#include<stdio.h>
int main()
{
	int n,num,count=0;
	printf("Enter a number:");
	scanf("%d",&num);
	n = num;
	while(n!=0)
	{ 
		n=n/10;
		count++;
	}
	printf("\n The total number of digits in %d is %d",num,count);
	return 0;
}
```

## 6. Temperature Check (Fever status)
```c
#include<stdio.h>
int main()
{
	int temp;
	printf("Enter the temperature:");
	scanf("%d",&temp);
	if(temp>100)
		printf("FEVER");
	else
		printf("Normal");
	return 0;
}
```

## 7. Exam Eligibility Checker
```c
#include<stdio.h>
int main()
{
	int atd;
	printf("Enter the percentage:");
	scanf("%d",&atd);
	if(atd>=75)
		printf("Eligible for exam");
	else
		printf("Not Eligible for exam");
	return 0;
}
```

## 8. Student Grade Pass/Fail Evaluation
```c
#include<stdio.h>
int main()
{
	int marks;
	printf("Enter the marks:");
	scanf("%d",&marks);
	if(marks>=40)
		printf("PASS");
	else
		printf("FAIL");
	return 0;
}
```

## 9. Age Classification Status
```c
#include<stdio.h>
int main()
{
	int age;
	printf("Enter the age:");
	scanf("%d",&age);
	if(age<=12)
		printf("Child");
	else if(age<=19)
		printf("Teenager");
	else if(age<=59)
		printf("Adult");
	else
		printf("Senior Citizen");
	return 0;
}
```

## 10. Electricity Bill Calculator 
```c
#include<stdio.h>
int main()
{
	int unit,amt=0;
	printf("Enter the Electricity Units consumed: ");
	scanf("%d",&unit);
	
	if(unit>500)
	{
		amt+=(unit-500)*10; 
		unit=500;
	}
	if(unit>100)
	{
		amt+=(unit-100)*8; 
		unit=100;
	}
	amt+=unit*5;
	
	printf("The total bill is %d",amt);
	return 0;
}
```

## 11. Character Type Classifier
```c
#include<stdio.h>
int main()
{
    char ch;
    printf("Enter a character: ");
    scanf("%c", &ch);
    if (ch >= 65 && ch <= 90)
    {
        printf("Uppercase Alphabet");
    }
    else if (ch >= 97 && ch <= 122)
    {
        printf("Lowercase Alphabet");
    }
    else if (ch >= 48 && ch <= 57)
    {
        printf("Digit");
    }
    else
    {
        printf("Special Character");
    }
    return 0;
}
```

## 12. Display Even Numbers Up to N
```c
#include <stdio.h>
int main()
{
    int n, i;
    printf("Enter the value of n: ");
    scanf("%d", &n);
    for(i = 2; i <= n; i = i + 2)
    {
        printf("%d ", i);
    }
    return 0;
}
```

## 13. Count Numbers Divisible by 3 Up to N
```c
#include <stdio.h>
int main()
{
    int n, i, count = 0;
    printf("Enter the value of n: ");
    scanf("%d", &n);
    for(i = 1; i <= n; i++)
    {
        if(i % 3 == 0)
        {
            count++;
        }
    }
    printf("Count = %d", count);
    return 0;
}
```

## 14. Find Largest Element Without Arrays
```c
#include <stdio.h>
int main()
{
    int n, i, num, largest;
    printf("Enter the number of elements: ");
    scanf("%d", &n);
    if(n <= 0) return 0;
    
    printf("Enter the numbers:\n");
    scanf("%d", &largest);
    for(i = 2; i <= n; i++)
    {
        scanf("%d", &num);
        if(num > largest)
        {
            largest = num;
        }
    }
    printf("Largest number = %d", largest);
    return 0;
}
```

## 15. Sign and Zero Counter
```c
#include <stdio.h>
int main()
{
    int n, i, num;
    int p = 0, ne = 0, z = 0;
    printf("Enter n: ");
    scanf("%d", &n);
    for(i = 1; i <= n; i++)
    {
        scanf("%d", &num);
        if(num > 0)
            p++;
        else if(num < 0)
            ne++;
        else
            z++;
    }
    printf("Positive = %d\n", p);
    printf("Negative = %d\n", ne);
    printf("Zero = %d", z);
    return 0;
}
```

## 16. Factorial Computation
```c
#include <stdio.h>
int main()
{
    int n, i, fact = 1;
    printf("Enter a number: ");
    scanf("%d", &n);
    for(i = 1; i <= n; i++)
    {
        fact = fact * i;
    }
    printf("Factorial = %d", fact);
    return 0;
}
```

## 17. Fibonacci Series Generator
```c
#include <stdio.h>
int main()
{
    int n, a = 0, b = 1, c, i;
    printf("Enter n: ");
    scanf("%d", &n);
    for(i = 1; i <= n; i++)
    {
        printf("%d ", a);
        c = a + b;
        a = b;
        b = c;
    }
    return 0;
}
```

## 18. Cumulative Sum of Digits
```c
#include<stdio.h>
int main()
{
	int n,r,v,temp;
	int sum=0; 
 	int	s=0;
	printf("Enter the number:");
	scanf("%d",&n);
	temp=n;
	while(temp!=0)
	{
		r= temp%10;
		sum=sum+r;
		temp=temp/10;
	}
	while(sum>9)
	{
		s=0;
		while(sum!=0)
		{
			v=sum%10;
			s=s+v;
			sum=sum/10;
		}
		sum = s;
	}
	printf("Sum of digits : %d", sum);
	return 0;
}
```

## 19. Pin Access Security Authenticator
```c
#include<stdio.h>
int main()
{
	int pswd=1234;
	int count=1,p,i=1;
	
	while(i<=3)
	{
		printf("\n Enter PIN:");
		scanf("%d",&p);
		if(p==pswd)
		{
			printf("\n Successful");
			break;
		}
		else
		{
			printf("Try Again! Attempts Used: %d",count);
			count++;
		}
		if(count>3)
		{
			printf("\n Account Locked!!");
		}
		i++;
	}
	return 0;	
}
```

## 20. Single Digit Reduction Tracker
```c
#include<stdio.h>
int main()
{
	int n,r,v,temp;
	int sum=0; 
 	int s=0;
	printf("Enter the number:");
	scanf("%d",&n);
	temp=n;
	while(temp!=0)
	{
		r= temp%10;
		sum=sum+r;
		temp=temp/10;
	}
	while(sum>9)
	{
		s=0;
		while(sum!=0)
		{
			v=sum%10;
			s=s+v;
			sum=sum/10;
		}
		sum = s;
	}
	printf("Sum of digits : %d", sum);
	return 0;
}
```

## 21. Multi-Candidate Voting Counter
```c
#include<stdio.h> 
int main() {
    int vote; 
    int a=0,b=0,c=0, v, i = 0; 
    printf("Enter the number of Voters: ");
    scanf("%d", &v); 
    while(i < v) {
        printf("\nEnter the Vote (1, 2, or 3): ");
        scanf("%d", &vote); 
        switch(vote) {
            case 1: 
                printf("You voted Candidate 1\n"); 
                a++; break; 
            case 2: 
                printf("You voted Candidate 2\n"); 
                b++; break; 
            case 3: 
                printf("You voted Candidate 3\n"); 
                c++; break; 
            default: 
                printf("Invalid Choice! Vote not counted.\n"); 
        }
        i++;
    }
    printf("\n--- Final Vote Count ---");
    printf("\nCandidate 1: %d", a);
    printf("\nCandidate 2: %d", b);
    printf("\nCandidate 3: %d\n", c);
    return 0;
}
```
