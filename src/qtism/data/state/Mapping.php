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

namespace qtism\data\state;

use InvalidArgumentException;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

/**
 * From IMS QTI:
 *
 * A special class used to create a mapping from a source set of any
 * baseType (except file and duration) to a single float. Note that
 * mappings from values of base type float should be avoided due to the
 * difficulty of matching floating point values, see the match operator
 * for more details. When mapping containers the result is the sum of
 * the mapped values from the target set. See mapResponse for details.
 */
class Mapping extends QtiComponent
{
    /**
     * From IMS QTI:
     *
     * The lower bound for the result of mapping a container. If unspecified
     * there is no lower-bound.
     *
     * @var float|bool
     * @qtism-bean-property
     */
    private $lowerBound = false;

    /**
     * From IMS QTI:
     *
     * The upper bound for the result of mapping a container. If unspecified
     * there is no upper-bound.
     *
     * @var float|bool
     * @qtism-bean-property
     */
    private $upperBound = false;

    /**
     * From IMS QTI:
     *
     * The default value from the target set to be used when no explicit
     * mapping for a source value is given.
     *
     * @var float
     * @qtism-bean-property
     */
    private $defaultValue = 0.0;

    /**
     * From IMS QTI:
     *
     * The map is defined by a set of mapEntries, each of which maps a
     * single value from the source set onto a single float.
     *
     * @var MapEntryCollection
     * @qtism-bean-property
     */
    private $mapEntries;

    /**
     * Create a new Mapping object.
     *
     * @param MapEntryCollection $mapEntries A collection of MapEntry which compose the Mapping object to be created.
     * @param float|bool $lowerBound A lower bound. Give false if not specified.
     * @param float|bool $upperBound An upper bound. Give false if not specified.
     * @param int|float $defaultValue A default value. Default is 0.
     * @throws InvalidArgumentException If $defaultValue is not a float, if $lowerBound or $upperBound are not floats nor false, If $mapEntries is an empty collection.
     */
    public function __construct(MapEntryCollection $mapEntries, $defaultValue = 0.0, $lowerBound = false, $upperBound = false)
    {
        $this->setLowerBound($lowerBound);
        $this->setUpperBound($upperBound);
        $this->setDefaultValue($defaultValue);
        $this->setMapEntries($mapEntries);
    }

    /**
     * Set the lower bound.
     *
     * @param bool|float $lowerBound A float or false if not lower bound.
     * @throws InvalidArgumentException If $lowerBound is not a float nor false.
     */
    public function setLowerBound($lowerBound): void
    {
        if (is_float($lowerBound) || (is_bool($lowerBound) && $lowerBound === false)) {
            $this->lowerBound = $lowerBound;
        } else {
            $msg = "The 'lowerBound' attribute must be a float or false, '" . gettype($lowerBound) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the lower bound.
     *
     * @return bool|float A float value or false if not specified.
     */
    public function getLowerBound()
    {
        return $this->lowerBound;
    }

    /**
     * Whether the Mapping has a lower bound.
     *
     * @return bool
     */
    public function hasLowerBound(): bool
    {
        return $this->getLowerBound() !== false;
    }

    /**
     * Set the upper bound.
     *
     * @param bool|float $upperBound A float value or false if not specified.
     * @throws InvalidArgumentException If $upperBound is not a float nor false.
     */
    public function setUpperBound($upperBound): void
    {
        if (is_float($upperBound) || (is_bool($upperBound) && $upperBound === false)) {
            $this->upperBound = $upperBound;
        } else {
            $msg = "The 'upperBound' argument must be a float or false, '" . gettype($upperBound) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the upper bound.
     *
     * @return float|bool A float value or false if not specified.
     */
    public function getUpperBound()
    {
        return $this->upperBound;
    }

    /**
     * Whether the Mapping has an upper bound.
     *
     * @return bool
     */
    public function hasUpperBound(): bool
    {
        return $this->getUpperBound() !== false;
    }

    /**
     * Set the default value of the Mapping.
     *
     * @param float $defaultValue A float value.
     * @throws InvalidArgumentException If $defaultValue is not a float value.
     */
    public function setDefaultValue($defaultValue): void
    {
        if (is_numeric($defaultValue)) {
            $this->defaultValue = $defaultValue;
        } else {
            $msg = "The 'defaultValue' argument must be a numeric value, '" . gettype($defaultValue) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the default value of the Mapping.
     *
     * @return float A default value as a float.
     */
    public function getDefaultValue(): float
    {
        return $this->defaultValue;
    }

    /**
     * Set the collection of MapEntry objects which compose the Mapping.
     *
     * @param MapEntryCollection $mapEntries A collection of MapEntry objects with at least one item.
     * @throws InvalidArgumentException If $mapEnties is an empty collection.
     */
    public function setMapEntries(MapEntryCollection $mapEntries): void
    {
        if (count($mapEntries) > 0) {
            $this->mapEntries = $mapEntries;
        } else {
            $msg = 'A Mapping object must contain at least one MapEntry object, none given.';
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the collection of MapEntry objects which compose the Mapping.
     *
     * @return MapEntryCollection A collection of MapEntry objects.
     */
    public function getMapEntries(): MapEntryCollection
    {
        return $this->mapEntries;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'mapping';
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return new QtiComponentCollection($this->getMapEntries()->getArrayCopy());
    }
}
