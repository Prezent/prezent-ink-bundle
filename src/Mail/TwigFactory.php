<?php

namespace Prezent\InkBundle\Mail;

use Pelago\Emogrifier;
use Prezent\Inky\Inky;
use Symfony\Component\Routing\RequestContext;

/**
 * Create e-mail messages from Twig templates
 *
 * @author Sander Marechal
 */
class TwigFactory
{
    /**
     * @var \Twig_Environment
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
     * Constructor
     */
    public function __construct(\Twig_Environment $twig, Inky $inky, Emogrifier $inliner)
    {
        $this->twig = $twig;
        $this->inky = $inky;
        $this->inliner = $inliner;
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
        $template = $this->twig->loadTemplate($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        $message = new \Swift_Message();
        $message
            ->setSubject($template->renderBlock('subject', $parameters))
            ->setBody($this->renderTextPart($template, $parameters), 'text/plain');

        if(defined('LIBXML_HTML_NOIMPLIED') && defined('LIBXML_HTML_NODEFDTD')) {
            $message
                ->addPart($this->renderHtmlPart($template, $parameters), 'text/html');
        }

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
        $template = $this->twig->loadTemplate($name);
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
        $template = $this->twig->loadTemplate($name);
        $parameters = $this->twig->mergeGlobals($parameters);

        return $this->renderHtmlPart($template, $parameters);
    }

    /**
     * Render the text part
     *
     * @param \Twig_Template $template
     * @param array $parameters
     * @return string
     */
    private function renderTextPart(\Twig_Template $template, array $parameters)
    {
        return $template->renderBlock('part_text', $parameters);
    }

    /**
     * Render the html part
     *
     * @param \Twig_Template $template
     * @param array $parameters
     * @return string
     */
    private function renderHtmlPart(\Twig_Template $template, array $parameters)
    {
        $html = $template->renderBlock('part_html', $parameters);
        $html = $this->inky->parse($html);

        $this->inliner->setHtml($html);
        $this->inliner->setCss('');

        $html = $this->inliner->emogrify();

        return $html;
    }
}
