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

/**
 * Class ItemSessionControl
 */
class ItemSessionControl extends QtiComponent
{
    /**
     * From IMS QTI:
     *
     * For non-adaptive items, maxAttempts controls the maximum number of attempts allowed in
     * the given test context. Normally this is 1 as the scoring rules for non-adaptive items
     * are the same for each attempt. A value of 0 indicates no limit. If it is unspecified
     * it is treated as 1 for non-adaptive items. For adaptive items, the value of maxAttempts
     * is ignored as the number of attempts is limited by the value of the completionStatus
     * built-in outcome variable.
     *
     * A value of maxAttempts greater than 1, by definition, indicates
     * that any applicable feedback must be shown. This applies to both Modal
     * Feedback and Integrated Feedback where applicable. However, once the
     * maximum number of allowed attempts have been used (or for adaptive items,
     * completionStatus has been set to completed) whether feedback is shown
     * is controlled by the showFeedback constraint.
     *
     * @var int
     * @qtism-bean-property
     */
    private $maxAttempts = 1;

    /**
     * From IMS QTI:
     *
     * This constraint affects the visibility of feedback after the end of the last attempt.
     * If it is false then feedback is not shown. This includes both Modal Feedback and
     * Integrated Feedback even if the candidate has access to the review state.
     * The default is false.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $showFeedback = false;

    /**
     * From IMS QTI:
     *
     * This constraint also applies only after the end of the last attempt. If set to true the
     * item session is allowed to enter the review state during which the candidate can review
     * the itemBody along with the responses they gave, but cannot update or resubmit them.
     * If set to false the candidate can not review the itemBody or their responses once they
     * have submitted their last attempt. The default is true.
     *
     * If the review state is allowed, but feedback is not, delivery systems must take extra
     * care not to show integrated feedback that resulted from the last attempt as part of
     * the review process. Feedback can however take the form of hiding material that was
     * previously visible as well as the more usual form of showing material that was previously
     * hidden.
     *
     * To resolve this ambiguity, for non-adaptive items the absence of feedback is defined
     * to be the version of the itemBody displayed to the candidate at the start of each attempt.
     * In other words, with the visibility of any integrated feedback determined by the default
     * values of the outcome variables and not the values of the outcome variables updated by
     * the invocation of response processing.
     *
     * For Adaptive Items the situation is complicated by the iterative nature of response
     * processing which makes it hard to identify the appropriate state in which to place
     * the item for review. To avoid requiring delivery engines to cache the values of the
     * outcome variables the setting of showFeedback should be ignored for adaptive items
     * when allowReview is true. When in the review state, the final values of the outcome
     * variables should be used to determine the visibility of integrated feedback.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $allowReview = true;

    /**
     * From IMS QTI:
     *
     * This constraint controls whether the system may provide the candidate with a
     * way of entering the solution state. The default is false.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $showSolution = false;

    /**
     * From IMS QTI:
     *
     * Some delivery systems support the capture of candidate comments. The comment is not
     * part of the assessed responses but provides feedback from the candidate to the other
     * actors in the assessment process. This constraint controls whether the candidate
     * is allowed to provide a comment on the item during the session.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $allowComment = false;

    /**
     * From IMS QTI:
     *
     * This attribute controls the behaviour of delivery engines when the candidate submits an
     * invalid response. An invalid response is defined to be a response which does not satisfy
     * the constraints imposed by the interaction with which it is associated. See interaction
     * for more information. When validateResponses is turned on (true) then the candidates are
     * not allowed to submit the item until they have provided valid responses for all
     * interactions. When turned off (false) invalid responses may be accepted by the system.
     * The value of this attribute is only applicable when the item is in a testPart with
     * individual submission mode. (See Navigation and Submission.)
     *
     * @var bool
     * @qtism-bean-property
     */
    private $validateResponses = false;

    /**
     * From IMS QTI:
     *
     * An item is defined to be skipped if the candidate has not provided any response.
     * In other words, all response variables are submitted with their default value or
     * are NULL. This definition is consistent with the numberResponded operator available
     * in outcomeProcessing. If false, candidates are not allowed to skip the item, or
     * in other words, they are not allowed to submit the item until they have provided
     * a non-default value for at least one of the response variables. By definition,
     * an item with no response variables cannot be skipped. The value of this attribute
     * is only applicable when the item is in a testPart with individual submission mode.
     * Note that if allowSkipping is true delivery engines must ensure that the candidate
     * can choose to submit no response, for example, through the provision of a "skip" button.
     *
     * @var bool
     * @qtism-bean-property
     */
    private $allowSkipping = true;

