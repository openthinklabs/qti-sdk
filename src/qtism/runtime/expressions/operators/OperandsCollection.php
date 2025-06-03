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

namespace qtism\runtime\expressions\operators;

use InvalidArgumentException;
use qtism\common\collections\AbstractCollection;
use qtism\common\collections\Stack;
use qtism\common\datatypes\QtiBoolean;
use qtism\common\datatypes\QtiDuration;
use qtism\common\datatypes\QtiFloat;
use qtism\common\datatypes\QtiInteger;
use qtism\common\datatypes\QtiPoint;
use qtism\common\datatypes\QtiString;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\runtime\common\Container;
use qtism\runtime\common\MultipleContainer;
use qtism\runtime\common\OrderedContainer;
use qtism\runtime\common\RecordContainer;
use qtism\runtime\common\Utils as RuntimeUtils;

/**
 * A collection that aims at storing operands (QTI Runtime compliant values).
 */
class OperandsCollection extends AbstractCollection implements Stack
{
    /**
     * Check if $value is a QTI Runtime compliant value.
     *
     * @param mixed $value
     * @throws InvalidArgumentException If $value is not a QTI Runtime compliant value.
     */
    protected function checkType($value): void
    {
        if (!RuntimeUtils::isRuntimeCompliant($value)) {
            $value = (is_object($value)) ? get_class($value) : $value;
            $msg = "The OperandsCollection only accepts QTI Runtime compliant values, '" . $value . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether the collection contains a QTI Runtime compliant value which is
     * considered to be NULL.
     *
     * * If the collection of operands is empty, true is returned.
     * * If the collection of operands contains null, an empty container, or an empty string, true is returned.
     * * In any other case, false is returned.
     *
     * @return bool
     */
    public function containsNull(): bool
    {
        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if ($v instanceof Container && $v->isNull()) {
                return true;
            } elseif (($v instanceof QtiString && $v->getValue() === '') || $v === null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the collection is exclusively composed of numeric values: primitive
     * or Containers. Please note that:
     *
     * * A primitive with the NULL value is not considered numeric.
     * * Only float and integer primitive are considered numeric.
     * * An empty Multiple/OrderedContainer with baseType integer or float is not considered numeric.
     * * If the collection contains a container with cardinality RECORD, it is not considered exclusively numeric.
     * * If the the current OperandsCollection is empty, false is returned.
     *
     * @return bool.
     */
    public function exclusivelyNumeric()
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || ($v->getBaseType() !== BaseType::FLOAT && $v->getBaseType() !== BaseType::INTEGER))) {
                return false;
            } elseif (!$v instanceof QtiInteger && !$v instanceof QtiFloat && !$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains exclusively boolean values or containers.
     *
     * * If the collection of operands is empty, false is returned.
     * * If the collection of operands contains a NULL value or a NULL container, false is returned.
     * * If the collection of operands contains a value or container which is not boolean, false is returned.
     * * If the collection of operands contains a RECORD container, false is returned, because records are not typed.
     *
     * @return bool
     */
    public function exclusivelyBoolean(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || $v->getBaseType() !== BaseType::BOOLEAN)) {
                return false;
            } elseif (!$v instanceof QtiBoolean && !$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains exclusively single cardinality values. If the container
     * is empty or contains a null value, false is returned.
     *
     * @return bool
     */
    public function exclusivelySingle(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if ($v === null || $v instanceof Container) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection is exclusively composed of string values: primitive or Containers.
     * Please note that:
     *
     * * A primitive with the NULL value is not considered as a string.
     * * An empty string is considered to be NULL and then not considered a valid string as per QTI 2.1 specification.
     * * An empty Multiple/OrderedContainer with baseType string is not considered to contain strings.
     * * If the collection contains a container with cardinality RECORD, it is not considered exclusively string.
     * * If the the current OperandsCollection is empty, false is returned.
     *
     * @return bool
     */
    public function exclusivelyString(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || $v->getBaseType() !== BaseType::STRING)) {
                return false;
            } elseif (!$v instanceof MultipleContainer && !$v instanceof OrderedContainer && (!$v instanceof QtiString || $v->getValue() === '')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains only MultipleContainer OR OrderedContainer.
     *
     * @return bool
     */
    public function exclusivelyMultipleOrOrdered(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (!$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection is exclusively composed of integer values: primitive
     * or Containers. Please note that:
     *
     * * A primitive with the NULL value is not considered as an integer.
     * * Only integer primitives and non-NULL Multiple/OrderedContainer objects are considered valid integers.
     * * If the the current OperandsCollection is empty, false is returned.
     *
     * @return bool.
     */
    public function exclusivelyInteger(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || $v->getBaseType() !== BaseType::INTEGER)) {
                return false;
            } elseif (!$v instanceof QtiInteger && !$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains only Single primitive values or MultipleContainer objects.
     *
     * * If the collection of operands is empty, false is returned.
     * * If the collection of operands contains a RecordContainer object, false is returned.
     * * If the collection of operands contains an OrderedContainer object, false is returned.
     * * In any other case, true is returned.
     *
     * @return bool
     */
    public function exclusivelySingleOrMultiple(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];

            if ($v instanceof RecordContainer || $v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains only Single primitive values or OrderedContainer objects.
     *
     * * If the collection of operands is empty, false is returned.
     * * If the collection of operands contains a RecordContainer object, false is returned.
     * * If the collection of operands contains a MultipleContainer object, false is returned.
     * * In any other case, true is returned.
     *
     * @return bool
     */
    public function exclusivelySingleOrOrdered(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];

            if ($v instanceof RecordContainer || ($v instanceof MultipleContainer && $v->getCardinality() === Cardinality::MULTIPLE)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains exclusively RecordContainer objects.
     *
     * * Returns false if the collection of operands is empty.
     * * Returns false if any of the value contained in the collection of operands is not a RecordContainer object.
     * * In any other case, returns true;
     *
     * @return bool
     */
    public function exclusivelyRecord(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (!$v instanceof RecordContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains exclusively OrderedContainer objects.
     *
     * * Returns false if the collection of operands is empty.
     * * Returns false if any of the value contained in the collection of operands is not an OrderedContainer object.
     * * Returns true in any other case.
     *
     * @return bool
     */
    public function exclusivelyOrdered(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (!$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection contains anything but a RecordContainer object.
     *
     * @return bool
     */
    public function anythingButRecord(): bool
    {
        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if ($v instanceof RecordContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection is composed of values with the same baseType.
     *
     * * If any of the values has not the same baseType than other values in the collection, false is returned.
     * * If the OperandsCollection is an empty collection, false is returned.
     * * If the OperandsCollection contains a value considered to be null, false is returned.
     * * If the OperandsCollection is composed exclusively by non-null RecordContainer objects, true is returned.
     *
     * @return bool
     */
    public function sameBaseType(): bool
    {
        $operandsCount = count($this);
        if ($operandsCount > 0 && !$this->containsNull()) {
            // take the first value of the collection as a referer.
            $refValue = $this[0];
            $refType = RuntimeUtils::inferBaseType($refValue);

            for ($i = 1; $i < $operandsCount; $i++) {
                $value = $this[$i];
                $testType = RuntimeUtils::inferBaseType($value);

                if ($testType !== $refType) {
                    return false;
                }
            }

            // In any other case, return true.
            return true;
        } else {
            return false;
        }
    }

    /**
     * Whether the collection is composed of values with the same cardinality. Please
     * note that:
     *
     * * If the OperandsCollection is empty, false is returned.
     * * If the OperandsCollection contains a NULL value or a NULL container (empty), false is returned
     *
     * @return bool
     */
    public function sameCardinality(): bool
    {
        $operandsCount = count($this);
        if ($operandsCount > 0 && !$this->containsNull()) {
            $refType = RuntimeUtils::inferCardinality($this[0]);

            for ($i = 1; $i < $operandsCount; $i++) {
                if ($refType !== RuntimeUtils::inferCardinality($this[$i])) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Whether the collection of operands is composed exclusively of Point objects or Container objects
     * with a point baseType.
     *
     * If the collection of operands contains something other than a Point object or a null Container object
     * with baseType point, false is returned.
     *
     * @return bool
     */
    public function exclusivelyPoint(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || $v->getBaseType() !== BaseType::POINT)) {
                return false;
            } elseif (!$v instanceof QtiPoint && !$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the collection of operands is composed exclusively of Duration objects or Container objects
     * with a duration baseType.
     *
     * If the collection of operands contains something other than a Duration object or a null Container object
     * with baseType duration, false is returned.
     *
     * @return bool
     */
    public function exclusivelyDuration(): bool
    {
        if (count($this) === 0) {
            return false;
        }

        foreach (array_keys($this->getDataPlaceHolder()) as $key) {
            $v = $this[$key];
            if (($v instanceof MultipleContainer || $v instanceof OrderedContainer) && ($v->isNull() || $v->getBaseType() !== BaseType::DURATION)) {
                return false;
            } elseif (!$v instanceof QtiDuration && !$v instanceof MultipleContainer && !$v instanceof OrderedContainer) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     */
    public function push($value): void
    {
        $this->checkType($value);

        $data = &$this->getDataPlaceHolder();
        array_push($data, $value);
    }

    /**
     * @param int $count
     * @return array|OperandsCollection
     */
    public function pop($count = 1)
    {
        $data = &$this->getDataPlaceHolder();
        if ($count === 1) {
            return new self([array_pop($data)]);
        }

        $returnValue = new self();
        $opCount = count($data);
        $i = $opCount - $count;
        while ($i < $opCount) {
            $returnValue[] = $data[$i];
            unset($data[$i]);
            $i++;
        }

        $newData = array_values($data);
        $this->setDataPlaceHolder($newData);

        return $returnValue;
    }
}
