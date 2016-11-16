Getting started
===============

After installing this bundle you can use the base e-mail template to create your own e-mail templates. The
base email template contains several blocks for you to override:

* `subject`: The subject of the e-mail
* `stylesheets`: One or more `<link>` elements to the sylesheets for your HTML e-mail.
* `body_text`: The body of the plain text version of your e-mail.
* `body_html`: The body of the HTML version of your e-mail.

Here is an example template:

```twig
{# src/AppBundle/Resources/views/Mail/hello.eml.twig #}
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

subject
-------

This is the subject of your e-mail.


stylesheets
-----------

One or more `<link>` elements to your stylesheets. Note that you should not use Symfony's `asset()` function here
because it generates a path relative to your URL, not your filesystem. You can refer to stylesheets in three ways:

1. Absolute paths, e.g. `/path/to/email.css`
2. Paths relative to the kernel root\_dir, such as `../web/css/email.css`
3. Kernel resources prefixed by `@`, such as `@AppBundle/Resources/public/css/email.css`

Note that the InkBundle does *not* ship with any CSS. You should get the
[Foundation for Emails](http://foundation.zurb.com/emails.html) CSS os SASS code yourself, in
whatever way you normally use front-end libraries (Bower, Gulp, Assetic, etcetera).


body\_text
----------

This is the body of the plain text version of your e-mail. Take care of your indenting here, as all spaces
and indents will show up in the final version of your e-mail.


body\_html
----------

This is the HTML version of your e-mail. You should structure your HTML according to the Foundation for Emails
components. You can use [Inky](http://foundation.zurb.com/emails/docs/inky.html) syntax to simplify your
HTML and deal with all the nested tables for you.


Create a message
================

After you have created your template, you can create a Swiftmailer message from it using the `prezent_ink.factory`
service. Example:

```php
$message = $this->get('prezent_ink.factory')->getMessage('@AppBundle:Mail:hello.eml.twig', [
    // Twig parameters
]);
```

This message contains the subject, the text version and the HTML version of your e-mail. The HTML version will
have been optimized for display across as many mail clients as possible (see
[Foundation Compatibility](http://foundation.zurb.com/emails/docs/compatibility.html)). It will:

1. Expand the Inky syntax to nested HTML tables.
2. Replace the `<link>` stylesheets with inlined `<style>` blocks.
3. Parse the style blocks and apply the CSS rules on all the HTML elements using `style=""` attributes.

All you have to do now is set `from` and `to` addresses and send the mail using the standard symfony mailer:

```php
$message
    ->setFrom('noreply@example.org')
    ->setTo('john.doe@example.org')
;

$this->get('mailer')->send($message);
```
