<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\runtime\rendering\markup\xhtml;

use DOMDocumentFragment;
use qtism\data\content\Stylesheet;
use qtism\data\QtiComponent;
use qtism\runtime\rendering\markup\AbstractMarkupRenderer;
use qtism\runtime\rendering\markup\AbstractMarkupRenderingEngine;
use qtism\runtime\rendering\RenderingException;

/**
 * Base class of all XHTML renderers.
 */
abstract class AbstractXhtmlRenderer extends AbstractMarkupRenderer
{
    /**
     * A tag name to be used instead of the
     * QTI class name for rendering.
     *
     * @var string
     */
    private $replacementTagName = '';

    /**
     * A set of additional QTI specific classes to be added
     * to the rendered element.
     *
     * @var array
     */
    private $additionalClasses = [];

    /**
     * A set of additional user specific classes to be added to the
     * rendered element.
     *
     * @var array
     */
    private $additionalUserClasses = [];

    /**
     * Create a new AbstractXhtmlRenderer object.
     *
     * @param AbstractMarkupRenderingEngine $renderingEngine
     */
    public function __construct(?AbstractMarkupRenderingEngine $renderingEngine = null)
    {
        parent::__construct($renderingEngine);
    }

    /**
     * Render a QtiComponent into a DOMDocumentFragment that will be registered
     * in the current rendering context.
     *
     * @param QtiComponent $component
     * @param string $base
     * @return DOMDocumentFragment A DOMDocumentFragment object containing the rendered $component into another constitution with its children rendering appended.
     */
    public function render($component, $base = ''): DOMDocumentFragment
    {
        $renderingEngine = $this->getRenderingEngine();
        $doc = $renderingEngine->getDocument();
        $fragment = $doc->createDocumentFragment();

        // Apply rendering...
        $this->renderingImplementation($fragment, $component, $base);

        // Specific case for stylesheet elements...
        if ($component instanceof Stylesheet && $renderingEngine->getStylesheetPolicy() === AbstractMarkupRenderingEngine::STYLESHEET_SEPARATE) {
            // Stylesheet must be rendered separately.
            $stylesheets = $renderingEngine->getStylesheets();
            $stylesheets->appendChild($fragment);
        } else {
            // Stylesheet must be rendered at the same place.
            $renderingEngine->storeRendering($component, $fragment);
        }

        return $fragment;
    }

    /**
     * A default rendering implementation focusing on a given $fragment and $component.
     *
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base An optional base path to be used for rendering.
     */
    protected function renderingImplementation(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        $this->appendElement($fragment, $component, $base);
        $this->appendChildren($fragment, $component, $base);
        $this->appendAttributes($fragment, $component, $base);

        if ($this->getRenderingEngine()->getCssClassPolicy() === AbstractMarkupRenderingEngine::CSSCLASS_ABSTRACT) {
            // The whole hierarchy of qti- CSS classes must be rendered.
            if ($this->hasAdditionalClasses() === true) {
                $classes = implode("\x20", $this->getAdditionalClasses());
                $currentClasses = $fragment->firstChild->getAttribute('class');
                $glue = ($currentClasses !== '') ? "\x20" : '';
                $fragment->firstChild->setAttribute('class', $currentClasses . $glue . $classes);
            }
        } elseif ($this->hasAdditionalClasses() === true) {
            // Only the last added qti- CSS class must be rendered.
            $classes = $this->getAdditionalClasses();
            $class = array_pop($classes);
            $fragment->firstChild->setAttribute('class', $class);
        }

        // Add user specific CSS classes e.g. 'my-class' to rendering.
        if ($this->hasAdditionalUserClasses() === true) {
            $classes = implode("\x20", $this->getAdditionalUserClasses());
            $currentClasses = $fragment->firstChild->getAttribute('class');
            $glue = ($currentClasses !== '') ? "\x20" : '';
            $fragment->firstChild->setAttribute('class', $currentClasses . $glue . $classes);
        }

        // Reset additional user classes for next rendering with this implementation.
        $this->setAdditionalUserClasses([]);
    }

