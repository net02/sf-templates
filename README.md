Templates for sf2.4 projects

* symfony2.4 and web/grafica folder, plus .htaccess tweaks
* sonata admin bundle (via ORM) with FOS user and e-one customization

# Installation
Install via composer, then set up sf2 permissions
```sh
$ rm -rf app/cache/*
$ rm -rf app/logs/*

$ HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
```
Update database schema and generate backend admin
```sh
$ php app/console doctrine:schema:update --force
$ php app/console fos:user:create admin admin@example.com admin --super-admin
```

# Configuration reference
To use gmail mailing service (during developement) use these parameters
```yml
parameters:
    mailer_transport: gmail
    mailer_host: localhost
    mailer_user: your-gmail-user
    mailer_password: your-gmail-password
```
