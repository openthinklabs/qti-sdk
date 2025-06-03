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
use qtism\data\content\xhtml\tables\TableCellScope;
use qtism\data\QtiComponent;

/**
 * TableCell renderer.
 */
class TableCellRenderer extends BodyElementRenderer
{
    /**
     * @param DOMDocumentFragment $fragment
     * @param QtiComponent $component
     * @param string $base
     */
    protected function appendAttributes(DOMDocumentFragment $fragment, QtiComponent $component, $base = ''): void
    {
        parent::appendAttributes($fragment, $component, $base);

        if ($component->hasHeaders() === true) {
            $fragment->firstChild->setAttribute('headers', implode("\x20", $component->getHeaders()->getArrayCopy()));
        }

        if ($component->hasScope() === true) {
            $fragment->firstChild->setAttribute(
                'scope',
                (string)TableCellScope::getNameByConstant($component->getScope())
            );
        }

        if ($component->hasAbbr() === true) {
            $fragment->firstChild->setAttribute('abbr', (string)$component->getAbbr());
        }

        if ($component->hasAxis() === true) {
            $fragment->firstChild->setAttribute('axis', (string)$component->getAxis());
        }

        if ($component->hasRowspan() === true) {
            $fragment->firstChild->setAttribute('rowspan', (string)$component->getRowspan());
        }

        if ($component->hasColspan() === true) {
            $fragment->firstChild->setAttribute('colspan', (string)$component->getColspan());
        }
    }
}
