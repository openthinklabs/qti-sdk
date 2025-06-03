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

namespace qtism\data\storage\xml\marshalling;

use DOMElement;
use qtism\data\expressions\NumberResponded;
use qtism\data\QtiComponent;

/**
 * A marshalling/unmarshalling implementation for the QTI numberResponded expression.
 */
class NumberRespondedMarshaller extends ItemSubsetMarshaller
{
    /**
     * Marshall an NumberResponded object in its DOMElement equivalent.
     *
     * @param QtiComponent $component A NumberResponded object.
     * @return DOMElement The corresponding numberResponded QTI element.
     */
    protected function marshall(QtiComponent $component): DOMElement
    {
        return parent::marshall($component);
    }

    /**
     * Marshall an numberResponded QTI element in its NumberResponded object equivalent.
     *
     * @param DOMElement $element A DOMElement object.
     * @return NumberResponded The corresponding NumberResponded object.
     */
    protected function unmarshall(DOMElement $element): NumberResponded
    {
        $baseComponent = parent::unmarshall($element);
        $object = new NumberResponded();
        $object->setSectionIdentifier($baseComponent->getSectionIdentifier());
        $object->setIncludeCategories($baseComponent->getIncludeCategories());
        $object->setExcludeCategories($baseComponent->getExcludeCategories());

        return $object;
    }

    /**
     * @return string
     */
    public function getExpectedQtiClassName(): string
    {
        return 'numberResponded';
    }
}
