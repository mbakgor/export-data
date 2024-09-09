# export-data
This plugin helps to export important data from librenms.

# Installiation

You need to follow this commands in where librenms located with librenms user.

./lnms plugin:add mbakgor/export-data  
php artisan vendor:publish  

php artisan cache:clear  
php artisan config:clear  
php artisan route:clear  
php artisan view:clear  
