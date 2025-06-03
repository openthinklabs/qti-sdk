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

namespace qtism\runtime\processing;

use InvalidArgumentException;
use qtism\runtime\common\Processable;
use qtism\runtime\common\ProcessingException;

/**
 * An Exception to be thrown in a PrintedVariable processing context.
 */
class PrintedVariableProcessingException extends ProcessingException
{
    /**
     * Set the source of the error.
     *
     * @param Processable $source The source of the error.
     * @throws InvalidArgumentException If $source is not a PrintedVariableEngine object.
     */
    public function setSource(Processable $source): void
    {
        if ($source instanceof PrintedVariableEngine) {
            parent::setSource($source);
        } else {
            $msg = 'PrintedVariableProcessingException::setSource only accepts PrintedVariableEngine objects.';
            throw new InvalidArgumentException($msg);
        }
    }
}
