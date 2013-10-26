#include <stdio.h>
#include <stdlib.h>

typedef struct list{
    double value;
    struct list *next;
} list;

typedef struct tree{
    double value;
    struct tree *left;
    struct tree *right;
} tree; 

int max(int a, int b)
{   
    if (a>b)
        return a;
    else 
        return b;

}

int height(tree *inTree)
{
    int retval;
    if(inTree==NULL)
    {
        retval= 0;
    }
    else
    {
        retval= (1+max(height(inTree->left),height(inTree->right))); 
    }

    //printf("%d\n", retval);
    return retval;

}

int balance(tree *inTree)
{
    int retval;
    if(inTree==NULL)
        retval= 0;
    else
    {
        retval= height(inTree->left)-height(inTree->right);
    }
    //printf("%d balance\n",retval);
    return retval;
}
             
void treeInsert(tree **inTree, double value)
{
    tree *temp;

    if (*inTree==NULL)
    {
        *inTree=(tree *)malloc(sizeof(tree));
        (*inTree)->value=value;
        (*inTree)->left=NULL;
        (*inTree)->right=NULL;
    }
    else if ((*inTree)->value > value)
    {
        treeInsert(&((*inTree)->left), value);
    }
    else {
        treeInsert(&((*inTree)->right),value);
    }
    if (balance(*inTree)>1)
    {
        if(balance((*inTree)->left)<1)
        {
            temp=(*inTree)->left;
            (*inTree)->left=((*inTree)->left)->right;
            temp->right=((*inTree)->left)->left;
            ((*inTree)->left)->left=temp;

        }
        //left too tall

        temp=*inTree;
        *inTree=(*inTree)->left;
        temp->left=(*inTree)->right;
        (*inTree)->right=temp;
        printf("fixed left imbalance\n");

    }
    if (balance(*inTree)<-1)
    {
        //right too tall
        if(balance((*inTree)->right)>1)
        {
            temp=(*inTree)->right;
            (*inTree)->right=((*inTree)->right)->left;
            temp->left=((*inTree)->right)->right;
            ((*inTree)->right)->right=temp;

        }

        temp=*inTree;
        *inTree=(*inTree)->right;
        temp->right=(*inTree)->left;
        (*inTree)->left=temp;


    }
}


void inorder(tree *inTree)
{

    if (inTree!=NULL)
    {   
        inorder(inTree->left);
        printf("%f ", inTree->value);
        inorder(inTree->right);
    }

}
        
void preorder(tree *inTree)
{
    if (inTree!=NULL)
    {
        printf("%f ", inTree->value);
        preorder(inTree->left); 
        preorder(inTree->right);
    }
}

void postorder(tree *inTree)
{
    if (inTree!=NULL)
    {
        postorder(inTree->left);
        postorder(inTree->right);
        printf("%f ", inTree->value);
    }
}



tree ** find_node(tree **inTree, double value)
{

    tree **retval;

    if ((*inTree)==NULL)
        retval=NULL;
    else if (((*inTree)->value)==value)
        retval=inTree;
    else if (((*inTree)->value>value))
        retval=find_node(&((*inTree)->left),value) ;
    else 
        retval=find_node((&(*inTree)->right),value);

    return retval;
}

void delete_node(tree **inTree, double value)
{
    //need to take the balancing code out of insert into a function of its own 
    //and then call it both after insertion and deletion
    
    tree **node, **sptr;
    tree *temp;
    node=find_node(inTree, value);
    if (*node!=NULL)
    {
        if((*node)->left==NULL && (*node)->right==NULL)
        {
            //remove node safely
            free(*node);
        }
        else if((*node)->right==NULL)
        {
            temp=*node;
            *node=(*node)->left;
            free(temp);
            //replace with left 
        }
        else if((*node)->left==NULL)
        {
            temp=*node;
            *node=(*node)->right;
            free(temp);
            //replace with right
        }
        else
        {
            sptr=&((*node)->right);
            while((*sptr)->left!=NULL)
            {
                sptr=&((*sptr)->left);

            }
            (*node)->value=(*sptr)->value;
            temp=*sptr;
            (*sptr)=(*sptr)->right;
            free(temp);
        }

    }


}
   
      
      


void insert(list **head, double value, int mode)
{
//inserts the input double at the head of the list if mode is 1 
//and at the end of the list if mode is 2.

    list *newNode = malloc(sizeof(list));
    newNode->value=value;
    newNode->next=NULL;
    list *temp=*head; 

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

double delete(list **head, int mode)
{
//deletes from the head of the list if mode is 1 
//and from the end of the list if mode is 2
//returns the value deleted.

    list *temp;
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

#define qCapacity 20

typedef struct queue{
    int capacity;
    int start;
    int length;
    double *values;
} queue;

queue *makeQueue()
{
    queue *myQueue;

    myQueue = (queue *)malloc(sizeof(queue));
    myQueue->start=0;
    myQueue->length=0;
    myQueue->capacity=qCapacity;
    myQueue->values=(double *)malloc(sizeof(double)*qCapacity);
    return myQueue;

} 

void enqueue(queue *myQueue, double value)
{
    if((myQueue->length)<(myQueue->capacity))
    {   
        (myQueue->values)[(myQueue->start+myQueue->length)%(myQueue->capacity)]=value;
        (myQueue->length)+=1;
    }
}   

double dequeue(queue *myQueue)
{
    double rval;
    if (myQueue->length==0)
        rval=-1;
    else{
        rval=(myQueue->values[myQueue->start]);
        (myQueue->start)=((myQueue->start)+1)%(myQueue->capacity);
        (myQueue->length)-=1;
    }
    
    return rval;
}
