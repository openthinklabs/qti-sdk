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
use qtism\data\expressions\OutcomeMinimum;
use qtism\data\QtiComponent;

/**
 * A marshalling/unmarshalling implementation for the QTI OutcomeMinimum expression.
 */
class OutcomeMinimumMarshaller extends ItemSubsetMarshaller
{
    /**
     * Marshall an OutcomeMinimum object in its DOMElement equivalent.
     *
     * @param QtiComponent $component A OutcomeMinimum object.
     * @return DOMElement The corresponding outcomeMinimum QTI element.
     */
    protected function marshall(QtiComponent $component): DOMElement
    {
        $element = parent::marshall($component);
        $this->setDOMElementAttribute($element, 'outcomeIdentifier', $component->getOutcomeIdentifier());

        $weightIdentifier = $component->getWeightIdentifier();
        if (!empty($weightIdentifier)) {
            $this->setDOMElementAttribute($element, 'weightIdentifier', $weightIdentifier);
        }

        return $element;
    }

    /**
     * Marshall a outcomeMinimum QTI element in its OutcomeMinimum object equivalent.
     *
     * @param DOMElement $element A DOMElement object.
     * @return OutcomeMinimum The corresponding OutcomeMinimum object.
     * @throws UnmarshallingException
     */
    protected function unmarshall(DOMElement $element): OutcomeMinimum
    {
        $baseComponent = parent::unmarshall($element);

        if (($outcomeIdentifier = $this->getDOMElementAttributeAs($element, 'outcomeIdentifier')) !== null) {
            $object = new OutcomeMinimum($outcomeIdentifier);
            $object->setSectionIdentifier($baseComponent->getSectionIdentifier());
            $object->setIncludeCategories($baseComponent->getIncludeCategories());
            $object->setExcludeCategories($baseComponent->getExcludeCategories());

            if (($weightIdentifier = $this->getDOMElementAttributeAs($element, 'weightIdentifier')) !== null) {
                $object->setWeightIdentifier($weightIdentifier);
            }

            return $object;
        } else {
            $msg = "The mandatory attribute 'outcomeIdentifier' is missing from element '" . $element->localName . "'.";
            throw new UnmarshallingException($msg, $element);
        }
    }

    /**
     * @return string
     */
    public function getExpectedQtiClassName(): string
    {
        return 'outcomeMinimum';
    }
}
