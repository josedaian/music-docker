if [ -d /tmp/scripts ]; then
chmod +x /tmp/scripts/*.sh
fi 


if [ -f /tmp/scripts/init.sh ]; then
echo "Initializing PHP"
/tmp/scripts/init.sh
fi 

echo "Starting php-fpm"
php-fpm
echo "Exiting..."