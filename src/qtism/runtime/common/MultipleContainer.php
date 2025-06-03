<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2013-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\runtime\common;

use InvalidArgumentException;
use qtism\common\datatypes\QtiDatatype;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ValueCollection;
use qtism\runtime\common\Utils as RuntimeUtils;

/**
 * A more concrete type of Container, which has cardinality qti:multiple
 * and drawn from the same value set. They can contain the same value
 * multiple times and the order is not important. In other words,
 * [A,B,C] equals [C,A,B].
 */
class MultipleContainer extends Container implements QtiDatatype
{
    /**
     * The baseType determines the value set of the accepted values of
     * this container.
     *
     * @var int
     */
    private $baseType = -1;

    /**
     * Create a new MultipleContainer object.
     *
     * @param int $baseType A value from the BaseType enumeration.
     * @param array $array An array of data to insert in the container.
     * @throws InvalidArgumentException If $baseType is not a value from the BaseType enumeration or if data in $array are not compliant with the given $baseType.
     */
    public function __construct($baseType, array $array = [])
    {
        $this->setBaseType($baseType);
        parent::__construct($array);
    }

    /**
     * Set the baseType of the values that will be held by the
     * container.
     *
     * @param int $baseType A value from the BaseType enumeration.
     * @throws InvalidArgumentException If $baseType is not a value from the BaseType enumeration.
     */
    protected function setBaseType($baseType): void
    {
        if (in_array($baseType, BaseType::asArray(), true)) {
            $this->baseType = $baseType;
        } else {
            $msg = 'The baseType argument must be a value from the BaseType enumeration.';
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the baseType of the container.
     *
     * @return int A value from the BaseType enumeration or -1.
     */
    public function getBaseType(): int
    {
        return $this->baseType;
    }

    /**
     * @param mixed $value
     */
    protected function checkType($value): void
    {
        parent::checkType($value);

        if (!Utils::isBaseTypeCompliant($this->getBaseType(), $value)) {
            Utils::throwBaseTypeTypingError($this->getBaseType(), $value);
        }
    }

    /**
     * Create a MultipleContainer object from a Data Model ValueCollection object.
     *
     * @param ValueCollection $valueCollection A collection of qtism\data\state\Value objects.
     * @param int $baseType A value from the BaseType enumeration.
     * @return MultipleContainer A MultipleContainer object populated with the values found in $valueCollection.
     * @throws InvalidArgumentException If a value from $valueCollection is not compliant with the QTI Runtime Model or the container type.
     */
    public static function createFromDataModel(ValueCollection $valueCollection, $baseType = BaseType::INTEGER): MultipleContainer
    {
        $container = new static($baseType);
        foreach ($valueCollection as $value) {
            $container[] = RuntimeUtils::valueToRuntime($value->getValue(), $value->getBaseType());
        }

        return $container;
    }

    /**
     * @return array
     */
    protected function getToStringBounds(): array
    {
        return ['[', ']'];
    }

    /**
     * @return int
     */
    public function getCardinality(): int
    {
        return Cardinality::MULTIPLE;
    }
}
