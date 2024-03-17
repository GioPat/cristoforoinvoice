# CristoforoInvoice - Brutal INVOICE System
Invoicing software with some very very basic ERP/CRM features aimed at easing the invoicing management for indivduals or small businesses.

# QUICK(ONLY) start
Just download freaking PHP 8.1 or whatever and run

**Make sure to have the pg_sqlite extension active for your PHP runtime**

```php
php -S localhost:8080
```


## Settings
Invoice number format tokens
```
  - Use PHP formatted string by setting the format row in the settings table
```
# Core (missing) features

- NO backup
- NO config to be installed
- NO headaches
- NO CONFIG (Almost, you can set a remote DB connection string through the `DATABASE_CONN_STRING` env variable)


# Yet another Invoicing software??
All the others are forcing you to deploy on these remote services which I didn't have time and desire. So just RUN LOCALLY

Thanks to [invoicr](https://github.com/code-boxx/invoicr/tree/master)

ENJOY

```
                     `. ___
                    __,' __`.                _..----....____
        __...--.'``;.   ,.   ;``--..__     .'    ,-._    _.-'
  _..-''-------'   `'   `'   `'     O ``-''._   (,;') _,'
,'________________                          \`-._`-','
 `._              ```````````------...___   '-.._'-:
    ```--.._      ,.                     ````--...__\-.
            `.--. `-`                       ____    |  |`
              `. `.                       ,'`````.  ;  ;`
                `._`.        __________   `.      \'__/`
                   `-:._____/______/___/____`.     \  `
                               |       `._    `.    \
                               `._________`-.   `.   `.___
                                             SSt  `------'`
```