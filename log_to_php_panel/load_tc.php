
#poprzednie iptables /var/www/shscripts/firewall 

#poprzednie iptables /var/www/shscripts/loadiptables.txt 

/var/www/shscripts/firewall

tc qdisc del dev eth1 root

tc -s qdisc ls dev eth1

tc qdisc del dev eth0 root

tc -s qdisc ls dev eth0

/sbin/tc qdisc add dev eth1 root handle 1: htb

/sbin/tc class add dev eth1 parent 1: classid 1:1 htb rate 100mbit

/sbin/tc qdisc add dev eth0 root handle 1: htb

/sbin/tc class add dev eth0 parent 1: classid 1:1 htb rate 1000mbit

echo NOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWY
	
/sbin/tc class add dev eth1 parent 1:1 classid 1:0x4e84 htb rate 500mbit  ceil 500mbit prio 1 
						
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle 0x4e84 fw flowid 1:0x4e84 
						
iptables -t mangle -I POSTROUTING -p all -d 10.0.0.4 -j MARK --set-mark 0x4e84  	
/sbin/tc class add dev eth1 parent 1:1 classid 1:0x2774 htb rate 1000mbit  ceil 1000mbit prio 1 
						
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle 0x2774 fw flowid 1:0x2774 
						
iptables -t mangle -I POSTROUTING -p tcp -d 10.0.0.4 --dport 80 -j MARK --set-mark 0x2774 	
/sbin/tc class add dev eth0 parent 1:1 classid 1:0x7594 htb rate 100mbit  ceil 100mbit prio 1 
						
/sbin/tc filter add dev eth0 parent 1:0 prio 1 protocol ip handle 0x7594 fw flowid 1:0x7594 
						
iptables -i eth1 -t mangle -I PREROUTING -p all -s 10.0.0.4 -j MARK --set-mark 0x7594 
echo STARY STARY STARY STARY STARY STARY STARY 
	
iptables -t mangle -I POSTROUTING -p all -d 10.0.0.5 -j MARK --set-mark 0x4e85  	
iptables -t mangle -I POSTROUTING -p tcp -d 10.0.0.5 --dport 80 -j MARK --set-mark 0x2775 	
iptables -i eth1 -t mangle -I PREROUTING -p all -s 10.0.0.5 -j MARK --set-mark 0x7595 
echo STARY STARY STARY STARY STARY STARY STARY 
	
iptables -t mangle -I POSTROUTING -p all -d 10.0.0.3 -j MARK --set-mark 0x4e85  	
iptables -t mangle -I POSTROUTING -p tcp -d 10.0.0.3 --dport 80 -j MARK --set-mark 0x2775 	
iptables -i eth1 -t mangle -I PREROUTING -p all -s 10.0.0.3 -j MARK --set-mark 0x7595 
echo NOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWY

echo NOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWY
	
/sbin/tc class add dev eth1 parent 1:1 classid 1:0x2776 htb rate 222mbit  ceil 222mbit prio 1 
						
/sbin/tc filter add dev eth1 parent 1:0 prio 1 protocol ip handle 0x2776 fw flowid 1:0x2776 
						
iptables -t mangle -I POSTROUTING -p tcp -d 10.0.0.6 --dport 80 -j MARK --set-mark 0x2776 	
/sbin/tc class add dev eth0 parent 1:1 classid 1:0x7596 htb rate 11mbit  ceil 11mbit prio 1 
						
/sbin/tc filter add dev eth0 parent 1:0 prio 1 protocol ip handle 0x7596 fw flowid 1:0x7596 
						
iptables -i eth1 -t mangle -I PREROUTING -p all -s 10.0.0.6 -j MARK --set-mark 0x7596 
echo NOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWYNOWY
   
iptables -t mangle -A POSTROUTING -p tcp -d 10.0.0.0/22 --dport 80 -j MARK --set-mark 10 
iptables -t mangle -A POSTROUTING -p all -d 10.0.0.0/22 -j MARK --set-mark 11 
iptables -i eth1 -t mangle -A PREROUTING -p all -s 10.0.0.0/22 -j MARK --set-mark 12 
