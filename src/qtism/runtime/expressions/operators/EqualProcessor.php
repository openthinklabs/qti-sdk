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
use qtism\common\datatypes\QtiFloat;
use qtism\data\expressions\operators\Equal;
use qtism\data\expressions\operators\ToleranceMode;
use qtism\runtime\expressions\Utils;

/**
 * The EqualProcessor class aims at processing Equal operators.
 *
 * From IMS QTI:
 *
 * The equal operator takes two sub-expressions which must both have single
 * cardinality and have a numerical base-type. The result is a single boolean
 * with a value of true if the two expressions are numerically equal and false
 * if they are not. If either sub-expression is NULL then the operator results
 * in NULL.
 *
 * When comparing two floating point numbers for equality it is often desirable
 * to have a tolerance to ensure that spurious errors in scoring are not
 * introduced by rounding errors. The tolerance mode determines whether
 * the comparison is done exactly, using an absolute range or a relative range.
 *
 * If the tolerance mode is absolute or relative then the tolerance must be specified.
 * The tolerance consists of two positive numbers, t0 and t1, that define the lower
 * and upper bounds. If only one value is given it is used for both.
 *
 * In absolute mode the result of the comparison is true if the value of the
 * second expression, y is within the following range defined by the first value, x.
 *
 * x-t0,x+t1
 *
 * In relative mode, t0 and t1 are treated as percentages and the following
 * range is used instead.
 *
 * x*(1-t0/100),x*(1+t1/100)
 */
class EqualProcessor extends OperatorProcessor
{
    /**
     * Process the Equal operator.
     *
     * @return QtiBoolean|null Whether the two expressions are numerically equal and false if they are not or NULL if either sub-expression is NULL.
     * @throws OperatorProcessingException
     */
    public function process(): ?QtiBoolean
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The Equal operator only accepts operands with a single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyNumeric() === false) {
            $msg = 'The Equal operator only accepts operands with an integer or float baseType';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        $operand1 = $operands[0];
        $operand2 = $operands[1];
        $expression = $this->getExpression();

        if ($expression->getToleranceMode() === ToleranceMode::EXACT) {
            return new QtiBoolean($operand1->getValue() == $operand2->getValue());
        } else {
            $tolerance = $expression->getTolerance();

            if (is_string($tolerance[0])) {
                $strTolerance = $tolerance;
                $tolerance = [];

                // variableRef to handle.
                $state = $this->getState();
                $tolerance0Name = Utils::sanitizeVariableRef($strTolerance[0]);
                $varValue = $state[$tolerance0Name];

                if ($varValue === null) {
                    $msg = "The variable with name '{$tolerance0Name}' could not be resolved.";
                    throw new OperatorProcessingException($msg, $this, OperatorProcessingException::NONEXISTENT_VARIABLE);
                } elseif (!$varValue instanceof QtiFloat) {
                    $msg = "The variable with name '{$tolerance0Name}' is not a float.";
                    throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_VARIABLE_BASETYPE);
                }

                $tolerance[] = $varValue->getValue();

                if (isset($strTolerance[1]) && is_string($strTolerance[1])) {
                    // A second variableRef to handle.
                    $tolerance1Name = Utils::sanitizeVariableRef($strTolerance[1]);

                    if (($varValue = $state[$tolerance1Name]) !== null && $varValue instanceof QtiFloat) {
                        $tolerance[] = $varValue->getValue();
                    }
                }
            }

            if ($expression->getToleranceMode() === ToleranceMode::ABSOLUTE) {
                $t0 = $operand1->getValue() - $tolerance[0];
                $t1 = $operand1->getValue() + ($tolerance[1] ?? $tolerance[0]);

                $moreThanLower = ($expression->doesIncludeLowerBound()) ? $operand2->getValue() >= $t0 : $operand2->getValue() > $t0;
                $lessThanUpper = ($expression->doesIncludeUpperBound()) ? $operand2->getValue() <= $t1 : $operand2->getValue() < $t1;

                return new QtiBoolean($moreThanLower && $lessThanUpper);
            } else {
                // Tolerance mode RELATIVE
                $tolerance = $expression->getTolerance();
                $t0 = $operand1->getValue() * (1 - $tolerance[0] / 100);
                $t1 = $operand1->getValue() * (1 + ($tolerance[1] ?? $tolerance[0]) / 100);

                $moreThanLower = ($expression->doesIncludeLowerBound()) ? $operand2->getValue() >= $t0 : $operand2->getValue() > $t0;
                $lessThanUpper = ($expression->doesIncludeUpperBound()) ? $operand2->getValue() <= $t1 : $operand2->getValue() < $t1;

                return new QtiBoolean($moreThanLower && $lessThanUpper);
            }
        }
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return Equal::class;
    }
}
