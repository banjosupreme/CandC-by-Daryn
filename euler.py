import weblifted
from math import *
import time
from collections import defaultdict
import csv

def pentn(n):
    rval=(n*(3*n-1))/2
    return rval

def trin(n):
    rval=(n*(n+1))/2
    return rval

def hexn(n):
    rval=n*(2*n-1)
    return rval

def addtillbigger(array, current, thresh, genfunc):

    flag=0
    while flag==0:
        val=genfunc(current)
        array.append(val)
        if val>thresh:
            flag=1
        current+=1

    return array,current
    

def euler44():

#need to fix this: make start point of search shift up as well as work on stopping criterion

    pents=[1,5,12,22,35,51,70,92,117,145]

    i=11
    flag=0

    mindif=False

    while flag==0:
        size=len(pents)
        if size<i:
            pents.append(pentn(i))
            size+=1

        if mindif!=False:
            if pents[-1]-pents[-2]>mindif:
                flag=1
                break
        for j in range(i-1):
            minus=pents[i-1]-pents[j]
            if minus in pents[:i-1]:
                plus=pents[i-1]+pents[j]
                #print size, len(pents)
                if (plus) <= pents[size-1]:
                    #print "here"
                    if plus in pents[i:]:
                        print minus
                        if mindif==False:
                            mindif=minus
                        else:
                            if mindif>minus:
                                mindif=minus
                else:
                    #print "there"
                    pents=addtillbigger(pents, len(pents),plus,pentn)
                    size=len(pents)
                    if plus in pents[i:]:
                        print minus
        i+=1 

    return mindif


def euler45():

    tbase=286
    pbase=166
    hbase=144

    flag=0

    while flag==0:
        triangles=[]
        pentagons=[]
        val=hexn(hbase)
        pentagons,pbase=addtillbigger(pentagons, pbase, val, pentn)
        if val in pentagons:
            triangles,tbase=addtillbigger(triangles, tbase, val, trin)
            if val in triangles:
                break
        hbase+=1

    return val


def isfactor(a,b):

    rval=0

    if a%b==0:
        rval=1

    return rval

def notfactor(a,b):

    rval=1

    if a%b[0]==0:
        rval=0

    return rval

def square(inval):

    return inval*inval


def getnextprime(lastprime):

    flag=0

    if lastprime%6==5:
        check=lastprime+7

        if weblifted.rabin_miller(lastprime+2):
            flag=1
            rval=lastprime+2
    else:
        check=lastprime+5
        

    while flag==0:

        if weblifted.rabin_miller(check-1):
            rval=check-1
            flag=1
        elif weblifted.rabin_miller(check+1):
            rval=check+1
            flag=1

        check+=6

    return rval

def myfilter(infunc, inlist, fixedlist):

    outlist=[]

    for i in range(len(inlist)):
        if infunc(inlist[i],fixedlist):
            outlist.append(inlist[i])
    return outlist

def proc203(inlist):

    outlist=[]


    inlist=myfilter(notfactor, inlist, [4])
    inlist=myfilter(notfactor, inlist, [9])
    inlist=myfilter(notfactor, inlist, [25])

    for x in inlist:
        #print x
        if weblifted.rabin_miller(x):
            print "prime found"
            outlist.append(x)
            inlist.remove(x)

    

    lastprime=5

    while len(inlist)>0:
        print "inside the while"
        nextprime=getnextprime(lastprime)

        for x in inlist:
            if x<square(nextprime):
                outlist.append(x)
                inlist.remove(x)
        inlist=myfilter(notfactor, inlist, [square(nextprime)])
        lastprime=nextprime

        print outlist
        #print "***", nextprime
        #print inlist
            

    return outlist    

def euler203():

    inc=-1

    dlist=[1]
    ulist=[1]
    for i in range(49):
     
        if inc==-1:
            dlist.append(dlist[-1]*2)
            start=2
        else:
            start=1
        for j in xrange(start,len(dlist),1):
            dlist[-j]=dlist[-j]+dlist[-(j+1)]

        
        for j in range(1,len(dlist)):
            if dlist[j] not in ulist:
                ulist.append(dlist[j])
        #print setdlist
        #print dlist
        
        inc=inc*(-1)

    ulist=proc203(ulist)

    return ulist, sum(ulist)

