#include <iostream>
#include <stdlib.h>
#include <vector>


template<typename T>
T max(T val1, T val2)
{
    return val1>val2 ? val1 : val2;
}


template <typename T>

class BinaryTree {

    struct tree{
        T value;
        tree *left;
        tree *right;
        
        tree(T invalue)
        {
            value=invalue;
            left=NULL;
            right=NULL;
        }
    }; 

    private:
        tree *root;
        std::vector<tree> myRep;
        std::vector<int> places;
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
            return retval;
        }
             
        void rebalance(tree *&inTree)
        {
            tree *temp;
            int bal;
            bal=balance(inTree);
            if (bal > 1)
            {
                if(balance(inTree->left)<1)
                {
                    temp=inTree->left;
                    inTree->left=(inTree->left)->right;
                    temp->right=(inTree->left)->left;
                    (inTree->left)->left=temp;

                }

                temp=inTree;
                inTree=inTree->left;
                temp->left=inTree->right;
                inTree->right=temp;

            }
            else if (bal < -1)
            {
                if(balance(inTree->right)>1)
                {
                    temp=inTree->right;
                    inTree->right=(inTree->right)->left;
                    temp->left=(inTree->right)->right;
                    (inTree->right)->right=temp;

                }

                temp=inTree;
                inTree=inTree->right;
                temp->right=inTree->left;
                inTree->left=temp;


            }
        }


        void treeInsert(tree *&inTree, T invalue)
        {
            if (inTree==NULL)
            {
                inTree = new tree(invalue);
            }
            else if (invalue > inTree->value)
            {          
                treeInsert(inTree->right, invalue);
            }
            else {
                treeInsert(inTree->left,invalue);
            }   
            rebalance(inTree);
        }
    
        void treeRemove(tree *&inTree, T invalue)
        {
            //need to take the balancing code out of insert into a function of its own 
            //and then call it both after insertion and deletion
   
            tree *temp, *temp2; 
            if(inTree==NULL)
                return;
            if(inTree->value>invalue)
            {
                treeRemove(inTree->left, invalue);
            }
            else if(inTree->value<invalue)
            {
                treeRemove(inTree->right, invalue);
            }
            else
            {
                if(inTree->left==NULL && inTree->right==NULL)
                {
                    delete inTree;
                    inTree=NULL; 
                    std::cout<<"case 1\n";
                }
                else if(inTree->right==NULL)
                {
                    temp=inTree->left;
                    delete inTree;
                    inTree=NULL;
                    std::cout<<"case 2\n";
                    inTree=temp;
                    //replace with left 
                }
                else if(inTree->left==NULL)
                {
                    temp=inTree->right;
                    delete inTree;
                    std::cout<<"case 3\n";
                    inTree=temp;
                    //replace with right
                }
                else
                {
                    temp=inTree->right;
                    while(temp->left!=NULL)
                    {
                        temp2=temp;
                        temp=temp->left;
    
                    }
                    std::cout<<temp->value<<" "<<temp2->value<<" "<<inTree->value<<"\n";
                    inTree->value=temp->value;
                    temp2->left=temp->right;
                    std::cout<<"case 4\n";

                    delete temp;
                }

            }   

            rebalance(inTree);
        }


        void inorder(tree *inTree)
        {
            if (inTree!=NULL)
            {   
                inorder(inTree->left);
                std::cout<<(inTree->value)<<" ";
                inorder(inTree->right);
            }

        }
        
        void preorder(tree *inTree)
        {
            if (inTree!=NULL)
            {
                std::cout<<(inTree->value)<<" ";
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
                std::cout<<(inTree->value)<<" ";
            }
        }   

        void repfunc(tree *inTree)
        {
            int place=0;
            int level_width, last;

            if (inTree!=NULL){
                myRep.push_back(*inTree);
                places.push_back(1);          
            }
            do{
                if (myRep[place].left!=NULL)
                {
                    myRep.push_back(*(myRep[place].left));
                    places.push_back(places[place]*2);
                    std::cout<<places[place]*2<<" ";

                }
                if (myRep[place].right!=NULL)
                {
                    myRep.push_back(*(myRep[place].right));
                    places.push_back(places[place]*2 + 1);
                    std::cout<<places[place]*2 + 1<<" ";
                }
                ++place;
            }while(place<myRep.size());
            std::cout<<"\n";
            
            level_width=1;
            last=1;
            for(place=0;place<myRep.size();place++)
            {
                std::cout<<myRep[place].value<<" ";
                if(places[place]>=last)
                {
                    std::cout<<"\n";
                    level_width*=2;
                    last+=level_width;
                }
            }
            std::cout<<"\n";
        }

    public:
        BinaryTree()
        {
            root=NULL;
        }

        void insert(T invalue)
        {
            treeInsert(root, invalue);     
        }

        void remove(T invalue)
        {
            treeRemove(root, invalue);
        }
        
        void inorder()
        {
            inorder(root);
            std::cout<<"\n";
        }
        
        void preorder()
        {   
            preorder(root);
            std::cout<<"\n";
        }

        void postorder()
        {
            postorder(root);
            std::cout<<"\n";
        }
        void showTree()
        {
            myRep.clear();
            places.clear();
            repfunc(root);
        }

};

    
    
   
       
      
      



