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

use qtism\common\enums\BaseType;

/**
 * An extension of the BaseType enumeration.
 *
 * This enumeration introduces 2 new constants about QTI BaseTypes.
 *
 * * ANY: Any kind of BaseType.
 * * SAME: Same BaseTypes.
 */
class OperatorBaseType extends BaseType
{
    /**
     * Express that the operands can have any BaseType from the BaseType enumeration and
     * can be different.
     *
     * @var int
     */
    public const ANY = 13;

    /**
     * Express that all the operands must have the same
     * baseType.
     *
     * @var int
     */
    public const SAME = 14;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        $values = BaseType::asArray();
        $values['ANY'] = self::ANY;
        $values['SAME'] = self::SAME;

        return $values;
    }
}