    /**
     * Get the maximum number of attempts in the given test context.
     *
     * @return int An integer.
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * Set the maximum number of attempts in the given test context.
     *
     * @param int $maxAttempts An integer.
     * @throws InvalidArgumentException If $maxAttempts is not an integer.
     */
    public function setMaxAttempts($maxAttempts): void
    {
        if (is_int($maxAttempts)) {
            $this->maxAttempts = $maxAttempts;
        } else {
            $msg = "MaxAttempts must be an integer, '" . gettype($maxAttempts) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Must show feedback in the given test context?
     *
     * @return bool true if feedbacks must be shown, otherwise false.
     */
    public function mustShowFeedback(): bool
    {
        return $this->showFeedback;
    }

    /**
     * Set if feedbacks must be shown in the given test context.
     *
     * @param bool $showFeedback true if feedbacks must be shown, otherwise false.
     * @throws InvalidArgumentException If $showFeedback is not a boolean value.
     */
    public function setShowFeedback($showFeedback): void
    {
        if (is_bool($showFeedback)) {
            $this->showFeedback = $showFeedback;
        } else {
            $msg = "ShowFeedback must be a boolean, '" . gettype($showFeedback) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Is the candidate allowed to review item body and given responses?
     *
     * @return bool true if allowed, false if not allowed.
     */
    public function doesAllowReview(): bool
    {
        return $this->allowReview;
    }

    /**
     * Set that the candidate is allowed to review item body and given responses
     * after the last item.
     *
     * @param bool $allowReview true if allowed, false if not.
     * @throws InvalidArgumentException If $allowReview is not a boolean.
     */
    public function setAllowReview($allowReview): void
    {
        if (is_bool($allowReview)) {
            $this->allowReview = $allowReview;
        } else {
            $msg = "AllowReview must be a boolean, '" . gettype($allowReview) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Must provide the candidate a way to enter the 'solution' state? Default is false.
     *
     * @return bool true if the candidate can, false if not.
     */
    public function mustShowSolution(): bool
    {
        return $this->showSolution;
    }

    /**
     * Set if the candidate is provided a way to enter the 'solution' state.
     *
     * @param bool $showSolution true if he is provided, false if not.
     * @throws InvalidArgumentException If $showSolution is not a boolean.
     */
    public function setShowSolution($showSolution): void
    {
        if (is_bool($showSolution)) {
            $this->showSolution = $showSolution;
        } else {
            $msg = "ShowSolution must be a boolean, '" . gettype($showSolution) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Is the candidate allowed to communicate comments?
     *
     * @return bool true if allowed, false if not.
     */
    public function doesAllowComment(): bool
    {
        return $this->allowComment;
    }

    /**
     * Set if the candidate is allowed to communicate comments during the session.
     *
     * @param bool $allowComment true if allowed, false if not.
     * @throws InvalidArgumentException If $allowComment is not a boolean.
     */
    public function setAllowComment($allowComment): void
    {
        if (is_bool($allowComment)) {
            $this->allowComment = $allowComment;
        } else {
            $msg = "AllowComment must be a boolean, '" . gettype($allowComment) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Is the candidate allowed to skip items?
     *
     * Know whether the Delivery Engine allow the candidate to skip items.
     *
     * @return bool true if allowed, false if not.
     */
    public function doesAllowSkipping(): bool
    {
        return $this->allowSkipping;
    }

    /**
     * Set if the candidate is allowed to skip items.
     *
     * Set whether the Delivery Engine allows the candidate to skip items.
     *
     * @param bool $allowSkipping true if allowed, false otherwise.
     * @throws InvalidArgumentException If $allowSkipping is not a valid boolean.
     */
    public function setAllowSkipping($allowSkipping): void
    {
        if (is_bool($allowSkipping)) {
            $this->allowSkipping = $allowSkipping;
        } else {
            $msg = "AllowSkipping must be a boolean, '" . gettype($allowSkipping) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Must validate responses?
     *
     * @return bool true if responses must be validated, false if not.
     */
    public function mustValidateResponses(): bool
    {
        return $this->validateResponses;
    }

    /**
     * Is a default Item Session Control?
     *
     * Whether the values held by the ItemSessionControl are the default ones.
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->getMaxAttempts() === 1 &&
            $this->mustShowFeedback() === false &&
            $this->doesAllowReview() === true &&
            $this->mustShowSolution() === false &&
            $this->doesAllowComment() === false &&
            $this->mustValidateResponses() === false &&
            $this->doesAllowSkipping() === true;
    }

    /**
     * Set if the responses must be validated.
     *
     * Set whether responses must be validated by the Delivery Engine.
     *
     * @param bool $validateResponses true if responses must be validated, false if not.
     * @throws InvalidArgumentException If $validateResponses is not a boolean.
     */
    public function setValidateResponses($validateResponses): void
    {
        if (is_bool($validateResponses)) {
            $this->validateResponses = $validateResponses;
        } else {
            $msg = "ValidateResponses must be a boolean value, '" . gettype($validateResponses) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'itemSessionControl';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return new QtiComponentCollection();
    }
}
