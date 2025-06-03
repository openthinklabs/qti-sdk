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

/**
 * The atomicBlock QTI class.
 */
abstract class AtomicBlock extends BodyElement implements BlockStatic, FlowStatic
{
    use FlowTrait;

    /**
     * The collection of Inline components contained by the AtomicBlock.
     *
     * @var InlineCollection
     * @qtism-bean-property
     */
    private $content;

    /**
     * Create a new AtomicBlock object.
     *
     * @param string $id A QTI identifier.
     * @param string $class One or more class names separated by spaces.
     * @param string $lang An RFC3066 language.
     * @param string $label A label that does not exceed 256 characters.
     */
    public function __construct($id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($id, $class, $lang, $label);
        $this->setContent(new InlineCollection());
    }

    /**
     * Set the collection of Inline components composing the AtomicBlock.
     *
     * @param InlineCollection $content A collection of Inline objects.
     */
    public function setContent(InlineCollection $content): void
    {
        $this->content = $content;
    }

    /**
     * Get the collection of Inline components composing the AtomicBlock.
     *
     * @return InlineCollection
     */
    public function getContent(): InlineCollection
    {
        return $this->content;
    }

    /**
     * Get the collection of Inline components composing the AtomicBlock.
     *
     * @return InlineCollection A collection of Inline objects.
     */
    public function getComponents(): InlineCollection
    {
        return $this->getContent();
    }
}
