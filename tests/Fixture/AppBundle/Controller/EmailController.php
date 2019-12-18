<?php

namespace Prezent\InkBundle\Tests\Fixture\AppBundle\Controller;

use Prezent\InkBundle\Mail\TwigFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Sander Marechal
 */
class EmailController extends AbstractController
{
    public function sendAction(TwigFactory $factory, \Swift_Mailer $mailer)
    {
        $message = $factory->getMessage('@App/hello.eml.twig', [
            'user' => 'world',
        ]);

        $message
            ->setFrom('noreply@example.org')
            ->setTo('john.doe@example.org')
        ;

        $mailer->send($message);

        return new Response('ok');
    }

    public function previewAction(TwigFactory $factory)
    {
        $message = $factory->getHtmlPart('@App/hello.eml.twig', [
            'user' => 'world',
        ]);

        return new Response($message);
    }
}
