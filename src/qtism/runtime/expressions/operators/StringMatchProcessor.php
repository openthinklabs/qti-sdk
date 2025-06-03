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
use qtism\data\expressions\operators\StringMatch;

/**
 * The StringMatchProcessor class aims at processing StringMatch operators.
 *
 * Please note that this implementation does not take care of the deprecated
 * attribute 'substring'.
 *
 * From IMS QTI:
 *
 * The stringMatch operator takes two sub-expressions which must have single and
 * a base-type of string. The result is a single boolean with a value of true if
 * the two strings match according to the comparison rules defined by the attributes
 * below and false if they don't. If either sub-expression is NULL then the operator
 * results in NULL.
 */
class StringMatchProcessor extends OperatorProcessor
{
    /**
     * Process the StringMatch operator.
     *
     * @return QtiBoolean Whether the two string match according to the comparison rules of the operator's attributes or NULL if either of the sub-expressions is NULL.
     * @throws OperatorProcessingException
     */
    public function process(): ?QtiBoolean
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The StringMatch operator only accepts operands with a single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyString() === false) {
            $msg = 'The StringMatch operator only accepts operands with a string baseType.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        $expression = $this->getExpression();

        // choose the correct comparison function according comparison rules
        // of the operator.
        // Please note that strcmp and strcasecmp are binary-safe *\0/* Hourray! *\0/*
        $func = ($expression->isCaseSensitive() === true) ? 'strcmp' : 'strcasecmp';

        return new QtiBoolean($func($operands[0]->getValue(), $operands[1]->getValue()) === 0);
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return StringMatch::class;
    }
}
