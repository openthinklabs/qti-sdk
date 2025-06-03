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
use qtism\common\utils\Format;
use qtism\data\QtiComponentCollection;

/**
 * From IMS QTI:
 *
 * The templateInline QTI class.
 */
class TemplateInline extends TemplateElement implements InlineStatic, FlowStatic
{
    /**
     * The base URI.
     *
     * @var string
     * @qtism-bean-property
     */
    private $xmlBase = '';

    /**
     * The content of the TemplateInline.
     *
     * @var InlineStaticCollection
     * @qtism-bean-property
     */
    private $content;

    /**
     * Create a new TemplateInline object.
     *
     * @param string $templateIdentifier The identifier of the associated template variable.
     * @param string $identifier The identifier of the templateInline.
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException If any of the argument is invalid.
     */
    public function __construct($templateIdentifier, $identifier, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($templateIdentifier, $identifier, $id, $class, $lang, $label);
        $this->setContent(new InlineStaticCollection());
    }

    /**
     * Set the content of the templateInline.
     *
     * @param InlineStaticCollection $content A collection of InlineStatic objects.
     */
    public function setContent(InlineStaticCollection $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the content of the templateInline.
     *
     * @return InlineStaticCollection A collection of InlineStatic objects.
     */
    public function getContent(): InlineStaticCollection
    {
        return $this->content;
    }

    /**
     * Set the base URI of the TemplateBlock.
     *
     * @param string $xmlBase A URI.
     * @throws InvalidArgumentException if $base is not a valid URI nor an empty string.
     */
    public function setXmlBase($xmlBase = ''): void
    {
        if (is_string($xmlBase) && (empty($xmlBase) || Format::isUri($xmlBase))) {
            $this->xmlBase = $xmlBase;
        } else {
            $msg = "The 'base' argument must be an empty string or a valid URI, '" . $xmlBase . "' given";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the base URI of the SimpleInline.
     *
     * @return string An empty string or a URI.
     */
    public function getXmlBase(): string
    {
        return $this->xmlBase;
    }

    /**
     * @return bool
     */
    public function hasXmlBase(): bool
    {
        return $this->getXmlBase() !== '';
    }

    /**
     * @return InlineStaticCollection|QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return $this->getContent();
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'templateInline';
    }
}
