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
 * The TestResultsSubmission enumeration represents the different configuration
 * values that can be applied for Test Results Submission.
 */
class TestResultsSubmission implements Enumeration
{
    public const END = 0;

    public const OUTCOME_PROCESSING = 1;

    /**
     * @return array
     */
    public static function asArray(): array
    {
        return [
            'END' => self::END,
            'OUTCOME_PROCESSING' => self::OUTCOME_PROCESSING,
        ];
    }

    /**
     * @param false|int $name
     * @return bool|int
     */
    public static function getConstantByName($name)
    {
        switch (strtolower((string)$name)) {
            case 'end':
                return self::END;
                break;

            case 'outcomeprocessing':
                return self::OUTCOME_PROCESSING;
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
            case self::END:
                return 'end';
                break;

            case self::OUTCOME_PROCESSING:
                return 'outcomeProcessing';
                break;

            default:
                return false;
                break;
        }
    }
}
