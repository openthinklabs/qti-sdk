<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * Copyright (c) 2013-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content;

use InvalidArgumentException;
use qtism\data\QtiComponentCollection;
use qtism\data\ViewCollection;

/**
 * From IMS QTI:
 *
 * Section rubric is presented to the candidate with each item contained (directly or indirectly)
 * by the section. As sections are nestable the rubric presented for each item is the
 * concatenation of the rubric blocks from the top-most section down to the item's
 * immediately enclosing section.
 */
class RubricBlock extends BodyElement implements BlockStatic, FlowStatic
{
    use FlowTrait;

    /**
     * The components composing the RubricBlock content.
     *
     * @var FlowStaticCollection
     * @qtism-bean-property
     */
    private $content;

    /**
     * The views in which the RubricBlock's content are to be shown.
     *
     * @var ViewCollection
     * @qtism-bean-property
     */
    private $views;

    /**
     * The purpose for which the rubric is intended to be used.
     *
     * @var string
     * @qtism-bean-property
     */
    private $use = '';

    /**
     * The stylesheets are used to format just the contents of the rubricBlock.
     *
     * @var StylesheetCollection
     * @qtism-bean-property
     */
    private $stylesheets;

    /**
     * Create a new RubricBlock object.
     *
     * @param ViewCollection $views A collection of values from the View enumeration.
     * @param string $id The identifier of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException If any of the arguments is invalid.
     */
    public function __construct(ViewCollection $views, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($id, $class, $lang, $label);
        $this->setViews($views);
        $this->setUse('');
        $this->setStylesheets(new StylesheetCollection());
        $this->setContent(new FlowStaticCollection());
    }

    /**
     * Get the views in which the rubric block's content are to be shown.
     *
     * @return ViewCollection A collection of values that belong to the View enumeration.
     */
    public function getViews(): ViewCollection
    {
        return $this->views;
    }

    /**
     * Set the views in which the rubric block's content are to be shown.
     *
     * @param ViewCollection $views A collection of values that belong to the View enumeration.
     * @throws InvalidArgumentException If $views is an empty collection.
     */
    public function setViews(ViewCollection $views): void
    {
        if (count($views) > 0) {
            $this->views = $views;
        } else {
            $msg = 'A RubricBlock object must contain at least one View.';
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get he purpose for which the rubric is intended to be used. If there is no
     * use for the Rubric Block, an empty string is returned.
     *
     * @return string The use or an empty string ('').
     */
    public function getUse(): string
    {
        return $this->use;
    }

    /**
     * Set he purpose for which the rubric is intended to be used. If there is no
     * use for the Rubric Block.
     *
     * @param string $use A use.
     * @throws InvalidArgumentException If $use is not a string.
     */
    public function setUse($use): void
    {
        if (is_string($use)) {
            $this->use = $use;
        } else {
            $msg = "The use argument must be a string, '" . gettype($use) . "' given";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the stylesheets to format the contents of the Rubric Block.
     *
     * @return StylesheetCollection A collection of stylesheet references.
     */
    public function getStylesheets(): StylesheetCollection
    {
        return $this->stylesheets;
    }

    /**
     * Set the stylesheets to format the contents of the Rubric Block.
     *
     * @param StylesheetCollection $stylesheets A collection of stylesheet references.
     */
    public function setStylesheets(StylesheetCollection $stylesheets): void
    {
        $this->stylesheets = $stylesheets;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'rubricBlock';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $components = $this->getContent();

        return new QtiComponentCollection(array_merge($components->getArrayCopy(), $this->getStylesheets()->getArrayCopy()));
    }

    /**
     * Set the collection of objects composing the RubricBlock.
     *
     * @param FlowStaticCollection $content A collection of FlowStatic objects.
     */
    public function setContent(FlowStaticCollection $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the content of objects composing the RubricBlock.
     *
     * @return FlowStaticCollection
     */
    public function getContent(): FlowStaticCollection
    {
        return $this->content;
    }
}
