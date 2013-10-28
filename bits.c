#include <stdio.h>
#include <stdlib.h>

int main(int argc, char **argv)
{
//prints the string representation of an integer    
    int test,i;
    test = atoi(argv[1]);
    char *stringrep;
  
    stringrep=(char *)malloc(33*sizeof(char));
    stringrep[32]='\0';
      
    for(i=31;i>=0;i--)
    {
        stringrep[i]=(test & 1) + '0';
        test>>=1;
    }

    printf("%s\n",stringrep);

    return 0;
}