    /**
     * Append a new DOMElement to the currently rendered $fragment which is suitable
     * to $component.
     *
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendElement(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        $tagName = ($this->hasReplacementTagName() === true) ? $this->getReplacementTagName() : $component->getQtiClassName();
        $fragment->appendChild($this->getRenderingEngine()->getDocument()->createElement($tagName));
    }

    /**
     * Append the children renderings of $components to the currently rendered $fragment.
     *
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendChildren(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        foreach ($this->getRenderingEngine()->getChildrenRenderings($component) as $childrenRendering) {
            $fragment->firstChild->appendChild($childrenRendering);
        }
    }

    /**
     * Append the necessary attributes of $component to the currently rendered $fragment.
     *
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        $this->handleXmlBase($component, $fragment->firstChild);
    }

    /**
     * Set the replacement tag name.
     *
     * @param string $replacementTagName
     */
    protected function setReplacementTagName($replacementTagName): void
    {
        $this->replacementTagName = $replacementTagName;
    }

    /**
     * Get the replacement tag name.
     *
     * @return string
     */
    protected function getReplacementTagName(): string
    {
        return $this->replacementTagName;
    }

    /**
     * Whether a replacement tag name is defined.
     *
     * @return bool
     */
    protected function hasReplacementTagName(): bool
    {
        return $this->getReplacementTagName() !== '';
    }

    /**
     * The renderer will by default render the QTI Component into its markup equivalent, using
     * the QTI class name returned by the component as the rendered element name.
     *
     * Calling this method will make the renderer use $tagName as the element node name to be
     * used at rendering time.
     *
     * @param string $tagName A tagname e.g. 'div'.
     */
    public function transform($tagName): void
    {
        $this->setReplacementTagName($tagName);
    }

    /**
     * Set the array of additional CSS classes.
     *
     * @param array $additionalClasses
     */
    protected function setAdditionalClasses(array $additionalClasses): void
    {
        $this->additionalClasses = $additionalClasses;
    }

    /**
     * Get the array of additional CSS classes.
     *
     * @return array
     */
    protected function getAdditionalClasses(): array
    {
        return $this->additionalClasses;
    }

    /**
     * Whether additional CSS classes are defined for rendering.
     *
     * @return bool
     */
    protected function hasAdditionalClasses(): bool
    {
        return count($this->getAdditionalClasses()) > 0;
    }

    /**
     * Add an additional CSS class to be rendered.
     *
     * @param string $additionalClass A CSS class.
     */
    public function additionalClass($additionalClass): void
    {
        $additionalClasses = $this->getAdditionalClasses();

        if (($key = array_search($additionalClass, $additionalClasses)) !== false) {
            unset($additionalClasses[$key]);
        }

        $additionalClasses[] = $additionalClass;
        $this->setAdditionalClasses($additionalClasses);
    }

    /**
     * Set the array of additional user CSS classes.
     *
     * @param array $additionalUserClasses
     */
    public function setAdditionalUserClasses(array $additionalUserClasses): void
    {
        $this->additionalUserClasses = $additionalUserClasses;
    }

    /**
     * Get the array of additional user CSS classes.
     *
     * @return array
     */
    public function getAdditionalUserClasses(): array
    {
        return $this->additionalUserClasses;
    }

    /**
     * Whether additional user CSS classes are defined for rendering.
     *
     * @return bool
     */
    public function hasAdditionalUserClasses(): bool
    {
        return count($this->getAdditionalUserClasses()) > 0;
    }

    /**
     * Add an additional user CSS class to be rendered.
     *
     * @param string $additionalUserClass
     */
    public function additionalUserClass($additionalUserClass): void
    {
        $additionalClasses = $this->getAdditionalUserClasses();

        if (($key = array_search($additionalUserClass, $additionalClasses)) !== false) {
            unset($additionalClasses[$key]);
        }

        $additionalClasses[] = $additionalUserClass;
        $this->setAdditionalUserClasses($additionalClasses);
    }
}
