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

namespace qtism\runtime\expressions\operators;

use qtism\common\datatypes\QtiInteger;
use qtism\data\expressions\operators\IntegerDivide;

/**
 * The IntegerDivideProcessor class aims at processing IntegerDivide operators.
 *
 * From IMS QTI:
 *
 * The integer divide operator takes 2 sub-expressions which both have single
 * cardinality and base-type integer. The result is the single integer that
 * corresponds to the first expression (x) divided by the second expression (y)
 * rounded down to the greatest integer (i) such that i<=(x/y). If y is 0, or if
 * either of the sub-expressions is NULL then the operator results in NULL.
 */
class IntegerDivideProcessor extends OperatorProcessor
{
    /**
     * Process the IntegerDivide operator.
     *
     * @return QtiInteger|null An integer value that corresponds to the first expression divided by the second rounded down to the greatest integer i such that i <= x / y. If the second expression is 0 or if either of the sub-expressions is NULL, the result is NULL.
     */
    public function process(): ?QtiInteger
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The IntegerDivide operator only accepts operands with single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyInteger() === false) {
            $msg = 'The IntegerDivide operator only accepts operands with baseType integer.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        $operand1 = $operands[0];
        $operand2 = $operands[1];

        if ($operand2->getValue() == 0) {
            // division by zero forbidden.
            return null;
        }

        return new QtiInteger((int)floor($operand1->getValue() / $operand2->getValue()));
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return IntegerDivide::class;
    }
}
