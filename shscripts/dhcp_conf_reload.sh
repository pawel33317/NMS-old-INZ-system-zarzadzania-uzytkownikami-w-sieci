#!/bin/bash
source /var/www/shscripts/variable.sh

rm -f $DHCPD_CONF
wget -q $PHP_LOG_CREATE_FILE > /dev/null 
cp $DHCPD_LOG $DHCPD_CONF
$DHCPD_SERVER restart > /dev/null

echo "dhcp_conf_reload.sh: "`date` >> $LOG_LOG
rm load_dhcp_conf.ph*
