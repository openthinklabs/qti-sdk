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
 * The random operator takes a sub-expression with a multiple or ordered
 * container value and any base-type. The result is a single value randomly
 * selected from the container. The result has the same base-type as the
 * sub-expression but single cardinality. If the sub-expression is NULL
 * then the result is also NULL.
 */
class Random extends Operator
{
    /**
     * Create a new Random object.
     *
     * @param ExpressionCollection $expressions
     */
    public function __construct(ExpressionCollection $expressions)
    {
        parent::__construct($expressions, 1, 1, [OperatorCardinality::MULTIPLE, OperatorCardinality::ORDERED], [OperatorBaseType::ANY]);
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'random';
    }

    /**
     * Checks whether this expression is pure.
     *
     * @link https://en.wikipedia.org/wiki/Pure_function
     *
     * @return bool True if the expression is pure, false otherwise
     */
    public function isPure(): bool
    {
        return false;
    }
}
