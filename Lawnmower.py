from collections import defaultdict


def Lawnmower(fname):

    infile=open(fname,'r')
    outfile=open(fname+'.out','wb')

    line=infile.readline().split()
    numcases=int(line[0])

    for i in range(numcases):
        N,M=[int(x) for x in infile.readline().split()]
        lawn=defaultdict(lambda:[])
        lawnvals=[]
        lineCount=0
        while lineCount<N:
            intline = [int(x) for x in infile.readline().split()]
            lawnvals.extend(intline)
            for j in range(len(intline)):
                lawn[intline[j]].append((lineCount,j))
            lineCount+=1

        values=[x for x in lawn]
        values.sort()
        doable='YES'
        for val in values:
            locations=lawn[val]
            for location in locations:
                row=lawnvals[location[0]*M:(location[0]+1)*M]
                col=[lawnvals[M*x+location[1]] for x in range(N)]
                if (max(row)>val and max(col)>val):
                    doable='NO'
                    break
            if (doable=='NO'):
                break
        outfile.write('Case #'+str(i+1)+': '+doable+'\n')

                
    infile.close()
    outfile.close()
