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
 * Copyright (c) 2018-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Moyon Camille <camille@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\results;

use InvalidArgumentException;
use qtism\data\QtiComponentCollection;

/**
 * Class TestResultCollection
 */
class TestResultCollection extends QtiComponentCollection
{
    /**
     * Check if a given $value is an instance of ItemResult.
     *
     * @param mixed $value
     * @throws InvalidArgumentException If the given $value is not an instance of ItemResult.
     */
    protected function checkType($value): void
    {
        if (!$value instanceof TestResult) {
            $msg = "TestResultCollection only accepts to store TestResult objects, '" . gettype($value) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }
}
