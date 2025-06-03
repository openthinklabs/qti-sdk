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
use qtism\data\expressions\operators\DurationGTE;

/**
 * The DurationGTEProcessor class aims at processing DurationGTE operators.
 *
 * From IMS QTI:
 *
 * The durationGTE operator takes two sub-expressions which must both have
 * single cardinality and base-type duration. The result is a single boolean with a
 * value of true if the first duration is longer (or equal, within the limits imposed
 * by truncation as described above) than the second and false if it is shorter than
 * the second. If either sub-expression is NULL then the operator results in NULL.
 *
 * See durationLT for more information about testing the equality of durations.
 */
class DurationGTEProcessor extends OperatorProcessor
{
    /**
     * Process the DurationGTE operator.
     *
     * @return QtiBoolean|null A boolean with a value of true if the first duration is longer or equal to the second, otherwise false. If either sub-expression is NULL, the result of the operator is NULL.
     * @throws OperatorProcessingException
     */
    public function process(): ?QtiBoolean
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return null;
        }

        if ($operands->exclusivelySingle() === false) {
            $msg = 'The DurationGTE operator only accepts operands with a single cardinality.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        }

        if ($operands->exclusivelyDuration() === false) {
            $msg = 'The DurationGTE operator only accepts operands with a duration baseType.';
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        return new QtiBoolean($operands[0]->longerThanOrEquals($operands[1]));
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return DurationGTE::class;
    }
}
