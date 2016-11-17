<?php

namespace Prezent\InkBundle\Inky;

use Hampe\Inky\Component\ComponentFactoryInterface;
use Hampe\Inky\Inky;
use PHPHtmlParser\Dom\HtmlNode;
use PHPHtmlParser\Dom\TextNode;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Replace linked stylesheets with inline style blocks
 *
 * @author Sander Marechal
 */
class InlineStyleComponentFactory implements ComponentFactoryInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Constructor
     *
     * @param mixed $rootDir
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'link';
    }

    /**
     * {@inheritDoc}
     */
    public function parse(HtmlNode $element, Inky $inky)
    {
        $attributes = $element->getAttributes();

        if (!isset($attributes['rel']) || $attributes['rel'] !== 'stylesheet') {
            return; // Not a stylesheet
        }

        if (!isset($attributes['href'])) {
            return; // No href attribute
        }

        $file = $attributes['href'];

        if (isset($file[0]) && $file[0] === '@') {
            $file = $this->kernel->locateResource($file, null, true);
        } else {
            if (!file_exists($attributes['href'])) {
                $file = $this->kernel->getRootDir() . '/' . ltrim($file, '/');

                if (!file_exists($file)) {
                    throw new \RuntimeException(sprintf('Could not find stylesheet "%s".', $attributes['href']));
                }
            }
        }

        $style = new HtmlNode('style');
        $style->addChild(new TextNode(file_get_contents($file)));

        return $style;
    }
}
