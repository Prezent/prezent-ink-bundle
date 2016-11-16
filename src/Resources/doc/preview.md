E-mail preview for development
==============================

When designing your e-mails it may be easier to display them in your browser rather
than sending out hundreds of test e-mails to yourself. To help with this, the message
factory has `getTextPart()` and `getHtmlPart()` methods. Example usage:


```php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller
use symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function previewHtmlAction()
    {
        $html = $this->get('prezent_ink.factory')->getHtmlPart('AppBundle:Mail:hello.eml.twig', [
            // Twig parameters
        ]);

        return new Response($html);
    }
}
```
