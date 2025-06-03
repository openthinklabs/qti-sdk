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

namespace qtism\runtime\tests;

use qtism\common\enums\Enumeration;

/**
 * The AssessmentTestSessionState enumeration describe the possible state
 * a test session can get during its lifecycle.
 */
class AssessmentTestSessionState implements Enumeration
{
    public const INITIAL = 0;

    public const INTERACTING = 1;

    public const MODAL_FEEDBACK = 2;

    public const SUSPENDED = 3;

    public const CLOSED = 4;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            'INITIAL' => self::INITIAL,
            'INTERACTING' => self::INTERACTING,
            'MODAL_FEEDBACK' => self::MODAL_FEEDBACK,
            'SUSPENDED' => self::SUSPENDED,
            'CLOSED' => self::CLOSED,
        ];
    }

    /**
     * @param false|int $name
     * @return bool|int
     */
    public static function getConstantByName($name)
    {
        switch (strtolower((string)$name)) {
            case 'initial':
                return self::INITIAL;
                break;

            case 'interacting':
                return self::INTERACTING;
                break;

            case 'modalfeedback':
                return self::MODAL_FEEDBACK;
                break;

            case 'suspended':
                return self::SUSPENDED;
                break;

            case 'closed':
                return self::CLOSED;
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
            case self::INITIAL:
                return 'initial';
                break;

            case self::INTERACTING:
                return 'interacting';
                break;

            case self::MODAL_FEEDBACK:
                return 'modalFeedback';
                break;

            case self::SUSPENDED:
                return 'suspended';
                break;

            case self::CLOSED:
                return 'closed';
                break;

            default:
                return false;
                break;
        }
    }
}
