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

namespace qtism\data\rules;

use qtism\data\expressions\Expression;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

/**
 * A preCondition is a simple expression attached to an assessmentSection or assessmentItemRef
 * that must evaluate to true if the item is to be presented. Pre-conditions are evaluated at
 * the time the associated item, section or testPart is to be attempted by the candidate,
 * during the test. They differ from rules for selection and ordering (see Test Structure)
 * which are followed at or before the start of the test.
 *
 * If the expression evaluates to false, or has a NULL value, the associated item or section
 * is skipped.
 */
class PreCondition extends QtiComponent
{
    /**
     * The expression that will make the Precondition return true or false.
     *
     * @var Expression
     * @qtism-bean-property
     */
    private $expression;

    /**
     * Create a new instance of PreCondition.
     *
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        $this->setExpression($expression);
    }

    /**
     * Get the expression of the PreCondition.
     *
     * @return Expression A QTI Expression.
     */
    public function getExpression(): Expression
    {
        return $this->expression;
    }

    /**
     * Set the expression of the Precondition.
     *
     * @param Expression $expression A QTI Expression.
     */
    public function setExpression(Expression $expression): void
    {
        $this->expression = $expression;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'preCondition';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return new QtiComponentCollection([$this->getExpression()]);
    }
}
