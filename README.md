prezent/ink-bundle
==================

[![Build Status](https://travis-ci.org/Prezent/prezent-ink-bundle.svg?branch=master)](https://travis-ci.org/Prezent/prezent-ink-bundle)

Easy responsive e-emails using Foundation and Inky

```php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller

class AppController extends Controller
{
    public function emailAction()
    {
        $message = $this->get('prezent_ink.factory')->getMessage('AppBundle:Mail:hello.eml.twig', [
            'user' => $this->getUser(),
        ]);

        $message
            ->setFrom('noreply@example.org')
            ->setTo('john.doe@example.org')
        ;

        $this->get('mailer')->send($message);
    }
}
```

```twig
{% extends 'PrezentInkBundle::base.eml.twig' %}

{% block stylesheets %}
    <link rel="stylesheet" href="@AppBundle/Resources/public/css/email.css" />
{% endblock %}

{% block subject %}Hello {{ user.username }}{% endblock %}

{% block body_text %}
Hello {{ user.username }},

Nice to meet you!
{% endblock %}

{% block body_html %}
<container>
    <h1>Hello {{ user.username }},</h1>
    <spacer size="16"></spacer>
    <callout>Nice to meet you!</callout>
</container>
{% endblock %}
```

The documentation can be found in [Resources/doc](src/Resources/doc/index.md)
