prezent/ink-bundle
==================

Easy responsive e-emails using Foundation and Inky.

Index
-----

1. Installation (see below)
2. [Getting started](getting-started.md)
3. [Custom Inky Components](inky-components.md)
4. [E-mail preview for development](preview.md)


Installation
------------

This bundle can be installed using Composer. Tell composer to install the bundle:

```bash
$ php composer.phar require prezent/ink-bundle
```

For Symfony 2.7 up to 3.2, tell Composer to get v0.1.5 of the Bundle.
```bash
$ php composer.phar require prezent/ink-bundle:0.1.5
```

Then, activate the bundle in your kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Prezent\InkBundle\PrezentInkBundle(),
    );
}
```


Configuration
-------------

Configuration is entirely optional. The only option is defining how many columns you want to use
in the Foundation for Emails grid. The default is 12 columns.

```yml
# app/config/config.yml

prezent_ink:
    grid_columns: 12
```
