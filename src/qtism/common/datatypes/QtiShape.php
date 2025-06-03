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

namespace qtism\common\datatypes;

use qtism\common\enums\Enumeration;

/**
 * From IMS QTI:
 *
 * A value of a shape is alway accompanied by coordinates (see coords and an associated
 * image which provides a context for interpreting them.
 */
class QtiShape implements Enumeration
{
    /**
     * Note: Corresponds to QTI shape::default. Unfortunately, 'default' is a reserved
     * token in PHP.
     *
     * From IMS QTI:
     *
     * The default shape refers to the entire area of the associated image.
     *
     * @var int
     */
    public const DEF = 0;

    /**
     * From IMS QTI:
     *
     * A rectangular region.
     *
     * @var int
     */
    public const RECT = 1;

    /**
     * From IMS QTI:
     *
     * A circular region.
     *
     * @var int
     */
    public const CIRCLE = 2;

    /**
     * From IMS QTI:
     *
     * An arbitrary polygonal region.
     *
     * @var int
     */
    public const POLY = 3;

    /**
     * From IMS QTI:
     *
     * This value is deprecated, but is included for compatibility with version
     * of 1 of the QTI specification. Systems should use circle or poly shapes instead.
     *
     * @var int
     * @deprecated
     */
    public const ELLIPSE = 4;

    /**
     * Get the enumeration as an array.
     *
     * @return array An associative array.
     */
    public static function asArray(): array
    {
        return [
            'DEF' => self::DEF,
            'RECT' => self::RECT,
            'CIRCLE' => self::CIRCLE,
            'POLY' => self::POLY,
            'ELLIPSE' => self::ELLIPSE,
        ];
    }

    /**
     * Get the constant value associated with $name.
     *
     * @param string $name
     * @return int|bool The constant value associated with the name or false if not found.
     */
    public static function getConstantByName($name)
    {
        switch (strtolower($name)) {
            case 'default':
                return self::DEF;
                break;

            case 'rect':
                return self::RECT;
                break;

            case 'circle':
                return self::CIRCLE;
                break;

            case 'poly':
                return self::POLY;
                break;

            case 'ellipse':
                return self::ELLIPSE;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Get the name associated with $constant.
     *
     * @param int $constant
     * @return string|bool The name or false if not found.
     */
    public static function getNameByConstant($constant)
    {
        switch ($constant) {
            case self::DEF:
                return 'default';
                break;

            case self::RECT:
                return 'rect';
                break;

            case self::CIRCLE:
                return 'circle';
                break;

            case self::POLY:
                return 'poly';
                break;

            case self::ELLIPSE:
                return 'ellipse';
                break;

            default:
                return false;
                break;
        }
    }
}
