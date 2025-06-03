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
use qtism\data\AssessmentSectionRef;
use qtism\data\QtiComponent;

/**
 * Marshalling/Unmarshalling implementation for assessmentSectionRef.
 */
class AssessmentSectionRefMarshaller extends SectionPartMarshaller
{
    /**
     * Marshall an AssessmentSectionRef object into a DOMElement object.
     *
     * @param QtiComponent $component An AssessmentSectionRef object.
     * @return DOMElement The according DOMElement object.
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    protected function marshall(QtiComponent $component): DOMElement
    {
        $element = parent::marshall($component);

        $this->setDOMElementAttribute($element, 'href', $component->getHref());

        return $element;
    }

    /**
     * Unmarshall a DOMElement object corresponding to a QTI assessmentSectionRef element.
     *
     * @param DOMElement $element A DOMElement object.
     * @return AssessmentSectionRef An AssessmentSectionRef object.
     * @throws UnmarshallingException If the mandatory attribute 'href' is missing.
     * @throws MarshallerNotFoundException
     */
    protected function unmarshall(DOMElement $element): AssessmentSectionRef
    {
        $baseComponent = parent::unmarshall($element);

        if (($href = $this->getDOMElementAttributeAs($element, 'href', 'string')) !== null) {
            $object = new AssessmentSectionRef($baseComponent->getIdentifier(), $href);
            $object->setRequired($baseComponent->isRequired());
            $object->setFixed($baseComponent->isFixed());
            $object->setPreConditions($baseComponent->getPreConditions());
            $object->setBranchRules($baseComponent->getBranchRules());
            $object->setItemSessionControl($baseComponent->getItemSessionControl());
            $object->setTimeLimits($baseComponent->getTimeLimits());

            return $object;
        } else {
            $msg = "Mandatory attribute 'href' is missing from element '" . $element->localName . "'.";
            throw new UnmarshallingException($msg, $element);
        }
    }

    /**
     * @return string
     */
    public function getExpectedQtiClassName(): string
    {
        return 'assessmentSectionRef';
    }
}
