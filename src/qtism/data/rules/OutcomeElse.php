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

use InvalidArgumentException;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

/**
 * The OutcomeElse class.
 */
class OutcomeElse extends QtiComponent
{
    /**
     * A collection of OutcomeRule objects to be evaluated.
     *
     * @var OutcomeRuleCollection
     * @qtism-bean-property
     */
    private $outcomeRules;

    /**
     * Create a new instance of OutcomeElse.
     *
     * @param OutcomeRuleCollection $outcomeRules A collection of OutcomeRule objects.
     * @throws InvalidArgumentException If $outcomeRules is an empty collection.
     */
    public function __construct(OutcomeRuleCollection $outcomeRules)
    {
        $this->setOutcomeRules($outcomeRules);
    }

    /**
     * Get the OutcomeRule objects to be evaluated.
     *
     * @return OutcomeRuleCollection A collection of OutcomeRule objects.
     */
    public function getOutcomeRules(): OutcomeRuleCollection
    {
        return $this->outcomeRules;
    }

    /**
     * Set the OutcomeRule objects to be evaluated.
     *
     * @param OutcomeRuleCollection $outcomeRules A collection of OutcomeRule objects.
     * @throws InvalidArgumentException If $outcomeRules is an empty collection.
     */
    public function setOutcomeRules(OutcomeRuleCollection $outcomeRules): void
    {
        if (count($outcomeRules) <= 0) {
            $msg = 'An OutcomeElse object must be bound to at least one OutcomeRule object.';
            throw new InvalidArgumentException($msg);
        }

        $this->outcomeRules = $outcomeRules;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'outcomeElse';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $comp = $this->getOutcomeRules()->getArrayCopy();

        return new QtiComponentCollection($comp);
    }
}
