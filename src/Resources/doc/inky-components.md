Custom Inky Components
======================

The Inky syntax is parsed using the [hampe/inky](https://github.com/thampe/inky) library. You can add
your own [custom components](https://github.com/thampe/inky#add-your-own-component-factory) to the parser
by registering them as a service and tagging them with the `prezent_ink.inky_component` tag.

```php
<?php

namespace AppBundle\Inky;

use Hampe\Inky\Component\ComponentFactoryInterface;
use Hampe\Inky\Inky;
use PHPHtmlParser\Dom\HtmlNode;

class TestComponentFactory implements ComponentFactoryInterface
{
    public function getName()
    {
        return 'test' // name of the html tag.
    }

    public function parse(HtmlNode $element, Inky $inkyInstance)
    {
        // ...
    }
}
```

```xml
<!-- services.xml -->
<service id="app.inky.test_component" class="appBundle\Inky\TestComponentFactory">
    <tag name="prezent_ink.inky_component"/>
</service>
```
