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

use InvalidArgumentException;
use qtism\data\expressions\ExpressionCollection;

/**
 * From IMS QTI:
 *
 * The substring operator takes two sub-expressions which must both have an effective
 * base-type of string and single cardinality. The result is a single boolean with a
 * value of true if the first expression is a substring of the second expression and
 * false if it isn't. If either sub-expression is NULL then the result of the operator
 * is NULL.
 */
class Substring extends Operator
{
    /**
     * From IMS QTI:
     *
     * Used to control whether the substring is matched case sensitively.
     * If true then the match is case sensitive and, for example, "Hell" is not
     * a substring of "Shell". If false then the match is not case sensitive and "Hell"
     * is a substring of "Shell".
     *
     * @var bool
     * @qtism-bean-property
     */
    private $caseSensitive = true;

    /**
     * Create a new Substring.
     *
     * @param ExpressionCollection $expressions A collection of Expression objects.
     * @param bool $caseSensitive A boolean value.
     * @throws InvalidArgumentException If $caseSensitive is not a boolean or if the count of $expressions is not correct.
     */
    public function __construct(ExpressionCollection $expressions, $caseSensitive = true)
    {
        parent::__construct($expressions, 2, 2, [OperatorCardinality::SINGLE], [OperatorBaseType::STRING]);
        $this->setCaseSensitive($caseSensitive);
    }

    /**
     * Set the caseSensitive attribute.
     *
     * @param bool $caseSensitive A boolean value.
     * @throws InvalidArgumentException If $caseSensitive is not a boolean value.
     */
    public function setCaseSensitive($caseSensitive): void
    {
        if (is_bool($caseSensitive)) {
            $this->caseSensitive = $caseSensitive;
        } else {
            $msg = "The caseSensitive argument must be a boolean value, '" . gettype($caseSensitive) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether the operator is case sensitive.
     *
     * @return bool
     */
    public function isCaseSensitive(): bool
    {
        return $this->caseSensitive;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'substring';
    }
}
