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
 * The QTI roudingMode enumeration.
 */
class RoundingMode implements Enumeration
{
    public const SIGNIFICANT_FIGURES = 0;

    public const DECIMAL_PLACES = 1;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            'SIGNIFICANT_FIGURES' => self::SIGNIFICANT_FIGURES,
            'DECIMAL_PLACES' => self::DECIMAL_PLACES,
        ];
    }

    /**
     * @param false|int $name
     * @return bool|int
     */
    public static function getConstantByName($name)
    {
        switch (strtolower((string)$name)) {
            case 'significantfigures':
                return self::SIGNIFICANT_FIGURES;
                break;

            case 'decimalplaces':
                return self::DECIMAL_PLACES;
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
            case self::SIGNIFICANT_FIGURES:
                return 'significantFigures';
                break;

            case self::DECIMAL_PLACES:
                return 'decimalPlaces';
                break;

            default:
                return false;
                break;
        }
    }
}
