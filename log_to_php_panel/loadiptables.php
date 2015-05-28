#poprzednie iptables /var/www/shscripts/firewall 

iptables -I FORWARD -p tcp -s 10.0.0.3 --dport 11 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.3 --dport 11 -j DROP
iptables -I FORWARD -p tcp -s 10.0.0.3 --dport 22 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.3 --dport 22 -j DROP
iptables -I FORWARD -p tcp -s 10.0.0.4 --dport 11 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.4 --dport 11 -j DROP
iptables -I FORWARD -p tcp -s 10.0.0.4 --dport 22 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.4 --dport 22 -j DROP
iptables -I FORWARD -p tcp -s 10.0.0.5 --dport 11 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.5 --dport 11 -j DROP
iptables -I FORWARD -p tcp -s 10.0.0.5 --dport 22 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.5 --dport 22 -j DROP
iptables -A FORWARD -p all -s 10.0.0.2 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.2 --dport 88 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.2 --dport 88 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.2 --dport 22 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.2 --dport 22 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.2 --dport 100:200 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.2 --dport 100:200 -j ACCEPT
iptables -A FORWARD -p all -s 10.0.0.6 -j DROP
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 80 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 80 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 21 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 21 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 22 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 22 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 23 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 23 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 34 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 34 -j ACCEPT
iptables -I FORWARD -p udp -s 10.0.0.6 --dport 53 -j ACCEPT
iptables -I FORWARD -p tcp -s 10.0.0.6 --dport 53 -j ACCEPT
