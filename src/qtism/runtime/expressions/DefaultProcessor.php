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

namespace qtism\runtime\expressions;

use qtism\data\expressions\DefaultVal;

/**
 * The DefaultProcessor class aims at processing Default QTI Data Model Expressions.
 *
 * From IMS QTI:
 *
 * This expression looks up the declaration of an itemVariable and returns the associated
 * defaultValue or NULL if no default value was declared. When used in outcomes processing
 * item identifier prefixing (see variable) may be used to obtain the default value from an
 * individual item.
 */
class DefaultProcessor extends ExpressionProcessor
{
    /**
     * Returns the defaultValue of the current Expression to be processed. If no Variable
     * with the given identifier is found, null is returned. If the Variable has no defaultValue,
     * null is returned.
     *
     * @return mixed A QTI Runtime compliant value.
     */
    #[\ReturnTypeWillChange]
    public function process()
    {
        $expr = $this->getExpression();
        $state = $this->getState();

        $var = $state->getVariable($expr->getIdentifier());

        return ($var === null) ? null : $var->getDefaultValue();
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return DefaultVal::class;
    }
}
