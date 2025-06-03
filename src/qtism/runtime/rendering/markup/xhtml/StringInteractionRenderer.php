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

/**
 * StringInteraction renderer. Rendered elements will be applied
 * the 'qti-stringInteraction' additional CSS class.
 *
 * The following data-X attributes will be rendered:
 *
 * * data-response-identifier = qti:interaction->responseIdentifier
 * * data-base = qti:stringInteraction->base
 * * data-string-identifier = qti:stringInteraction->stringIdentifier (only if set in QTI-XML counter-part).
 * * data-expected-length = qti:stringInteraction->expectedLength (only if set in QTI-XML counter-part).
 * * data-pattern-mask = qti:stringInteraction->patternMask (only if set in QTI-XML counter-part).
 * * data-placeholder-text = qti:stringInteraction->placeholderText (only if set in QTI-XML counter-part).
 */
abstract class StringInteractionRenderer extends InteractionRenderer
{
    /**
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        parent::appendAttributes($fragment, $component, $base);
        $this->additionalClass('qti-stringInteraction');

        $fragment->firstChild->setAttribute('data-base', (string)$component->getBase());

        if ($component->hasStringIdentifier() === true) {
            $fragment->firstChild->setAttribute('data-string-identifier', (string)$component->getStringIdentifier());
        }

        if ($component->hasExpectedLength() === true) {
            $fragment->firstChild->setAttribute('data-expected-length', (string)$component->getExpectedLength());
        }

        if ($component->hasPatternMask() === true) {
            $fragment->firstChild->setAttribute('data-pattern-mask', (string)$component->getPatternMask());
        }

        if ($component->hasPlaceholderText() === true) {
            $fragment->firstChild->setAttribute('data-placeholder-text', (string)$component->getPlaceholderText());
        }
    }
}
