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
use qtism\common\collections\IdentifierCollection;

/**
 * From IMS QTI:
 *
 * The choices that are used to fill the gaps in a gapMatchInteraction
 * are either simple runs of text or single image objects, both derived
 * from gapChoice.
 */
abstract class GapChoice extends Choice implements AssociableChoice
{
    /**
     * From IMS QTI:
     *
     * The maximum number of choices this choice may be associated with.
     * If matchMax is 0 there is no restriction.
     *
     * @var int
     * @qtism-bean-property
     */
    private $matchMax;

    /**
     * From IMS QTI:
     *
     * The minimum number of gaps this choice must be associated with to
     * form a valid response. If matchMin is 0 then the candidate is not
     * required to associate this choice with any gaps at all. matchMin
     * must be less than or equal to the limit imposed by matchMax.
     *
     * @var int
     * @qtism-bean-property
     */
    private $matchMin = 0;

    /**
     * From IMS QTI:
     *
     * A set of choices that this choice may be associated with, all others are
     * excluded. If no matchGroup is given, or if it is empty, then all other
     * choices may be associated with this one subject to their own matching
     * constraints.
     *
     * @var IdentifierCollection
     * @qtism-bean-property
     */
    private $matchGroup;

    /**
     * Create a new GapChoice object.
     *
     * @param string $identifier The identifier of the GapChoice.
     * @param int $matchMax The matchMax attribute of the GapChoice.
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException
     */
    public function __construct($identifier, $matchMax, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($identifier, $id, $class, $lang, $label);
        $this->setMatchMax($matchMax);
        $this->setMatchMin(0);
        $this->setMatchGroup(new IdentifierCollection());
    }

    /**
     * Set the matchMax attribute of the gapChoice.
     *
     * @param int $matchMax A postive (>= 0) integer.
     * @throws InvalidArgumentException If $matchMax is not a positive integer.
     */
    public function setMatchMax($matchMax): void
    {
        if (is_int($matchMax) && $matchMax >= 0) {
            $this->matchMax = $matchMax;
        } else {
            $msg = "The 'matchMax' argument must be a positive integer, '" . gettype($matchMax) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the matchMax attribute of the gapChoice.
     *
     * @return int A positive (>= 0) integer.
     */
    public function getMatchMax(): int
    {
        return $this->matchMax;
    }

    /**
     * Set the matchMin attribute of the gapChoice.
     *
     * @param int $matchMin A positive (>= 0) integer.
     * @throws InvalidArgumentException If $matchMin is not a positive integer.
     */
    public function setMatchMin($matchMin): void
    {
        if (is_int($matchMin) && $matchMin >= 0) {
            $this->matchMin = $matchMin;
        } else {
            $msg = "The 'matchMin' argument must be a positive integer, '" . gettype($matchMin) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the matchMin attribute of the gapChoice.
     *
     * @return int A positive (>= 0) integer.
     */
    public function getMatchMin(): int
    {
        return $this->matchMin;
    }

    /**
     * @param IdentifierCollection $matchGroup
     */
    public function setMatchGroup(IdentifierCollection $matchGroup): void
    {
        $this->matchGroup = $matchGroup;
    }

    /**
     * @return IdentifierCollection
     */
    public function getMatchGroup(): IdentifierCollection
    {
        return $this->matchGroup;
    }
}
