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

namespace qtism\data\expressions\operators;

use qtism\common\enums\Cardinality;

/**
 * An extension of the Cardinality enumeration.
 *
 * This enumeration introduces two new constants about QTI cardinalites.
 *
 * * SAME: Values must have the same cardinality.
 * * ANY: Values can have any cardinality.
 */
class OperatorCardinality extends Cardinality
{
    /**
     * Express that all the expressions involved in an operator have
     * the same cardinality.
     *
     * @var int
     */
    public const SAME = 4;

    /**
     * Express that all the expressions involved in an operator may
     * have any cardinality.
     *
     * @var int
     */
    public const ANY = 5;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        $values = Cardinality::asArray();
        $values['SAME'] = self::SAME;
        $values['ANY'] = self::ANY;

        return $values;
    }
}
