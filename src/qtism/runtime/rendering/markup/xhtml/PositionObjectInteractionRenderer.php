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
use qtism\data\QtiComponent;
use qtism\runtime\rendering\markup\AbstractMarkupRenderingEngine;

/**
 * PositionObjectInteraction renderer. Rendered components will be transformed as
 * 'div' elements with a 'qti-positionObjectInteraction' additional CSS class.
 *
 * The following data-X attributes will be rendered:
 *
 * * data-responseIdentifier = qti:interaction->responseIdentifier
 * * data-max-choices = qti:positionObjectInteraction->maxChoices
 * * data-min-choices = qti:positionObjectInteraction->minChoices (Rendered only if a value is present for the attribute)
 * * data-center-point = qti:positionObjectInteraction->centerPoint (Rendered only if a value is present for the attribute)
 */
class PositionObjectInteractionRenderer extends InteractionRenderer
{
    /**
     * Create a new PositionObjectInteractionRenderer object.
     *
     * @param AbstractMarkupRenderingEngine $renderingEngine
     */
    public function __construct(AbstractMarkupRenderingEngine $renderingEngine = null)
    {
        parent::__construct($renderingEngine);
        $this->transform('div');
    }

    /**
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        parent::appendAttributes($fragment, $component, $base);
        $this->additionalClass('qti-positionObjectInteraction');

        $fragment->firstChild->setAttribute('data-max-choices', (string)$component->getMaxChoices());

        if ($component->hasMinChoices() === true) {
            $fragment->firstChild->setAttribute('data-min-choices', (string)$component->getMinChoices());
        }

        if ($component->hasCenterPoint() === true) {
            $fragment->firstChild->setAttribute('data-center-point', $component->getCenterPoint()->getX() . ' ' . $component->getCenterPoint()->getY());
        }
    }
}
