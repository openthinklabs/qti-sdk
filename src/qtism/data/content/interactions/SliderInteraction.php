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

namespace qtism\data\content\interactions;

use InvalidArgumentException;
use qtism\data\QtiComponentCollection;

/**
 * From IMS QTI:
 *
 * The slider interaction presents the candidate with a control for
 * selecting a numerical value between a lower and upper bound. It must be
 * bound to a response variable with single cardinality with a base-type of
 * either integer or float.
 *
 * Note that a slider interaction does not have a default or initial position
 * except where specified by a default value for the associated response variable.
 * The currently selected value, if any, must be clearly indicated to the
 * candidate.
 *
 * Because a slider interaction does not have a default or initial
 * position — except where specified by a default value for the associated
 * response variable — it is difficult to distinguish between an intentional
 * response that corresponds to the slider's initial position and a NULL
 * response. As a workaround, sliderInteraction items have to either a)
 * not count NULL responses (i.e. count all responses as intentional) or
 * b) include a 'skip' button and count its activation combined with a
 * RESPONSE variable that is equal to the slider's initial position as a
 * NULL response.
 */
class SliderInteraction extends BlockInteraction
{
    /**
     * From IMS QTI:
     *
     * If the associated response variable is of type integer then the
     * lowerBound must be rounded down to the greatest integer less than
     * or equal to the value given.
     *
     * @var float
     * @qtism-bean-property
     */
    private $lowerBound;

    /**
     * From IMS QTI:
     *
     * If the associated response variable is of type integer then the
     * upperBound must be rounded up to the least integer greater than
     * or equal to the value given.
     *
     * @var float
     * @qtism-bean-property
     */
    private $upperBound;

    /**
     * From IMS QTI:
     *
     * The steps that the control moves in. For example, if the lowerBound
     * and upperBound are [0,10] and step is 2 then the response would be
     * constrained to the set of values {0,2,4,6,8,10}. If the response
     * variable is bound to an integer, and the step attribute is not
     * declared, the default step is 1. If the response variable is bound
     * to a float, and the step attribute is not declared, the slider is
     * assumed to operate on an approximately continuous scale.
     *
     * @var int
     * @qtism-bean-property
     */
    private $step = 0;

    /**
     * From IMS QTI:
     *
     * By default, sliders are labelled only at their ends. The stepLabel
     * attribute controls whether each step on the slider should
     * also be labelled. It is unlikely that delivery engines will be
     * able to guarantee to label steps so this attribute should be
     * treated only as request.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $stepLabel = false;

    /**
     * From IMS QTI:
     *
     * The orientation attribute provides a hint to rendering systems that
     * the slider is being used to indicate the value of a quantity with
     * an inherent vertical or horizontal interpretation. For example, an
     * interaction that is used to indicate the value of height might set
     * the orientation to vertical to indicate that rendering it horizontally
     * could spuriously increase the difficulty of the item.
     *
     * @var int
     * @qtism-bean-property
     */
    private $orientation = Orientation::HORIZONTAL;

    /**
     * From IMS QTI:
     *
     * The reverse attribute provides a hint to rendering systems that the
     * slider is being used to indicate the value of a quantity for which
     * the normal sense of the upper and lower bounds is reversed. For
     * example, an interaction that is used to indicate a depth below sea
     * level might specify both a vertical orientation and set reverse.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $reverse = false;

    /**
     * Create a new SliderInteraction object.
     *
     * @param string $responseIdentifier The identifier of the associated response variable.
     * @param float $lowerBound A lower bound.
     * @param float $upperBound An upper bound.
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException
     */
    public function __construct($responseIdentifier, $lowerBound, $upperBound, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($responseIdentifier, $id, $class, $lang, $label);
        $this->setLowerBound($lowerBound);
        $this->setUpperBound($upperBound);
    }

    /**
     * Get the value of the lowerBound attribute.
     *
     * @param float $lowerBound A float value.
     * @throws InvalidArgumentException If $lowerBound is not a float value.
     */
    public function setLowerBound($lowerBound): void
    {
        if (is_float($lowerBound)) {
            $this->lowerBound = $lowerBound;
        } else {
            $msg = "The 'lowerBound' argument must be a float value, '" . gettype($lowerBound) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Set the value of the lowerBound attribute.
     *
     * @return float A float value.
     */
    public function getLowerBound(): float
    {
        return $this->lowerBound;
    }

    /**
     * Set the value of the upperBound attribute.
     *
     * @param float $upperBound A float value.
     * @throws InvalidArgumentException If $upperBound is not a float value.
     */
    public function setUpperBound($upperBound): void
    {
        if (is_float($upperBound)) {
            $this->upperBound = $upperBound;
        } else {
            $msg = "The 'upperBound' argument must be a float value, '" . gettype($upperBound) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the value of the upperBound attribute.
     *
     * @return float A float value.
     */
    public function getUpperBound(): float
    {
        return $this->upperBound;
    }

    /**
     * Set the step that controls the move of the slider. If $step is 0, it means
     * that no value is actually defined for the step attribute.
     *
     * @param int $step A positive (>= 0) integer.
     * @throws InvalidArgumentException If $step is not a positive integer.
     */
    public function setStep($step): void
    {
        if (is_int($step) && $step >= 0) {
            $this->step = $step;
        } else {
            $msg = "The 'step' argument must be a positive (>= 0) integer, '" . gettype($step) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the step that controls the move of the slider.
     *
     * @return int A positive (>= 0) integer.
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * Whether a value is defined for the step attribute.
     *
     * @return bool
     */
    public function hasStep(): bool
    {
        return $this->getStep() > 0;
    }

    /**
     * Set whether each step on the slider has to be labelled.
     *
     * @param bool $stepLabel
     * @throws InvalidArgumentException If $stepLabel is not a boolean value.
     */
    public function setStepLabel($stepLabel): void
    {
        if (is_bool($stepLabel)) {
            $this->stepLabel = $stepLabel;
        } else {
            $msg = "The 'stepLabel' argument must be a boolean value, '" . gettype($stepLabel) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether each step on the slider has to be labelled.
     *
     * @return bool
     */
    public function mustStepLabel(): bool
    {
        return $this->stepLabel;
    }

    /**
     * Set the orientation of the slider (horizontal or vertical).
     *
     * @param int $orientation A value from the Orientation enumeration.
     * @throws InvalidArgumentException If $orientation is not a value from the Orientation enumeration.
     */
    public function setOrientation($orientation): void
    {
        if (in_array($orientation, Orientation::asArray(), true)) {
            $this->orientation = $orientation;
        } else {
            $msg = "The 'orientation' argument must be a value from the Orientation enumeration.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the orientation of the slider (horizontal or vertical).
     *
     * @return int A value from the Orientation enumeration.
     */
    public function getOrientation(): int
    {
        return $this->orientation;
    }

    /**
     * Set whether or not the upper and lower bounds are reversed.
     *
     * @param bool $reverse
     * @throws InvalidArgumentException If $reverse is not a boolean value.
     */
    public function setReverse($reverse): void
    {
        if (is_bool($reverse)) {
            $this->reverse = $reverse;
        } else {
            $msg = "The 'reverse' argument must be a boolean value, '" . gettype($reverse) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether the upper and lower bounds are reversed.
     *
     * @return bool
     */
    public function mustReverse(): bool
    {
        return $this->reverse;
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return parent::getComponents();
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'sliderInteraction';
    }
}
