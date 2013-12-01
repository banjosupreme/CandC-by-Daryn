#include <iostream>
#include <string>

template<typename KeyType, typename ValueType>
struct KeyValuePair{
    KeyType key;
    ValueType value;
    
    KeyValuePair()
    {
    }
    KeyValuePair(KeyType inKey, ValueType inValue)
    {
        key=inKey;
        value=inValue;
    }
    bool operator < (KeyValuePair<KeyType,ValueType> comp)
    {
        return (key < comp.key);
    }
    bool operator > (KeyValuePair<KeyType,ValueType> comp)
    {
        return (key > comp.key);
    }
    bool operator == (KeyValuePair<KeyType,ValueType> comp)
    {
        return (key == comp.key);
    } 
    KeyValuePair& operator =(KeyValuePair<KeyType,ValueType> rhs)
    {
        key=rhs.key;
        value=rhs.value;
        return *this;
    }   

};


template <typename KeyType, typename ValueType>
std::ostream& operator<<(std::ostream &o, const KeyValuePair<KeyType, ValueType>& inpair)
{
    return o<<"Key: "<<inpair.key<<" Value: "<<inpair.value;
}    
namespace dir
{
    template<typename T>
    T max(T var1, T var2)
    {
        return var1 > var2 ? var1 : var2;
    }

    template<typename T>
    T min(T var1, T var2)
    {
        return var1 < var2 ? var2 : var1;
    }
}

int main()
{
    int i;
    std::string values[18]={"hello", "daryn","maths", "basketball", "sando", "football", "-0.23", "probability", "templates", "programming", "morehouse", "rpi", "pres", "3rd Trinidad", "boys rc", "stamford", "troy", "data"};
    KeyValuePair<std::string,int> tempPair, tempPair2; 
    
    for(i=0;i<17;i+=2)
    {
        tempPair.key=values[i];
        tempPair.value=i;
        tempPair2.key=values[i+1];
        tempPair2.value=i*2;
        std::cout<<tempPair<<" and "<<tempPair2<<". Max: "<<(dir::max(tempPair,tempPair2))<<"\n";
        
    }
    
    return 0;

}

