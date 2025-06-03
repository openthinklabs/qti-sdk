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

use qtism\common\storage\StreamAccessException;
use qtism\data\storage\php\PhpArgument;
use qtism\data\storage\php\PhpArgumentCollection;
use qtism\data\storage\php\Utils as PhpUtils;

/**
 * Implements the logic of marshalling PHP arrays into
 * PHP source code.
 */
class PhpArrayMarshaller extends PhpMarshaller
{
    /**
     * Marshall an array into PHP source code.
     *
     * @throws PhpMarshallingException If something wrong happens during marshalling.
     * @throws StreamAccessException
     */
    public function marshall(): void
    {
        $ctx = $this->getContext();
        $access = $ctx->getStreamAccess();
        $array = $this->getToMarshall();
        $args = new PhpArgumentCollection();

        foreach ($array as $a) {
            if (PhpUtils::isScalar($a) === false) {
                $msg = 'The PhpArrayMarshaller class only deals with PHP scalar values, object or resource given.';
                throw new PhpMarshallingException($msg);
            }

            $args[] = new PhpArgument($a);
        }

        $arrayVarName = $ctx->generateVariableName($array);
        $access->writeVariable($arrayVarName);
        $access->writeEquals($ctx->mustFormatOutput());
        $access->writeFunctionCall('array', $args);
        $access->writeSemicolon($ctx->mustFormatOutput());

        $ctx->pushOnVariableStack($arrayVarName);
    }

    /**
     * Whether the $toMarshall value is marshallable by this implementation which
     * only supports arrays to be marshalled.
     *
     * @param mixed $toMarshall
     *
     * @return bool
     */
    protected function isMarshallable($toMarshall): bool
    {
        return is_array($toMarshall);
    }
}
