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

namespace qtism\data;

use InvalidArgumentException;
use qtism\common\utils\Format;
use qtism\data\rules\BranchRuleCollection;
use qtism\data\rules\PreConditionCollection;
use SplObjectStorage;

/**
 * From IMS QTI:
 *
 * Sections group together individual item references and/or sub-sections.
 * A number of common parameters are shared by both types of child element.
 */
class SectionPart extends QtiComponent implements QtiIdentifiable, Shufflable
{
    use QtiIdentifiableTrait;

    /**
     * From IMS QTI:
     *
     * The identifier of the section or item reference must be unique within the test and
     * must not be the identifier of any testPart.
     *
     * @var string
     * @qtism-bean-property
     */
    private $identifier;

    /**
     * From IMS QTI:
     *
     * If a child element is required it must appear (at least once) in the selection.
     * It is in error if a section contains a selection rule that selects fewer child
     * elements than the number of required elements it contains.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $required = false;

    /**
     * From IMS QTI:
     *
     * If a child element is required it must appear (at least once) in the selection.
     * It is in error if a section contains a selection rule that selects fewer child
     * elements than the number of required elements it contains.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $fixed = false;

    /**
     * From IMS QTI:
     *
     * An optional set of conditions evaluated during the test, that determine if the
     * item or section is to be skipped (in nonlinear mode, pre-conditions are ignored).
     *
     * @var PreConditionCollection
     * @qtism-bean-property
     */
    private $preConditions;

    /**
     * From IMS QTI:
     *
     * An optional set of rules, evaluated during the test, for setting an alternative
     * target as the next item or section (in nonlinear mode, branch rules are ignored).
     *
     * @var BranchRuleCollection
     * @qtism-bean-property
     */
    private $branchRules;

    /**
     * From IMS QTI:
     *
     * Parameters used to control the allowable states of each item session
     * (may be overridden at sub-section or item level).
     *
     * @var ItemSessionControl
     * @qtism-bean-property
     */
    private $itemSessionControl = null;

    /**
     * From IMS QTI:
     *
     * Optionally controls the amount of time a candidate is allowed for this item or section.
     *
     * @var TimeLimits
     * @qtism-bean-property
     */
    private $timeLimits = null;

    private ?SectionPart $parent = null;

    private bool $isLast = false;

    /**
     * Create a new instance of SectionPart.
     *
     * @param string $identifier A QTI Identifier.
     * @param bool $required true if it must absolutely appear during the session, false if not.
     * @param bool $fixed true if it must not be affected by shuffling, false if it can be affected by shuffling.
     * @throws InvalidArgumentException If $identifier is not a valid QTI Identifier, $required or $fixed are not booleans.
     */
    public function __construct($identifier, $required = false, $fixed = false)
    {
        $this->setObservers(new SplObjectStorage());

        $this->setIdentifier($identifier);
        $this->setRequired($required);
        $this->setFixed($fixed);
        $this->setPreConditions(new PreConditionCollection());
        $this->setBranchRules(new BranchRuleCollection());
    }

