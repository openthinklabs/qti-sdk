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
 * Copyright (c) 2014-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\common\datatypes;

use InvalidArgumentException;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;

/**
 * Represents the Boolean QTI Datatype.
 */
class QtiBoolean extends QtiScalar
{
    /**
     * Check whether the intrinsic $value is a PHP boolean.
     *
     * @param mixed $value A given value.
     * @throws InvalidArgumentException
     */
    protected function checkType($value): void
    {
        if (is_bool($value) !== true) {
            $msg = 'The Boolean Datatype only accepts to store boolean values.';
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the baseType of the Boolean value. This method
     * systematically returns BaseType::BOOLEAN.
     *
     * @return int A value from the BaseType enumeration.
     */
    public function getBaseType(): int
    {
        return BaseType::BOOLEAN;
    }

    /**
     * Get the cardinality of the Boolean value. This method
     * systematically returns Cardinality::SINGLE.
     *
     * @return int A value from the BaseType enumeration.
     */
    public function getCardinality(): int
    {
        return Cardinality::SINGLE;
    }

    /**
     * "true" or "false" depending on the intrinsic value of the Boolean
     * object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return ($this->getValue() === true) ? 'true' : 'false';
    }
}
