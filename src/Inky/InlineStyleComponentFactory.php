<?php

namespace Prezent\InkBundle\Inky;

use Prezent\Inky\Component\ComponentFactory;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Replace linked stylesheets with inline style blocks
 *
 * @author Sander Marechal
 */
class InlineStyleComponentFactory implements ComponentFactory
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
    public function parse(\DOMNode $element)
    {
        if (!$element->hasAttribute('rel') || $element->getAttribute('rel') !== 'stylesheet') {
            return; // Not a stylesheet
        }

        if (!$element->hasAttribute('href')) {
            return; // No href attribute
        }

        $file = $element->getAttribute('href');

        if (isset($file[0]) && $file[0] === '@') {
            $file = $this->kernel->locateResource($file, null, true);
        } else {
            if (!file_exists($file)) {
                $file = $this->kernel->getRootDir() . '/' . ltrim($file, '/');

                if (!file_exists($file)) {
                    throw new \RuntimeException(
                        sprintf('Could not find stylesheet "%s".', $element->getAttribute('href'))
                    );
                }
            }
        }

        $style = $element->ownerDocument->createElement('style');
        $style->textContent = file_get_contents($file);

        $element->parentNode->replaceChild($style, $element);
    }
}