    /**
     * Get the identifier of the Section Part.
     *
     * @return string A QTI Identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set the identifier of the Section Part.
     *
     * @param string $identifier A QTI Identifier.
     * @throws InvalidArgumentException If $identifier is not a valid QTI Identifier.
     */
    public function setIdentifier($identifier): void
    {
        if (Format::isIdentifier($identifier, false)) {
            $this->identifier = $identifier;
            $this->notify();
        } else {
            $msg = "'{$identifier}' is not a valid QTI Identifier.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Must appear at least once?
     *
     * @return bool true if must appear at least one, false if not.
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set if it must appear at least one during the session.
     *
     * @param bool $required true if it must appear at least one, otherwise false.
     * @throws InvalidArgumentException If $required is not a boolean.
     */
    public function setRequired($required): void
    {
        if (is_bool($required)) {
            $this->required = $required;
        } else {
            $msg = "Required must be a boolean, '" . gettype($required) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Subject to shuffling?
     *
     * @return bool true if subject to shuffling, false if not.
     */
    public function isFixed(): bool
    {
        return $this->fixed;
    }

    /**
     * Set if the section part is subject to shuffling.
     *
     * @param bool $fixed true if subject to shuffling, false if not.
     * @throws InvalidArgumentException If $fixed is not a boolean.
     */
    public function setFixed($fixed): void
    {
        if (is_bool($fixed)) {
            $this->fixed = $fixed;
        } else {
            $msg = "Fixed must be a boolean, '" . gettype($fixed) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the collection of PreConditions bound to this section part.
     *
     * @return PreConditionCollection A collection of PreCondition objects.
     */
    public function getPreConditions(): PreConditionCollection
    {
        return $this->preConditions;
    }

    /**
     * Set the collection of PreConditions bound to this sections part.
     *
     * @param PreConditionCollection $preConditions A collection of PreCondition objects.
     */
    public function setPreConditions(PreConditionCollection $preConditions): void
    {
        $this->preConditions = $preConditions;
    }

    /**
     * Get the collection of BranchRules bound to this section part.
     *
     * @return BranchRuleCollection A collection of BranchRule objects.
     */
    public function getBranchRules(): BranchRuleCollection
    {
        return $this->branchRules;
    }

    /**
     * Set the collection of BranchRules bound to this section part.
     *
     * @param BranchRuleCollection $branchRules A collection of BranchRule objects.
     */
    public function setBranchRules(BranchRuleCollection $branchRules): void
    {
        $this->branchRules = $branchRules;
    }

    /**
     * Get the parameters used to control the allowable states of each item session.
     * Returns null value if not specified.
     *
     * @return ItemSessionControl
     */
    public function getItemSessionControl(): ?ItemSessionControl
    {
        return $this->itemSessionControl;
    }

    /**
     * Set the parameters used to control the allowable states of each item session.
     *
     * @param ItemSessionControl $itemSessionControl An ItemSessionControl object.
     */
    public function setItemSessionControl(ItemSessionControl $itemSessionControl = null): void
    {
        $this->itemSessionControl = $itemSessionControl;
    }

    /**
     * Whether the SectionPart holds an ItemSessionControl object.
     *
     * @return bool
     */
    public function hasItemSessionControl(): bool
    {
        return $this->getItemSessionControl() !== null;
    }

    /**
     * Set the amount of time a candidate is allowed for this section.
     * Returns null value if not specified.
     *
     * @return TimeLimits A TimeLimits object.
     */
    public function getTimeLimits(): ?TimeLimits
    {
        return $this->timeLimits;
    }

    /**
     * Whether the SectionPart holds a TimeLimits object.
     *
     * @return bool
     */
    public function hasTimeLimits(): bool
    {
        return $this->getTimeLimits() !== null;
    }

    /**
     * Set the amount of time a candidate is allowed for this section.
     * Returns null value if not specified.
     *
     * @param TimeLimits $timeLimits A TimeLimits object.
     */
    public function setTimeLimits(TimeLimits $timeLimits = null): void
    {
        $this->timeLimits = $timeLimits;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'sectionPart';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $comp = array_merge(
            $this->getBranchRules()->getArrayCopy(),
            $this->getPreConditions()->getArrayCopy()
        );

        if ($this->getTimeLimits() !== null) {
            $comp[] = $this->getTimeLimits();
        }

        if ($this->getItemSessionControl() !== null) {
            $comp[] = $this->getItemSessionControl();
        }

        return new QtiComponentCollection($comp);
    }

    public function __clone()
    {
        $this->setBranchRules(clone $this->getBranchRules());
        $this->setPreConditions(clone $this->getPreConditions());

        if ($this->hasItemSessionControl() === true) {
            $this->setItemSessionControl(clone $this->getItemSessionControl());
        }

        if ($this->hasTimeLimits() === true) {
            $this->setTimeLimits(clone $this->getTimeLimits());
        }

        // Reset observers.
        $this->setObservers(new SplObjectStorage());
    }

    public function getParent(): ?SectionPart
    {
        return $this->parent;
    }

    public function setParent(SectionPart $parent): void
    {
        $this->parent = $parent;
    }

    public function isLast(): bool
    {
        return $this->isLast;
    }

    public function setIsLast(bool $isLast): void
    {
        $this->isLast = $isLast;
    }
}
