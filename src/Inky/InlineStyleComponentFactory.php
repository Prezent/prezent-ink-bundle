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
     * @var ?string
     */
    private $publicDir;

    /**
     * Constructor
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
     * Get publicDir
     *
     * @return ?string
     */
    public function getPublicDir()
    {
        return $this->publicDir;
    }

    /**
     * Set publicDir
     *
     * @param ?string $publicDir
     * @return self
     */
    public function setPublicDir($publicDir)
    {
        $this->publicDir = $publicDir;
        return $this;
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

        $file = $this->findFile($element->getAttribute('href'));

        $style = $element->ownerDocument->createElement('style');
        $style->textContent = file_get_contents($file);

        $element->parentNode->replaceChild($style, $element);
    }

    /**
     * Find the location of a stylesheet
     *
     * First check for an @bundle shortcut, then check the configured publicDir, absolute path
     * and finally path relative to the kernel root dir.
     *
     * @return string
     */
    private function findFile($file)
    {
        if (isset($file[0]) && $file[0] === '@') {
            return $this->kernel->locateResource($file, null, true);
        }

        if ($this->publicDir) {
            $path = rtrim($this->publicDir, '/') . '/' . ltrim($file, '/');

            if (file_exists($path)) {
                return $path;
            }
        }

        if (file_exists($file)) {
            return $file;
        }

        $path = $this->kernel->getRootDir() . '/' . ltrim($file, '/');

        if (file_exists($path)) {
            return $path;
        }

        throw new \RuntimeException(sprintf('Could not find stylesheet "%s".', $file));
    }
}
