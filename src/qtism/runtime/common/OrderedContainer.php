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

use qtism\common\Comparable;
use qtism\common\datatypes\QtiDatatype;
use qtism\common\enums\Cardinality;

/**
 * A more concrete type of Container, which has cardinality qti:ordered
 * and drawn from the same value set. In this type of container, the order
 * is important.
 */
class OrderedContainer extends MultipleContainer implements QtiDatatype
{
    /**
     * @param mixed $obj
     * @return bool
     */
    public function equals($obj): bool
    {
        $countA = count($this);

        if (is_object($obj) && $obj instanceof self && $countA === count($obj)) {
            for ($i = 0; $i < $countA; $i++) {
                $objA = $this[$i];
                $objB = $obj[$i];

                if (is_object($objA) && $obj instanceof Comparable) {
                    if ($objA->equals($objB) === false) {
                        return false;
                    }
                } elseif (is_object($objB) && $obj instanceof Comparable) {
                    if ($objB->equals($objA) === false) {
                        return false;
                    }
                } elseif ($objA !== $objB) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public function getCardinality(): int
    {
        return Cardinality::ORDERED;
    }

    /**
     * @return array
     */
    protected function getToStringBounds(): array
    {
        return ['<', '>'];
    }
}
