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

namespace qtism\runtime\storage\common;

use Exception;
use qtism\common\QtiSdkPackageContentException;

/**
 * The StorageException class represents exceptions that AssessmentTestSession
 * Storage Services encounter an error.
 */
class StorageException extends Exception implements QtiSdkPackageContentException
{
    /**
     * The error code to be used when the nature of the error is unknown.
     * Should be used in absolute necessity. Otherwise, use the appropriate
     * error code.
     *
     * @var int
     */
    public const UNKNOWN = 0;

    /**
     * Error code to be used when an error occurs while
     * instantiating an AssessmentTestSession.
     *
     * @var int
     */
    public const INSTANTIATION = 1;

    /**
     * Error code to use when an error occurs while
     * persisting an AssessmentTestSession.
     *
     * @var int
     */
    public const PERSISTENCE = 2;

    /**
     * Error code to use when an error occurs while
     * retrieving an AssessmentTestSession.
     *
     * @var int
     */
    public const RETRIEVAL = 3;

    /**
     * Error code to use when an error occurs while deleting an AssessmentTestSession.
     *
     * @var int
     */
    public const DELETION = 4;
}
