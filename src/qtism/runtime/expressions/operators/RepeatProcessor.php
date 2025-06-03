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

use qtism\common\datatypes\QtiDatatype;
use qtism\common\datatypes\QtiInteger;
use qtism\common\enums\Cardinality;
use qtism\data\expressions\operators\Repeat;
use qtism\runtime\common\Container;
use qtism\runtime\common\OrderedContainer;
use qtism\runtime\common\Utils as RuntimeUtils;
use qtism\runtime\expressions\Utils as ExprUtils;

/**
 * The RepeatProcessor class aims at processing Repeat operators.
 *
 * From IMS QTI:
 *
 * The repeat operator takes 1 or more sub-expressions, all of which must have either
 * single or ordered cardinality and the same baseType.
 *
 * The result is an ordered container having the same baseType as its sub-expressions.
 * The container is filled sequentially by evaluating each sub-expression in turn and
 * adding the resulting single values to the container, iterating this process
 * numberRepeats times in total. If numberRepeats refers to a variable whose value
 * is less than 1, the value of the whole expression is NULL.
 *
 * Any sub-expressions evaluating to NULL are ignored. If all sub-expressions are
 * NULL then the result is NULL.
 */
class RepeatProcessor extends OperatorProcessor
{
    /**
     * Process the Repeat operator.
     *
     * Note: NULL values are simply ignored. If all sub-expressions are NULL, NULL is
     * returned.
     *
     * @return OrderedContainer An ordered container filled sequentially by evaluating each sub-expressions, repeated a 'numberRepeats' of times. NULL is returned if all sub-expressions are NULL or numberRepeats < 1.
     * @throws OperatorProcessingException
     */
    public function process(): ?OrderedContainer
    {
        $operands = $this->getOperands();

        // get the value of numberRepeats
        $expression = $this->getExpression();
        $numberRepeats = $expression->getNumberRepeats();

        if (is_string($numberRepeats)) {
            // Variable reference found.
            $state = $this->getState();
            $varName = ExprUtils::sanitizeVariableRef($numberRepeats);
            $varValue = $state[$varName];

            if ($varValue === null) {
                $msg = "The variable with name '{$varName}' could not be resolved.";
                throw new OperatorProcessingException($msg, $this);
            } elseif (!$varValue instanceof QtiInteger) {
                $msg = "The variable with name '{$varName}' is not an integer value.";
                throw new OperatorProcessingException($msg, $this);
            }

            $numberRepeats = $varValue->getValue();
        }

        if ($numberRepeats < 1) {
            return null;
        }

        $result = null;
        for ($i = 0; $i < $numberRepeats; $i++) {
            $refType = null;

            foreach ($operands as $operand) {
                // If null, ignore
                if ($operand === null || ($operand instanceof Container && $operand->isNull())) {
                    continue;
                }

                // Check cardinality.
                if ($operand->getCardinality() !== Cardinality::SINGLE && $operand->getCardinality() !== Cardinality::ORDERED) {
                    $msg = 'The Repeat operator only accepts operands with a single or ordered cardinality.';
                    throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
                }

                // Check baseType.
                $currentType = RuntimeUtils::inferBaseType($operand);

                if ($refType !== null && $currentType !== $refType) {
                    $msg = 'The Repeat operator only accepts operands with the same baseType.';
                    throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
                } elseif ($result === null) {
                    $refType = $currentType;
                    $result = new OrderedContainer($refType);
                }

                // Okay we are good...
                $operandCardinality = RuntimeUtils::inferCardinality($operand);
                if ($operandCardinality !== Cardinality::ORDERED) {
                    $operand = new OrderedContainer($currentType, [$operand]);
                }

                foreach ($operand as $o) {
                    $result[] = ($o instanceof QtiDatatype) ? clone $o : $o;
                }
            }
        }

        if (isset($result) && $result->isNull() !== true) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return Repeat::class;
    }
}
