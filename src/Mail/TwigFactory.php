<?php

namespace Prezent\InkBundle\Mail;

use Pelago\Emogrifier;
use Prezent\Inky\Inky;
use Symfony\Component\Routing\RequestContext;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;
use Twig\Environment;
use Twig\TemplateWrapper;

/**
 * Create e-mail messages from Twig templates
 *
 * @author Sander Marechal
 */
class TwigFactory
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Inky
     */
    private $inky;

    /**
     * @var CssToInlineStyles
     */
    private $inliner;

    /**
     * @var EntrypointLookupCollectionInterface
     */
    private $entrypointLookupCollection;

    /**
     * Constructor
     */
    public function __construct(
        Environment $twig,
        Inky $inky,
        Emogrifier $inliner,
        EntrypointLookupCollectionInterface $entrypointLookupCollection = null
    ) {
        $this->twig = $twig;
        $this->inky = $inky;
        $this->inliner = $inliner;
        $this->entrypointLookupCollection = $entrypointLookupCollection;
    }

    /**
     * Get a Swift message
     *
     * @param mixed $name
     * @param array $parameters
     * @return \Swift_Message
     */
    public function getMessage($name, array $parameters = [])
    {
        $template = $this->twig->load($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        $message = new \Swift_Message();
        $message
            ->setSubject($template->renderBlock('subject', $parameters))
            ->setBody($this->renderTextPart($template, $parameters), 'text/plain')
            ->addPart($this->renderHtmlPart($template, $parameters), 'text/html');

        return $message;
    }

    /**
     * Get the text part of a message
     *
     * @param mixed $name
     * @param array $parameters
     * @return string
     */
    public function getTextPart($name, array $parameters = [])
    {
        $template = $this->twig->load($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        return $this->renderTextPart($template, $parameters);
    }

    /**
     * Get the html part of a message
     *
     * @param mixed $name
     * @param array $parameters
     * @return string
     */
    public function getHtmlPart($name, array $parameters = [])
    {
        $template = $this->twig->load($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        return $this->renderHtmlPart($template, $parameters);
    }

    /**
     * Render the text part
     *
     * @param TemplateWrapper $template
     * @param array $parameters
     * @return string
     */
    private function renderTextPart(TemplateWrapper $template, array $parameters)
    {
        $text = $template->renderBlock('part_text', $parameters);
        $this->reset();

        return $text;
    }

    /**
     * Render the html part
     *
     * @param TemplateWrapper $template
     * @param array $parameters
     * @return string
     */
    private function renderHtmlPart(TemplateWrapper $template, array $parameters)
    {
        $html = $template->renderBlock('part_html', $parameters);
        $html = $this->inky->parse($html);

        $this->inliner->setHtml($html);
        $this->inliner->setCss('');

        $html = $this->inliner->emogrify();

        $this->reset();

        return $html;
    }

    /**
     * Reset the twig renderer
     *
     * @return void
     */
    private function reset()
    {
        if ($this->entrypointLookupCollection) {
            $this->entrypointLookupCollection->getEntrypointLookup()->reset();
        }
    }
}
