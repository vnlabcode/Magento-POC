# Magento 2 POC
### How to download
Use command line download
> git clone https://bot.aimegazone.com:90/magento/simple-poc/

### How to install
1. Create new database and import database sample in folder "**database**". You can use database management tool such as phpMyAdmin
2. Change site url:
After imported database please access to **core_config_data** table, In this table you can see two value: **web/unsecure/base_url** and **web/secure/base_url**. Go to table **core_config_data** -> Click search. Scroll down then you will see "**Value**" Field you enter text: **%http%** after that you click "Go". You will see the tables need to change url:
> http://magentopoc.com/ to http://YOUR-DOMAIN/

3. Open file "**app/etc/env.php**" in your server and change database connect.
4. Run commandl line. You need login ssh and cd to root magento and run commands line below:
> php bin/magento indexer:reindex

> php bin/magento setup:upgrade

> php bin/magento setup:static-content:deploy -f

> php bin/magento cache:flush

> chmod 777 -R pub var generated
