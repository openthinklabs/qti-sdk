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

namespace qtism\data\storage\php\marshalling;

use Exception;
use qtism\common\QtiSdkPackageContentException;

/**
 * The exception class to use when exception occurs during PHP marshalling time.
 */
class PhpMarshallingException extends Exception implements QtiSdkPackageContentException
{
    /**
     * Error code to use when the error is unknown.
     *
     * @var int
     */
    public const UNKNOWN = 0;

    /**
     * Error code to use when a runtime error occurs
     * at marshalling time.
     *
     * @var int
     */
    public const RUNTIME = 1;

    /**
     * Error code to use while dealing with the stream where
     * the code has to be put into.
     *
     * @var int
     */
    public const STREAM = 2;

    /**
     * Create a new PhpMarshallingException object.
     *
     * @param string $message A human-readable message.
     * @param int $code An error code.
     * @param Exception $previous A previously thrown exception.
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
