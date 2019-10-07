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

For Symfony 2.7 up to 3.2, you should use the 0.1.x branch of this bundle:

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

You can optionally set the `public\_dir` option where this bundle will look for CSS files referenced in your e-mail messages.

```yaml
prezent_ink:
    public_dir: '%kernel.project_dir%/public'
```
