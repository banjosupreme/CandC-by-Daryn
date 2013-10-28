#include <stdlib.h>
#include <stdio.h>

void swap(int array[], int loc1, int loc2)
{
    int temp;
    temp=array[loc1];
    array[loc1]=array[loc2];
    array[loc2]=temp;
}

void msort(int array[], int low, int high)
{
    int pivot,i;
    int good;
    if(high<=low)
        return; 
    pivot=(high+low)/2;

    swap(array,high,pivot);

    good=low;

    for(i=low;i<high;i++)
    {
        if (array[i]<array[high])
        {
            swap(array,good,i);
            good++;
        }
    }
    swap(array,high,good);

    msort(array,low,good-1);
    msort(array,good+1,high);


}

void merge(int array[], int loc1, int loc2, int last)
{
    int i,j,k;
    int * temp;
    temp=(int *)malloc((last-loc1+1)*sizeof(int));

    i=loc1;
    j=loc2;
    k=0;
    while((i<loc2) && (j<=last))
    {
        if(array[i]<array[j])
        {
            temp[k]=array[i];
            ++i;
            ++k;
        }
        else{
            temp[k]=array[j];
            ++j;
            ++k;
        }
   
    }
    while(i<loc2)
    {
        temp[k]=array[i];
        ++i;
        ++k;
    }
    while(j<=last)
    {
        temp[k]=array[j];
        ++j;
        ++k;
    }

    for(k=0;k<(last-loc1+1);k++)
    {
        array[loc1+k]=temp[k];
    }

}

void mergesort2(int array[], int low, int high)
{

    
    int pivot;
    if(low==high)
        return;

    pivot=(low+high)/2;
    mergesort2(array,low,pivot);
    mergesort2(array,pivot+1,high);   
    merge(array,low,pivot+1,high);

}

int main(int argc, char **argv)
{
    int *array, i; 
    array = (int *)malloc((argc-1)*sizeof(int));

    for(i=0;i<argc-1;i++)
    {
        array[i]=atoi(argv[i+1]);
    }
    mergesort2(array,0,argc-2);

    for(i=0;i<argc-1;i++)
    {
        printf("%d ", array[i]);
    }
    return 0;
}
