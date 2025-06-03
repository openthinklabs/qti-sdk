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

namespace qtism\data\content\xhtml\tables;

use InvalidArgumentException;
use qtism\data\content\BodyElement;
use qtism\data\content\InlineCollection;

/**
 * The XHTML caption class.
 */
class Caption extends BodyElement
{
    /**
     * The components composing the caption.
     *
     * @var InlineCollection
     * @qtism-bean-property
     */
    private $content;

    /**
     * Create a new Caption object.
     *
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement;
     * @throws InvalidArgumentException If one of the arguments is invalid.
     */
    public function __construct($id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($id, $class, $lang, $label);
        $this->setContent(new InlineCollection());
    }

    /**
     * Get the components composing the caption.
     *
     * @return InlineCollection A collection of Inline objects.
     */
    public function getComponents(): InlineCollection
    {
        return $this->getContent();
    }

    /**
     * Set the components composing the caption.
     *
     * @param InlineCollection $content A collection of Inline objects.
     */
    public function setContent(InlineCollection $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the components composing the caption.
     *
     * @return InlineCollection
     */
    public function getContent(): InlineCollection
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'caption';
    }
}