def nextposs(inlist, minval, maxval):

    lasttest=[maxval]*len(inlist)

    
    for i in range(len(inlist)):
        if inlist[i]<maxval:
            inlist[i]+=1
            for j in range(i):
                inlist[j]=minval
            break
    return inlist

def waysfor(numdice, least, lookup):

    rval=0

    if lookup[numdice-1][least]!=-1:
        rval=lookup[numdice-1][least]

    else:
        if least<=0:
            rval=int(pow(4,numdice))
        elif least==numdice*4:
            rval=1
        elif least>numdice*4:
            rval=0
        elif numdice==1:
            rval=5-least
        else:
            for i in xrange(1,5,1):
                if least-i<=(numdice-1)*4:
                    rval+=waysfor(numdice-1,least-i, lookup)
                    #print waysfor(numdice-1,least-i, lookup), least, i, "***"
        lookup[numdice-1][least]=rval


    return rval

def euler205():

    t=time.time()
    Colin=[1]*6
    
    prob=0

    lookup=[]

    
    for i in xrange(9):
        lookup.append([-1]*37)

    while Colin!=[6]*6:
        tobeat=sum(Colin)+1
        tprob=waysfor(9,tobeat,lookup)/pow(4,9)
        #print waysfor(9,tobeat,lookup), tobeat, tprob
        tprob=tprob/pow(6,6)
        prob+=tprob
        #print tprob
        Colin=nextposs(Colin,1,6)

    print(time.time()-t)
    
    return prob

class pokerhand(object):

    cardvals={'2':2, '3':3, '4':4, '5':5, '6':6, '7':7, '8':8, '9':9, 'T':10, 'J':11, 'Q':12, 'K':13, 'A':14} 

    def __init__(self, inlist):
        self.vals=[]
        self.suits=[]
        self.mults = defaultdict(lambda: 0)
        for i in inlist:
            self.vals.append(pokerhand.cardvals[i[0]])
            self.suits.append(i[1])
            self.mults[pokerhand.cardvals[i[0]]]+=1
        
        self.calcscore()

    def isflush(self):
        check=self.suits[0]

        rval=True

        for i in range(1, len(self.vals),1):
            if self.suits[i]!=check:
                rval=False
                break

        return rval

    def isstraight(self):
        check=self.vals[0]
        rval=check

        for i in range(1, len(self.vals),1):
            if self.vals[i]!=check+i:
                rval=0
                break
        return rval

    def fourofkind(self):
        rval=0
        total=0
        for i in range(1,15,1):
            total+=self.mults[i]
            if self.mults[i]==4:
                rval=i
            if total>1:
                break
        return rval

    def threeofkind(self):
        rval=0
        total=0
        for i in range(1,15,1):
            total+=self.mults[i]
            if self.mults[i]==3:
                rval=i
            if total>2:
                break
        return rval

    def numpairs(self):
        rval=0
        paircount=0
        total=0
        for i in range(1,15,1):
            total+self.mults[i]
            if self.mults[i]==2:
                paircount+=1
            if total>3:
                break
        return paircount

    def pairval(self):
        rval=0
        for i in range(15,0,-1):
            if self.mults[i]==2:
                rval=i
                break
        return rval
                
     

    def calcscore(self):
        self.vals.sort()
        if self.isflush() and self.isstraight()>0:
            self.score=11-self.isstraight()
        elif self.fourofkind()>0:
            self.score=10+(14-self.fourofkind())
        elif self.threeofkind()>0 and self.numpairs()==1:
            self.score=23+(14-self.threeofkind())
        elif self.isflush():
            self.score=36
        elif self.isstraight()>0:
            self.score=37+(14-self.isstraight())
        elif self.threeofkind()>0:
            self.score=50+(14-self.threeofkind())
        elif self.numpairs()>0:
            if self.numpairs()==2:
                self.score=63
            else:
                self.score=64+(14-self.pairval())
        else:
            self.score=77+(14-self.vals[-1])

    def __gt__(self, other):
        rval=False
        if self.score<other.score:
            rval=True
        elif self.score==other.score:
            for i in xrange(1,len(self.vals)+1,1):
                if self.vals[-i]!=other.vals[-i]:
                    if self.vals[-i]>other.vals[-i]:
                        rval=True
                    break
        return rval

    def __lt__(self, other):
        rval=False
        if self.score>other.score:
            rval=True
        elif self.score==other.score:
            for i in xrange(1,len(self.vals)+1,1):
                if self.vals[-i]!=other.vals[-i]:
                    if self.vals[-i]<other.vals[-i]:
                        rval=True
                    break
        return rval

