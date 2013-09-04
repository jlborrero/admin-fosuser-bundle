Installation
============

Prerequisites
-------------
Required
          "jlbs/admin-bundle": "dev-master",
          "friendsofsymfony/user-bundle": "2.0.*@dev"

Installation
------------

1. Add this bundle to your project in composer.json:

    1.2. Plain Admin
    Symfony 2.3 uses composer (http://www.getcomposer.org) to organize dependencies:

    ```json
    {
        "require": {
            "jlbs/admin-fosuser-bundle": "dev-master"
        }
    }

2. Add this bundle to your app/AppKernel.php:

    ``` php
    // application/ApplicationKernel.php
    public function registerBundles()
    {
        return array(
            // ...
                  new Jlbs\AdminBundle\JlbsAdminFOSUserBundle(),
            // ...
        );
    }

3. Register the routing in `app/config/routing.yml`:

``` yml
# app/config/routing.yml

fos_js_routing:
    resource: "@JlbsAdminFOSUserBundle/Resources/config/routing/routing.yml"
```

Publish assets:

    $ php app/console assets:install --symlink web