<?php

namespace Prezent\InkBundle\Mail;

use Pelago\Emogrifier\CssInliner;
use Prezent\Inky\Inky;
use Symfony\Component\Mime\Email;
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
     * @var EntrypointLookupCollectionInterface
     */
    private $entrypointLookupCollection;

    /**
     * Constructor
     */
    public function __construct(
        Environment $twig,
        Inky $inky,
        EntrypointLookupCollectionInterface $entrypointLookupCollection = null
    ) {
        $this->twig = $twig;
        $this->inky = $inky;
        $this->entrypointLookupCollection = $entrypointLookupCollection;
    }

    /**
     * Get a mail message
     *
     * @param mixed $name
     * @param array $parameters
     */
    public function getMessage($name, array $parameters = []): Email
    {
        $template = $this->twig->load($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        $message = (new Email())
            ->subject($template->renderBlock('subject', $parameters))
            ->text($this->renderTextPart($template, $parameters), 'text/plain')
            ->html($this->renderHtmlPart($template, $parameters), 'text/html');

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
        $html = CssInliner::fromHtml($html)->inlineCss()->render();

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
