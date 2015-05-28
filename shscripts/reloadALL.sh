#!/bin/bash
source /var/www/shscripts/variable.sh 

newusr=$FILE_TO_RELOAD"newusr.s";
stany=$FILE_TO_RELOAD"stany.s";
logi=$FILE_TO_RELOAD"logi.s";
firewall=$FILE_TO_RELOAD"firewall.s";
tc=$FILE_TO_RELOAD"tc.s";
dhcpdreload=$FILE_TO_RELOAD"dhcpreload.s";
dhcp=$FILE_TO_RELOAD"dhcp.s";
apache=$FILE_TO_RELOAD"apache.s";
mysql=$FILE_TO_RELOAD"mysql.s";
cron=$FILE_TO_RELOAD"cron.s";


#full path becaouse script is running via crontab
#echo "reloadALL: "`date` >> $LOG_LOG


for i in {1..29}
do
############  N E W U S E R (1) ##########
	CONTENT=`cat $newusr`
	if [ $CONTENT -ne 0 ]; then
		$SHSCRIPTS"dhcp_conf_reload.sh"
		chmod +x $PHP_CREATE_CONF"loadiptables.txt"
		$PHP_CREATE_CONF"loadiptables.txt"
		$DHCPD_SERVER restart > /dev/null
		echo "reloadALL: dhcpd reload "`date` >> $LOG_LOG
		echo "0" > $newusr
	fi

############  S T A N Y (1) ############
	CONTENT=`cat $stany`
	if [ $CONTENT -ne 0 ]; then
		#echo "reloadALL: service cron dhcpd httpd state reloaded "`date` >> $LOG_LOG
		cd $SERVICE_STATE
		state=`ps aux | pgrep cron`
			if [ state == "" ]; then
				echo "1" > $SERVICE_STATE"crond.d"
			else
				echo "0" > $SERVICE_STATE"crond.d"
			fi
		state=`ps aux | pgrep dhcpd`
			if [ state == "" ]; then
				echo "1" > $SERVICE_STATE"dhcpd.d"
			else
				echo "0" > $SERVICE_STATE"dhcpd.d"
			fi
		state=`ps aux | pgrep httpd`
			if [ state == "" ]; then
				echo "1" > $SERVICE_STATE"httpd.d"
			else
				echo "0" > $SERVICE_STATE"httpd.d"
			fi
		state=`iptables -L -n`
			if [ state == "" ]; then
				echo "1" > $SERVICE_STATE"iptables.d"
			else
				echo "0" > $SERVICE_STATE"iptables.d"
			fi
		state=`ps aux | pgrep mysqld`
			if [state == ""]; then
				echo "1" > $SERVICE_STATE"mysqld.d"
			else
				echo "0" > $SERVICE_STATE"mysqld.d"
			fi
		echo "0" > $stany
	fi
############  L O G I (1) ############
	CONTENT=`cat $logi`
	if [ $CONTENT -ne 0 ]; then
		#echo "reloadALL: logs reload "`date` >> $LOG_LOG
		cd $SYSTEM_LOGS
		lista=`ls | grep sh`
		for a in $lista
		do
			./$a;
		done
		echo "0" > $logi
	fi
############  F I R E W A L L (1) ############
	CONTENT=`cat $firewall`
	if [ $CONTENT -ne 0 ]; then
		$SHSCRIPTS"firewall"
		wget -q $PHP_IPTABLES_CREATE_FILE > /dev/null 
		chmod +x $PHP_CREATE_CONF"loadiptables.txt"
		$PHP_CREATE_CONF"loadiptables.txt"
		echo "0" > $firewall
		echo "reloadALL: firewall reload "`date` >> $LOG_LOG
 	fi
	
############  TTTTTTTTTTTTCCCCCCCCCCCC ############
	CONTENT=`cat $tc`
	if [ $CONTENT -ne 0 ]; then
		wget -q $PHP_TC_CREATE_FILE > /dev/null 
		chmod +x $PHP_CREATE_CONF"loadiptables.txt"
		TMP=`. $PHP_CREATE_CONF"load_tc.txt"`
		echo "0" > $tc
		echo "reloadALL: TC reload "`date` >> $LOG_LOG
		echo $TMP >> $LOG_LOG
 	fi
	
############  D H C P R E L O A D (1) ############
	CONTENT=`cat $dhcpdreload`
	if [ $CONTENT -ne 0 ]; then
		$SHSCRIPTS"dhcp_conf_reload.sh"
		echo "0" > $DHCPD_LEASES
		$DHCPD_SERVER restart > /dev/null
		echo "0" > $dhcpdreload
		echo "reloadALL: dhcpd reload "`date` >> $LOG_LOG
	fi
############  D H C P (1, 2, 3) ############
	CONTENT=`cat $dhcp`
	if [ $CONTENT -eq 1 ]; then
		echo "reloadALL: dhcpd turn on "`date` >> $LOG_LOG
		$DHCPD_SERVER start > /dev/null
		echo "0" > $dhcp
	fi
	if [ $CONTENT -eq 2 ]; then
		echo "reloadALL: dhcpd restart "`date` >> $LOG_LOG
		$DHCPD_SERVER restart > /dev/null
		echo "0" > $dhcp
	fi
	if [ $CONTENT -eq 3 ]; then
		echo "reloadALL: dhcpd turn off "`date` >> $LOG_LOG
		$DHCPD_SERVER stop > /dev/null
		echo "0" > $dhcp
	fi
############  A P A C H E (1, 2, 3) ############
	CONTENT=`cat $apache`
	if [ $CONTENT -eq 1 ]; then
		$APACHE start
		echo "0" > $apache
		echo "reloadALL: apache turn on "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 2 ]; then
		$APACHE restart
		echo "0" > $apache
		echo "reloadALL: apache turn restart "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 3 ]; then
		$APACHE stop
		echo "0" > $apache
		echo "reloadALL: apache turn off "`date` >> $LOG_LOG
	fi
############  M Y S Q L (1, 2, 3) ############
	CONTENT=`cat $mysql`
	if [ $CONTENT -eq 1 ]; then
		$MYSQLD start
		echo "0" > $mysql
		echo "reloadALL: mysql turn on "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 2 ]; then
		$MYSQLD restart
		echo "0" > $mysql
		echo "reloadALL: mysql restart "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 3 ]; then
		$MYSQLD stop
		echo "0" > $mysql
		echo "reloadALL: mysql turn off "`date` >> $LOG_LOG
	fi
############  C R O N (1, 2, 3) ############
	CONTENT=`cat $cron`
	if [ $CONTENT -eq 1 ]; then
		$CROND start
		echo "0" > $cron
		echo "reloadALL: cron turn on "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 2 ]; then
		$CROND restart
		echo "0" > $cron
		echo "reloadALL: cron restart "`date` >> $LOG_LOG
	fi
	if [ $CONTENT -eq 3 ]; then
		$CROND stop
		echo "0" > $cron
		echo "reloadALL: cron turn off "`date` >> $LOG_LOG
	fi
	sleep 2;
	echo $i
done
