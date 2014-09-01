Templates for sf2.4 projects

* symfony2.4 and web/grafica folder, plus .htaccess tweaks
* sonata admin bundle (via ORM) with FOS user and e-one customization
* i18n integration with sonata admin
* Doctrine ORM knp-menu integration

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
Load demo fixtures (menu)
```sh
$ php app/console doctrine:fixtures:load --append --fixtures=src/Acme/DemoBundle/DataFixtures/ORM
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

Menu [configuration](src/Eone/MenuBundle/Resources/config/services.yml#L7-15) with Doctrine ORM integration:
```yml
eone.menu.menu.main:
    class: Knp\Menu\MenuItem
    factory_service: eone.menu.builder
    factory_method: createMenuByAlias
    arguments: ["main", "@request"]
    scope: request
    tags:
        - { name: knp_menu.menu, alias: main }
        - { name: eone_menu.menu, alias: main }
```
```xml
<service id="eone.menu.menu.main"
         class="Knp\Menu\MenuItem"
         scope="request"
         factory-service="eone.menu.builder"
         factory-method="createMenuByAlias">
    <tag name="knp_menu.menu" alias="main"/>
    <tag name="eone_menu.menu" alias="main"/>
    <argument>main</argument>
    <argument type="service" id="request" />
</service>
```

Customize Menu rendering with [KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle/blob/1.1.x/Resources/doc/custom_renderer.md)

# Features
* FOS features have been restricted to resetting only (see [routing.yml](app/config/routing.yml)) and use SonataAdmin theming.
* **Translatable** and **Translating** interfaces for i18n entities, which will be hooked up by the respective admin extensions in Sonata
* Use template **EoneSonataCustomizationBundle:CRUD:list_translatable.html.twig** to show translatable fields in parent i18n entity List View (see [example](src/Acme/DemoBundle/Admin/NewsAdmin.php#L23))
* Extend [TranslatingController](src/Eone/SonataCustomizationBundle/Controller/TranslatingController.php) to enable localization of i18n entities in frontend actions

# Todo
* [ ] Demo landing page
* [ ] *trans* the i18n Admin Extensions
* [ ] *trans* Menu Admin
* [ ] Update to knp-menu ~2.0
* [ ] Enable choosing between separate (as of now) and inline translating (inside the edit page) of i18n entities
* [ ] Better current-menu-item matching for absolute routes & hash uri
* [ ] Better key-value form templating
