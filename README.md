Simple IP calculator
====================

# Quick start

Add model to your project
```
$ composer require muvo/yii2-ip-calculator "*"
```
or add this into section `require` of your `composer.json`:
```
...
    "require" : {
        ...
        "muvo/yii2-ip-calc" : "*"
    }
...
```
then run
```
$ composer update
```

# Basic usage

## Examples

```
$ipv4 = address\IPv4('192.168.0.3/24');

echo $ipv4->ip;         // 192.168.0.3      # Actual IP address
echo $ipv4->net;        // 192.168.0.0      # Address of current IP network
echo $ipv4->mask;       // 255.255.255.0    # Netmask in string format
echo $ipv4->prefixlen;  // 24               # Prefix length of current network
echo $ipv4->broadcast;  // 192.168.0.255    # Broadcast address of network
echo $ipv4->first;      // 192.168.0.1      # First **REALLY AVAILABLE** IP for using
echo $ipv4->last;       // 192.168.0.254    # Last **REALLY AVAILABLE** IP
echo $ipv4->cidr;       // 192.168.0.0/24   # Network address written in CIDR format
```

# Credits
Vladislav Muschinskikh <i@unixoid.su> © 2016
