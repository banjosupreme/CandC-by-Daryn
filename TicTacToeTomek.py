import csv

def haswinner(inarray):
    
    xcount=0
    ocount=0

    for i in range(len(inarray)):
        if inarray[i]=='.':
            break
        elif inarray[i]=='X':
            xcount+=1
        elif inarray[i]=='O':
            ocount+=1
        elif inarray[i]=='T':
            xcount+=1
            ocount+=1
        

    return (xcount,ocount)

def check(xcount,ocount):

    if xcount==4:
        return('X won')
    elif ocount==4:
        return('O won')
    else:
        return False

def tictactoetomek(fname):

    infile=open(fname,"r");
    outfile=open(fname+".out","wb")
 
    numcases=int(infile.readline())

    result=False

    for i in range(numcases):
        board=[]    
        result=False

        #reading the btone jeffersonard
        for j in range(4):
            board.extend(infile.readline().rstrip())
        #checking the rows
        #print(board)
        for j in range(4):
            xcount,ocount=haswinner(board[j*4:(j+1)*4])
            winner=check(xcount,ocount)
            if(winner!=False):
                break
        #checking the columns
        if (winner==False):
            for j in range(4):
                xcount,ocount=haswinner([board[j],board[j+4],board[j+8],board[j+12]])
                winner=check(xcount,ocount)
                if(winner!=False):
                    break

        #checking the diagonals

        if(winner==False):
            xcount,ocount=haswinner([board[0],board[5],board[10],board[15]])
            winner=check(xcount,ocount)
            if(winner==False):
                xcount,ocount=haswinner([board[3],board[6],board[9],board[12]])
                winner=check(xcount,ocount)

        #checking if game complete
        if(winner==False):
            if('.' in board):
                winner='Game has not completed'
            else:
                winner='Draw'
        outfile.write('Case #'+str(i+1)+": "+winner+'\n')

        infile.readline() 

        
    infile.close()
    outfile.close()
        
            
            
        
