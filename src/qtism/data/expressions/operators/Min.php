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

namespace qtism\data\expressions\operators;

use qtism\data\expressions\ExpressionCollection;

/**
 * From IMS QTI:
 *
 * The min operator takes 1 or more sub-expressions which all have numerical
 * base-types and may have single, multiple or ordered cardinality. The result
 * is a single float, or, if all sub-expressions are of integer type, a single
 * integer, equal in value to the smallest of the argument values, i.e. the
 * result is the argument closest to negative infinity. If the arguments have
 * the same value, the result is that same value. If any of the sub-expressions
 * is NULL, the result is NULL. If any of the sub-expressions is not a numerical
 * value, then the result is NULL.
 */
class Min extends Operator
{
    /**
     * Create a new Min object.
     *
     * @param ExpressionCollection $expressions
     */
    public function __construct(ExpressionCollection $expressions)
    {
        parent::__construct($expressions, 1, -1, [OperatorCardinality::SINGLE, OperatorCardinality::MULTIPLE, OperatorCardinality::ORDERED], [OperatorBaseType::INTEGER, OperatorBaseType::FLOAT]);
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'min';
    }
}
