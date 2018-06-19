E-mail preview for development
==============================

When designing your e-mails it may be easier to display them in your browser rather
than sending out hundreds of test e-mails to yourself. To help with this, the message
factory has `getTextPart()` and `getHtmlPart()` methods. Example usage:


```php
namespace AppBundle\Controller;

use Prezent\InkBundle\Mail\TwigFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function previewHtmlAction()
    {
        $html = $this->get(TwigFactory::class)->getHtmlPart('@App/mail/hello.eml.twig', [
            // Twig parameters
        ]);

        return new Response($html);
    }
}
```
