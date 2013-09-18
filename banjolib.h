#include <stdio.h>
#include <stdlib.h>

struct node{
    double value;
    struct node *next;
};

void insert(struct node **head, double value, int mode)
{
//inserts the input double at the head of the list if mode is 1 
//and at the end of the list if mode is 2.

    struct node *newNode = malloc(sizeof(struct node));
    newNode->value=value;
    newNode->next=NULL;
    struct node *temp=*head; 

    if (mode==1)
    {
        *head=newNode;
        (*head)->next=temp;
    }
    else if (mode==2)
    {
        while (temp->next!=NULL)
        {   
            temp=temp->next;
        }
        temp->next=newNode;        

    }

    

}

double delete(struct node **head, int mode)
{
//deletes from the head of the list if mode is 1 
//and from the end of the list if mode is 2
//returns the value deleted.

    struct node *temp;
    double retVal;

    if(mode==1)
    {
        retVal=(*head)->value;
        temp=(*head)->next;
        free(*head);
        *head=temp;
    }
    else if (mode==2)
    {
        temp=*head;
        while(temp->next!=NULL)
        {
            temp=temp->next;
        }
        retVal=temp->value;
        free(temp);

    }


    return retVal;

}

