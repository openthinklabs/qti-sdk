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

use qtism\common\enums\Enumeration;

/**
 * The QTI toleranceMode enumeration.
 */
class ToleranceMode implements Enumeration
{
    public const EXACT = 0;

    public const ABSOLUTE = 1;

    public const RELATIVE = 2;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            'EXACT' => self::EXACT,
            'ABSOLUTE' => self::ABSOLUTE,
            'RELATIVE' => self::RELATIVE,
        ];
    }

    /**
     * @param false|int $name
     * @return bool|int
     */
    public static function getConstantByName($name)
    {
        switch (strtolower((string)$name)) {
            case 'exact':
                return self::EXACT;
                break;

            case 'absolute':
                return self::ABSOLUTE;
                break;

            case 'relative':
                return self::RELATIVE;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * @param false|string $constant
     * @return bool|string
     */
    public static function getNameByConstant($constant)
    {
        switch ($constant) {
            case self::EXACT:
                return 'exact';
                break;

            case self::ABSOLUTE:
                return 'absolute';
                break;

            case self::RELATIVE:
                return 'relative';
                break;

            default:
                return false;
                break;
        }
    }
}