def lastdigofpower(number, power, digits):

    copy=number
    numberl=[]
    while number>0 and len(numberl)<digits:
        numberl.append(number%10)
        number=number/10
    while len(numberl)<digits:
        numberl.append(0)

    cpow=1
    
    while cpow<power:
        carry=0
        for i in xrange(len(numberl)):
            temp=copy*numberl[i]+carry
            numberl[i]=temp%10
            carry=temp/10
        cpow+=1

    return numberl

def euler97():

    step1=lastdigofpower(2,7830457,10)
    
    carry=0
    for i in xrange(10):
        temp=28433*step1[i]+carry
        step1[i]=temp%10
        carry=temp//10

    #need to reverse and add 1
    return step1

    
    
def euler54():

    f = open('poker.txt', 'r')
    wins=0
    losses=0
    for line in f:
        a=line.rstrip()
        a=a.rsplit(' ')
        x=pokerhand(a[0:5])
        y=pokerhand(a[5:])
        print a
        if x>y:
            wins+=1
            print "Player 1 wins"
        if x<y:
            losses+=1
            "Player 1 wins"
    return wins, losses


def numintsbetween(lower, upper):

    lower=ceil(lower)
    upper=floor(upper)+1

    rval=len(xrange(lower,upper,1))
    return rval
    
def euler63():

    n=1
    bigcount=0
    flag=True
    
    while flag==True:
        lower=ceil(pow(10,float(n-1)/n))
        count=0
        comp=pow(10,n)
        while pow(lower,n)<comp:
            count+=1
            lower+=1
        print count
        if count==0:
            flag=False
            break
        else:
            bigcount+=count
            n+=1

    return bigcount

def sumdigitssq(n):

    rval=0
    while n>0:
        temp=n%10
        rval+=(temp*temp)
        n=n//10
        
    return rval

def euler92():
    t=time.time()
    lookup=[0]*649
    lookup[1]=1
    lookup[89]=89

    count89=0
    
    for i in range(1,649,1):
        check=i
        temp=[]
        while lookup[check]==0:
            temp.append(i)
            check=sumdigitssq(check)
        for i in temp:
            lookup[i]=lookup[check]
        if lookup[check]==89:
            count89+=1
    for i in range(650,10000000,1):
        check=sumdigitssq(i)
        if lookup[check]==89:
            count89+=1


    return count89, time.time()-t

def gcd(pair):

    pair.sort()

    while pair[0]!=0:
        pair[1]=pair[1]%pair[0]
        pair.sort()
    return pair[1]

def kindaprime(n, primes):

    rval=True
    count=0
    
    while primes[count]<=sqrt(n):
        if n%(primes[count])==0:
            rval=False
            break
        count+=1

    return rval

def phi(n, primes):

    num=n
    denom=1

    count=0
    temp=n

    while count<len(primes) and primes[count]<=sqrt(n) and primes[count]<=temp:
        if temp%primes[count]==0:
            num*=(primes[count]-1)
            denom*=(primes[count])
            while temp%primes[count]==0:
                temp=temp/primes[count]
        count+=1
    if temp>1:
        num*=(temp-1)
        denom*=temp
    rval=num/denom
    #print rval,n
    return rval

def euler72b():

    t=time.time()
    primes=[2,3,5]

    last=5
    
    while last<=1000:
        last=getnextprime(last)
        primes.append(last)
        
    rval=0
    for i in xrange(2,1000001,1):
        rval+=phi(i,primes)

    return rval, time.time()-t
                
        


