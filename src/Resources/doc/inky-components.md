Custom Inky Components
======================

The Inky syntax is parsed using the [prezent/inky](https://github.com/prezent/prezent-inky) library. You can add
your own custom components to the parser by registering them as a service and tagging them with
the `prezent_ink.inky_component` tag.

```php
<?php

namespace AppBundle\Inky;

use Prezent\Inky\Component\ComponentFactory;

class TestComponentFactory implements ComponentFactory
{
    public function getName()
    {
        return 'test' // name of the html tag.
    }

    public function parse(\DOMNode $element)
    {
        // ...
    }
}
```

```xml
<!-- services.xml -->
<service id="AppBundle\Inky\TestComponentFactory">
    <tag name="prezent_ink.inky_component"/>
</service>
```
