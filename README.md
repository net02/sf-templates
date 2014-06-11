Templates for sf2.4 projects

* symfony2.4 and web/grafica folder, plus .htaccess tweaks
* sonata admin bundle (via ORM) with FOS user and e-one customization
* i18n integration with sonata admin

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
To use gmail mailing service (during developement) use these parameters:
```yml
parameters:
    mailer_transport: gmail
    mailer_host: localhost
    mailer_user: your-gmail-user
    mailer_password: your-gmail-password
```

Sonata Admin configuration for i18n entities:
```yml
project.admin.entity:
    ...
    calls:
        - [addChild, ["@project.admin.entityi18n"]]    
            
project.admin.entityi18n:
    ...
    arguments:
        - ~
        - NameSpace\Of\EntityI18n
        - 'EoneSonataCustomizationBundle:TranslatingAdmin'
```
```xml
<service id="sonata.admin.entity" ...>
    ...
    <call method="addChild">
        <argument type="service" id="project.admin.entityi18n" />
    </call>
</service>
<service id="sonata.admin.entityi18n" ...>    
    <argument />
    <argument>NameSpace\Of\EntityI18n</argument>
    <argument>EoneSonataCustomizationBundle:TranslatingAdmin</argument>
</service>
```

# Features
* FOS features have been restricted to resetting only (see [routing.yml](app/config/routing.yml)) and use SonataAdmin theming.
* **Translatable** and **Translating** interfaces for i18n entities, which will be hooked up by the respective admin extensions in Sonata
* Use template **EoneSonataCustomizationBundle:CRUD:list_translatable.html.twig** to show translable fields in parent i18n entity List View
* Extend [TranslatingController](src/Eone/SonataCustomizationBundle/Controller/TranslatingController.php) to enable localization of i18n entities in frontend actions

# Todo
* [ ] Enable choosing between separate (as of now) and inline translating (inside the edit page) of i18n entities