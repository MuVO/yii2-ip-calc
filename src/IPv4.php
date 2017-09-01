<?php namespace muvo\yii\ip\address;

use yii\base\InvalidParamException;
use yii\base\Model;

class IPv4 extends Model
{
    /**
     * @var \Net_IPv4
     */
    private $ip;

    /**
     * @param $string
     * @param null $mask
     * @return self
     */
    public static function create($string, $mask = null)
    {
        $ip = new \Net_IPv4();

        if (is_long($string)) {
            $ip->long = $string;
        } elseif (is_string($string) && $ip->validateIP($string)) {
            $ip->ip = $string;
        } elseif (!$ip = $ip->parseAddress($string)) {
            throw new InvalidParamException("Can't parse given address");
        }

        if (is_numeric($mask)) {
            if ($mask < 0) {
                $ip->netmask = long2ip($mask);
            } elseif ($mask <= 32) {
                $ip->bitmask = $mask;
            }
        } elseif (is_string($mask) && $ip->validateNetmask($mask)) {
            $ip->netmask = $mask;
        }

        $ip->calculate();

        return \Yii::createObject(self::className(), [['ip' => $ip]]);
    }

    public function setIp(\Net_IPv4 $ip)
    {
        return $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip->ip;
    }

    /**
     * @return string
     */
    public function getNet()
    {
        return $this->ip->network;
    }

    /**
     * @return string
     */
    public function getMask()
    {
        return $this->ip->netmask;
    }

    /**
     * @return int
     */
    public function getPrefixlen()
    {
        return $this->ip->bitmask ?? 32;
    }

    /**
     * @return string
     */
    public function getBroadcast()
    {
        return $this->ip->broadcast;
    }

    /**
     * @return string
     */
    public function getFirst()
    {
        return $this->ip->network !== $this->ip->broadcast
            ? long2ip(ip2long($this->ip->network) + 1)
            : $this->ip->ip;
    }

    /**
     * @return string
     */
    public function getLast()
    {
        return $this->ip->network !== $this->ip->broadcast
            ? long2ip(ip2long($this->ip->broadcast) - 1)
            : $this->ip->ip;
    }

    /**
     * @return string
     */
    public function getCidr()
    {
        return sprintf('%s/%d', $this->ip->network, $this->ip->bitmask ?? 32);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s/%d', $this->ip->ip, $this->ip->bitmask ?? 32);
    }
}
