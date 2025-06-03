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

use qtism\common\datatypes\QtiBoolean;
use qtism\data\expressions\operators\AndOperator;

/**
 * The AndProcessor class aims at processing AndOperator QTI Data Model Expression objects.
 *
 * Developer's note: IMS does not explain what happens when one or more sub-expressions are NULL
 * but not all sub-expressions are true. In this implementation, we consider that NULL is returned
 * if one ore more sub-expressions are NULL.
 *
 * From IMS QTI:
 *
 * The and operator takes one or more sub-expressions each with a base-type of boolean and single
 * cardinality. The result is a single boolean which is true if all sub-expressions are true and
 * false if any of them are false. If one or more sub-expressions are NULL and all others are true
 * then the operator also results in NULL.
 */
class AndProcessor extends OperatorProcessor
{
    /**
     * Process the current expression.
     *
     * @return QtiBoolean True if the expression is true, false otherwise.
     * @throws OperatorProcessingException
     */
    public function process(): ?QtiBoolean
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The And Expression only accept operands with single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyBoolean() === false) {
            $msg = 'The And Expression only accept operands with boolean baseType.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        foreach ($operands as $operand) {
            if ($operand->getValue() === false) {
                return new QtiBoolean(false);
            }
        }

        return new QtiBoolean(true);
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return AndOperator::class;
    }
}
