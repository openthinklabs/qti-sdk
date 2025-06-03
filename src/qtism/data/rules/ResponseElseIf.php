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
 * From IMS QTI:
 *
 * responseElseIf is defined in an identical way to responseIf.
 */
class ResponseElseIf extends QtiComponent
{
    /**
     * The expression to be evaluated with the Else If statement.
     *
     * @var Expression
     * @qtism-bean-property
     */
    private $expression;

    /**
     * The collection of ResponseRule objects to be evaluated as sub expressions
     * if the expression bound to the Else If statement is evaluated to true.
     *
     * @var ResponseRuleCollection
     * @qtism-bean-property
     */
    private $responseRules;

    /**
     * Create a new instance of ResponseElseIf.
     *
     * @param Expression $expression An expression to be evaluated with the Else If statement.
     * @param ResponseRuleCollection $responseRules A collection of ResponseRule objects.
     */
    public function __construct(Expression $expression, ResponseRuleCollection $responseRules)
    {
        $this->setExpression($expression);
        $this->setResponseRules($responseRules);
    }

    /**
     * Get the expression to be evaluated with the Else If statement.
     *
     * @return Expression An Expression object.
     */
    public function getExpression(): Expression
    {
        return $this->expression;
    }

    /**
     * Set the expression to be evaluated with the Else If statement.
     *
     * @param Expression $expression An Expression object.
     */
    public function setExpression(Expression $expression): void
    {
        $this->expression = $expression;
    }

    /**
     * Get the ResponseRules to be evaluated as sub expressions if the expression bound
     * to the Else If statement returns true.
     *
     * @return ResponseRuleCollection A collection of OutcomeRule objects.
     */
    public function getResponseRules(): ResponseRuleCollection
    {
        return $this->responseRules;
    }

    /**
     * Set the ResponseRules to be evaluated as sub expressions if the expression bound
     * to the Else If statement returns true.
     *
     * @param ResponseRuleCollection $responseRules A collection of ResponseRule objects.
     */
    public function setResponseRules(ResponseRuleCollection $responseRules): void
    {
        $this->responseRules = $responseRules;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'responseElseIf';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $comp = array_merge(
            [$this->getExpression()],
            $this->getResponseRules()->getArrayCopy()
        );

        return new QtiComponentCollection($comp);
    }
}
