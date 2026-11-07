# C Array Practice Programs

This file contains 8 essential C programs demonstrating various operations on arrays, such as reading, displaying, summing, calculating average, separating odd/even numbers, counting, and finding minimum/maximum elements.

---

## 1. Read and Display an Array
```c
#include<stdio.h> 
int main() { 
    int a[5],i; 
    for(i=0;i<=4;i++) { 
        printf("Enter the elements : "); 
        scanf("%d",&a[i]); 
    } 
    for(i=0;i<=4;i++) { 
        printf("\n Elements : %d \n",a[i]); 
    } 
    return 0; 
}
```

## 2. Display 4th and 5th Position Values from Array
```c
#include<stdio.h> 
int main() { 
    int a[5],i; 
    for(i=0;i<=4;i++) { 
        printf("Enter the elements : "); 
        scanf("%d",&a[i]); 
    } 
    printf("\n 4th and 5th elements are : %d and %d\n",a[3],a[4]); 
    return 0; 
}
```

## 3. Sum of Array Elements
```c
#include<stdio.h> 
int main() { 
    int a[5],i,sum=0; 
    for(i=0;i<=4;i++) { 
        printf("Enter the elements : "); 
        scanf("%d",&a[i]); 
        sum=sum+a[i]; 
    } 
    printf("\n Sum of Array Elements are : %d \n",sum); 
    return 0; 
}
```

## 4. Average of Array Elements
```c
#include<stdio.h> 
int main() { 
    int a[5],i,sum=0,avg=0; 
    for(i=0;i<=4;i++) { 
        printf("Enter the elements : "); 
        scanf("%d",&a[i]); 
        sum=sum+a[i]; 
    } 
    avg=sum/5; 
    printf("\n Average of Array Elements are : %d \n",avg); 
    return 0; 
}
```

## 5. Sum of Even and Odd Numbers
```c
#include<stdio.h> 
int main() { 
    int a[5],i,sum_e=0,sum_o=0; 
    for(i=0;i<=4;i++) { 
        printf("Enter the elements : "); 
        scanf("%d",&a[i]); 
        if(a[i]%2==0) { 
            sum_e=sum_e+a[i]; 
        } else { 
            sum_o=sum_o+a[i]; 
        } 
    } 
    printf("\n Sum of Even Numbers are : %d \n",sum_e); 
    printf("\n Sum of Odd Numbers are : %d \n",sum_o); 
    return 0; 
}
```

## 6. Display Even and Odd Numbers Separately
```c
#include <stdio.h> 
int main() { 
    int a[5], e[5], o[5]; 
    int i, j = 0, k = 0; 
    for(i = 0; i <= 4; i++) { 
        printf("Enter element : "); 
        scanf("%d", &a[i]); 
        if(a[i] % 2 == 0) { 
            e[j] = a[i]; 
            j++; 
        } else { 
            o[k] = a[i]; 
            k++; 
        } 
    } 
    printf("\n Even Numbers in Array are: "); 
    for(i = 0; i < j; i++) { 
        printf("%d ", e[i]); 
    } 
    printf("\n Odd Numbers in Array are: "); 
    for(i = 0; i < k; i++) { 
        printf("%d ", o[i]); 
    } 
    return 0; 
}
```

## 7. Count Even and Odd Numbers
```c
#include <stdio.h> 
int main() { 
    int num, i, ecount = 0, ocount = 0; 
    for(i = 1; i <= 5; i++) { 
        printf("Enter element %d: ", i); 
        scanf("%d", &num); 
        if(num % 2 == 0) { 
            ecount++; 
        } else { 
            ocount++; 
        } 
    } 
    printf("\nTotal Even Numbers: %d", ecount); 
    printf("\nTotal Odd Numbers: %d\n", ocount); 
    return 0; 
}
```

## 8. Display the Largest and Smallest Element
```c
#include <stdio.h> 
int main() { 
    int a[5], i; 
    int max, min; 
    for(i = 0; i <= 4; i++) { 
        printf("Enter the elements : "); 
        scanf("%d", &a[i]); 
    } 
    max = a[0]; 
    min = a[0]; 
    for(i = 1; i <= 4; i++) { 
        if(a[i] > max) { 
            max = a[i]; 
        } 
        if(a[i] < min) { 
            min = a[i]; 
        } 
    } 
    printf("\n Largest Element is : %d \n", max); 
    printf("\n Smallest Elements is : %d \n", min); 
    return 0; 
}
```

