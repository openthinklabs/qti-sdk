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
 * The MathFunctions enumeration.
 *
 * @see \qtism\data\expressions\operators\MathOperator
 */
class MathFunctions implements Enumeration
{
    public const SIN = 0;

    public const COS = 1;

    public const TAN = 2;

    public const SEC = 3;

    public const CSC = 4;

    public const COT = 5;

    public const ASIN = 6;

    public const ACOS = 7;

    public const ATAN = 8;

    public const ATAN2 = 9;

    public const ASEC = 10;

    public const ACSC = 11;

    public const ACOT = 12;

    public const SINH = 13;

    public const COSH = 14;

    public const TANH = 15;

    public const SECH = 16;

    public const CSCH = 17;

    public const COTH = 18;

    public const LOG = 19;

    public const LN = 20;

    public const EXP = 21;

    public const ABS = 22;

    public const SIGNUM = 23;

    public const FLOOR = 24;

    public const CEIL = 25;

    public const TO_DEGREES = 26;

    public const TO_RADIANS = 27;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            'SIN' => self::SIN,
            'COS' => self::COS,
            'TAN' => self::TAN,
            'SEC' => self::SEC,
            'CSC' => self::CSC,
            'COT' => self::COT,
            'ASIN' => self::ASIN,
            'ACOS' => self::ACOS,
            'ATAN' => self::ATAN,
            'ATAN2' => self::ATAN2,
            'ASEC' => self::ASEC,
            'ACSC' => self::ACSC,
            'ACOT' => self::ACOT,
            'SINH' => self::SINH,
            'COSH' => self::COSH,
            'TANH' => self::TANH,
            'SECH' => self::SECH,
            'CSCH' => self::CSCH,
            'COTH' => self::COTH,
            'LOG' => self::LOG,
            'LN' => self::LN,
            'EXP' => self::EXP,
            'ABS' => self::ABS,
            'SIGNUM' => self::SIGNUM,
            'FLOOR' => self::FLOOR,
            'CEIL' => self::CEIL,
            'TO_DEGREES' => self::TO_DEGREES,
            'TO_RADIANS' => self::TO_RADIANS,
        ];
    }

    /**
     * @param false|int $name
     *
     * @return bool|int
     */
    public static function getConstantByName($name)
    {
        switch (strtolower((string)$name)) {
            case 'sin':
                return self::SIN;
                break;

            case 'cos':
                return self::COS;
                break;

            case 'tan':
                return self::TAN;
                break;

            case 'sec':
                return self::SEC;
                break;

            case 'csc':
                return self::CSC;
                break;

            case 'cot':
                return self::COT;
                break;

            case 'asin':
                return self::ASIN;
                break;

            case 'acos':
                return self::ACOS;
                break;

            case 'atan':
                return self::ATAN;
                break;

            case 'atan2':
                return self::ATAN2;
                break;

            case 'asec':
                return self::ASEC;
                break;

            case 'acsc':
                return self::ACSC;
                break;

            case 'acot':
                return self::ACOT;
                break;

            case 'sinh':
                return self::SINH;
                break;

            case 'cosh':
                return self::COSH;
                break;

            case 'tanh':
                return self::TANH;
                break;

            case 'sech':
                return self::SECH;
                break;

            case 'csch':
                return self::CSCH;
                break;

            case 'coth':
                return self::COTH;
                break;

            case 'log':
                return self::LOG;
                break;

            case 'ln':
                return self::LN;
                break;

            case 'exp':
                return self::EXP;
                break;

            case 'abs':
                return self::ABS;
                break;

            case 'signum':
                return self::SIGNUM;
                break;

            case 'floor':
                return self::FLOOR;
                break;

            case 'ceil':
                return self::CEIL;
                break;

            case 'todegrees':
                return self::TO_DEGREES;
                break;

            case 'toradians':
                return self::TO_RADIANS;
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
            case self::SIN:
                return 'sin';
                break;

            case self::COS:
                return 'cos';
                break;

            case self::TAN:
                return 'tan';
                break;

            case self::SEC:
                return 'sec';
                break;

            case self::CSC:
                return 'csc';
                break;

            case self::COT:
                return 'cot';
                break;

            case self::ASIN:
                return 'asin';
                break;

            case self::ACOS:
                return 'acos';
                break;

            case self::ATAN:
                return 'atan';
                break;

            case self::ATAN2:
                return 'atan2';
                break;

            case self::ASEC:
                return 'asec';
                break;

            case self::ACSC:
                return 'acsc';
                break;

            case self::ACOT:
                return 'acot';
                break;

            case self::SINH:
                return 'sinh';
                break;

            case self::COSH:
                return 'cosh';
                break;

            case self::TANH:
                return 'tanh';
                break;

            case self::SECH:
                return 'sech';
                break;

            case self::CSCH:
                return 'csch';
                break;

            case self::COTH:
                return 'coth';
                break;

            case self::LOG:
                return 'log';
                break;

            case self::LN:
                return 'ln';
                break;

            case self::EXP:
                return 'exp';
                break;

            case self::ABS:
                return 'abs';
                break;

            case self::SIGNUM:
                return 'signum';
                break;

            case self::FLOOR:
                return 'floor';
                break;

            case self::CEIL:
                return 'ceil';
                break;

            case self::TO_DEGREES:
                return 'toDegrees';
                break;

            case self::TO_RADIANS:
                return 'toRadians';
                break;

            default:
                return false;
                break;
        }
    }
}