def euler59():

    inpt = csv.reader(open('cipher1.txt'), delimiter=',')
    line=[]
    for j in inpt: 
        for i in xrange(len(j)):
            line.append(int(j[i]))
        
    common=['and', 'the', 'it', 'is', 'for', 'and', 'there', 'what', 'where', 'how', 'why', 'you', 'with', 'of', 'to', 'that', 'you', 'he', 'was', 'were', 'are', 'as', 'with']

    f = open('BLAHBLAH', 'w')

    key=[0]*3

    for i in range(103,104,1):
        key[0]=i
        for j in range(111,112,1):
            key[1]=j
            for k in range(100,101,1):
                key[2]=k
                string=''
                place=0
                sm=0
                while place<len(line):
                    kin=place%3
                    string=string+chr(key[kin]^line[place])
                    sm+=key[kin]^line[place]
                    place+=1
                print string
            
                
            
                           
                    
    return sm

def insertinord(tpl, lst):

    comp=0
    while tpl[2]>lst[comp][2] and comp<len(lst):
        comp+=1
    #print comp

    if comp==len(lst):
        lst.insert(comp,tpl)
    elif lst[comp][2]>tpl[2]:
        lst.insert(comp,tpl)

def gcd(num1, num2):

    if num1>num2:
        itr=1
    else:
        itr=-1

    flag=False

    while flag==False:
        if itr==1:
            num1=num1%num2
            if num1==0:
                flag=True
                rval=num2
        else:
            num2=num2%num1
            if num2==0:
                flag=True
                rval=num1
        itr=itr*(-1)
    return rval

def reducefrac(tpl):

    while True:
        a=gcd(tpl[0],tpl[1])
        if a==1:
            break
        tpl=(tpl[0]/a, tpl[1]/a, tpl[2])

    return tpl

def euler71():

    v=float(299999)/700000
    comp=float(3)/7
    lst=[(3,7, 3.0/7)]
    for i in range(8,1000000,1):
        curr=int(ceil(v*i))
        print i
        while True:
            if (float(curr)/i) > comp:
                break
            val=float(curr)/i
            ttpl=reducefrac((curr,i,val))
            #print ttpl, curr,i,val
            insertinord(ttpl, lst)
            curr+=1
    return lst

def concat(a,b):

    place=0
    prod=1

    while b//prod>0:
        prod*=10
        place+=1
    rval=a*prod+b

    return rval


def euler72():

    t=time.time()
    primes=[2,3,5]

    last=5
    
    while last<=1000:
        last=getnextprime(last)
        primes.append(last)
        

    comp=2.5
    rval=0
    for i in xrange(1,1000001,1):
        count=0
        sieve=[]
        
        while primes[count]<sqrt(i):
            if i%(primes[count])==0:
                sieve.append(primes[count])
                factor=i/primes[count]
                if kindaprime(factor,primes):
                    sieve.append(factor)
            count+=1
        if primes[count]==sqrt(i):
            sieve.append(primes[count])
        
        numerator=1
        denominator=1
        #print sieve
        if len(sieve)==0:
            newval=i-1
        else:
            for x in sieve:
                numerator*=(x-1)
                denominator*=x
            newval=i*numerator/denominator
        rval+=newval

        if i==1000000:
            print "last one", newval, rval,sieve
        elif i==999999:
            print "2nd to last", newval, rval
        elif i==999998:
            print "3rd to last", newval, rval
        #print i,newval, sieve
        
        
        #print newval, comp
            
    #six seconds too long on run 
    return rval, time.time()-t

def flip(a,b):
    return b,a

def addc(c,num,denom):
    num=(c*denom)+num
    return num,denom
    
def euler65():

    lst=[1]*99
    i=1
    t=time.time()
    while (3*i-2)<99:
        lst[3*i -2]=2*i
        i+=1

    place=1

    denom=lst[-1]
    num=1

    place=2

    while place<=len(lst):
        num,denom=addc(lst[-place],num,denom)
        #print num,denom
        
        num,denom=flip(num,denom)
        #print num,denom
        place+=1


    num,denom=addc(2,num,denom)
    summ=0

    while num>0:
        summ+=num%10
        num=num//10
        

    return summ, time.time()-t

def rowcolsq(inval):

    col=inval%9
    row=inval//9

    for check in xrange(3):
        if row<(check+1)*3:
            for j in xrange(3):
                if col<(j+1)*3:
                    sq=(check*3)+j
                    break
            break
    return row, col, sq

def euler96():

    lookup=[]
    for i in xrange(81):
        lookup.append(rowcolsq(i))
    return lookup

    
    
