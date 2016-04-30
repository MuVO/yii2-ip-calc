<?php namespace muvo\yii\ip\address;

use yii\base\Object;

class IPv4 extends Object
{
    public $address;
    public $netmask;
    public $prefixlen;

    private $network;
    private $broadcast;

    public function init(){
        parent::init();
        $this->network = $this->address & $this->netmask;
        $this->broadcast = $this->address | $this->netmask ^ ip2long('255.255.255.255');
    }

    public static function create($string){
        $data = array();

        if(strpos($string,'/')){
            list($address,$prefix) = explode('/',$string,2);
            $data['address'] = ip2long($address);
            if(is_numeric($prefix)&&$prefix>=0&&$prefix<=32){
                $data['prefixlen'] = (int)$prefix;
                $data['netmask'] = ip2long('255.255.255.255')<<32-$prefix;
            }
            elseif(preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',$prefix)){
                $data['netmask'] = ip2long($prefix);
            }
        } else $data = [
            'address' => ip2long($string),
            'netmask' => ip2long('255.255.255.255'),
            'prefixlen' => 32
        ];

        return \Yii::createObject(self::className(),[$data]);
    }

    public function getIp(){
        return long2ip($this->address);
    }

    public function getNet(){
        return long2ip($this->network);
    }

    public function getMask(){
        return long2ip($this->netmask);
    }

    public function getBroadcast(){
        return long2ip($this->broadcast);
    }

    public function getFirst(){
        return $this->network!==$this->broadcast
            ? long2ip($this->network+1)
            : long2ip($this->address);
    }

    public function getLast(){
        return $this->network!==$this->broadcast
            ? long2ip($this->broadcast-1)
            : long2ip($this->address);
    }

    public function getCidr(){
        return sprintf('%s/%d',$this->getNet(),$this->prefixlen);
    }

    public function __toString(){
        return sprintf('%s/%d',$this->getIp(),$this->prefixlen);
    }
}
