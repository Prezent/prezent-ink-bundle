<?php

namespace Prezent\InkBundle\Tests\Fixture\AppBundle\Controller;

use Prezent\InkBundle\Mail\TwigFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Sander Marechal
 */
class EmailController extends Controller
{
    public function sendAction()
    {
        $message = $this->get(TwigFactory::class)->getMessage('@App/hello.eml.twig', [
            'user' => 'world',
        ]);

        $message
            ->setFrom('noreply@example.org')
            ->setTo('john.doe@example.org')
        ;

        $this->get('mailer')->send($message);

        return new Response('ok');
    }

    public function previewAction()
    {
        $message = $this->get(TwigFactory::class)->getHtmlPart('@App/hello.eml.twig', [
            'user' => 'world',
        ]);

        return new Response($message);
    }
}
