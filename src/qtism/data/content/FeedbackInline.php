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

namespace qtism\data\content;

use InvalidArgumentException;
use qtism\common\utils\Format;
use qtism\data\ShowHide;

/**
 * The FeedbackInline QTI class.
 */
class FeedbackInline extends SimpleInline implements FeedbackElement
{
    /**
     * From IMS QTI:
     *
     * The identifier of an outcome variable that must have a base-type of
     * identifier and be of either single or multiple cardinality. The visibility
     * of the feedbackElement is controlled by assigning a value (or values) to
     * this outcome variable during responseProcessing.
     *
     * @var string
     * @qtism-bean-property
     */
    private $outcomeIdentifier;

    /**
     * From IMS QTI:
     *
     * The showHide attribute determines how the visibility of the feedbackElement is
     * controlled. If set to show then the feedback is hidden by default and shown
     * only if the associated outcome variable matches, or contains, the value of
     * the identifier attribute. If set to hide then the feedback is shown by
     * default and hidden if the associated outcome variable matches, or contains,
     * the value of the identifier attribute.
     *
     * @var int
     * @qtism-bean-property
     */
    private $showHide = ShowHide::SHOW;

    /**
     * From IMS QTI:
     *
     * The identifier that determines the visibility of the feedback in conjunction
     * with the showHide attribute.
     *
     * @var string
     * @qtism-bean-property
     */
    private $identifier;

    /**
     * Create a new FeedbackInline object.
     *
     * @param string $outcomeIdentifier The identifier of an outcome variable controlling the visibility of the feedbackElement.
     * @param string $identifier The identifier value that determines the visibility of the feedbackElement in conjunction with $showHide.
     * @param int $showHide A values of the ShowHide enumeration that determines hot the visibility of the feedbackElement is controlled.
     * @param string $id The identifier of the bodyElement.
     * @param string $class The class(es) of the bodyElement. If multiple classes, separate them with whitespaces (' ').
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException If any arguments of the constructor is invalid.
     */
    public function __construct($outcomeIdentifier, $identifier, $showHide = ShowHide::SHOW, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($id, $class, $lang, $label);
        $this->setOutcomeIdentifier($outcomeIdentifier);
        $this->setIdentifier($identifier);
        $this->setShowHide($showHide);
    }

    /**
     * Set the identifier of the outcome variable that determines the visibility of the feedbackInline.
     *
     * @param string $outcomeIdentifier A QTI identifier.
     * @throws InvalidArgumentException If $outcomeIdentifier is not a valid QTI identifier.
     */
    public function setOutcomeIdentifier($outcomeIdentifier): void
    {
        if (Format::isIdentifier($outcomeIdentifier, false) === true) {
            $this->outcomeIdentifier = $outcomeIdentifier;
        } else {
            $msg = "The 'outcomeIdentifier' argument must be a valid QTI identifier, '" . $outcomeIdentifier . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the identifier of the outcome variable that determines the visibility of the feedbackInline.
     *
     * @return string A QTI identifier.
     */
    public function getOutcomeIdentifier(): string
    {
        return $this->outcomeIdentifier;
    }

    /**
     * Set how the visibility of the feedbackInline is handled. If set to ShowHide::show, the feedback is hidden by default
     * and shown if the identifier matches. If set the ShowHide::hide, the feedback is shown by default, and hidden if the identifier
     * matches.
     *
     * @param int $showHide A value from the ShowHide enumeration.
     * @throws InvalidArgumentException If $showHide is not a value from the ShowHide enumeration.
     */
    public function setShowHide($showHide): void
    {
        if (in_array($showHide, ShowHide::asArray())) {
            $this->showHide = $showHide;
        } else {
            $msg = "The 'showHide' argument must be a value from the ShowHide enumeration, '" . $showHide . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get how the visibility of the feedbackInline is handled.
     *
     * @return int A value from the ShowHide enumeration.
     */
    public function getShowHide(): int
    {
        return $this->showHide;
    }

    /**
     * Set the identifier to be matched to show/hide the feedbackInline.
     *
     * @param string $identifier A QTI identifier.
     * @throws InvalidArgumentException If $identifier is not a valid QTI identifier.
     */
    public function setIdentifier($identifier): void
    {
        if (Format::isIdentifier($identifier, false) === true) {
            $this->identifier = $identifier;
        } else {
            $msg = "The 'identifier' argument must be a valid QTI identifier, '" . $identifier . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the identifier to be matched to show/hide the feedbackInline.
     *
     * @return string A QTI identifier.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'feedbackInline';
    }
}
