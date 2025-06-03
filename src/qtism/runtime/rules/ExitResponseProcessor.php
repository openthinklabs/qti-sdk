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

namespace qtism\runtime\rules;

use qtism\data\rules\ExitResponse;

/**
 * From IMS QTI:
 *
 * The exit response rule terminates response processing immediately (for this
 * invocation).
 */
class ExitResponseProcessor extends RuleProcessor
{
    /**
     * Process the ExitResponse rule. It simply throws a RuleProcessingException with
     * the special code RuleProcessingException::EXIT_RESPONSE to simulate the
     * response processing termination.
     *
     * @throws RuleProcessingException with code = RuleProcessingException::EXIT_RESPONSE In any case.
     */
    public function process(): void
    {
        $msg = 'Termination of Response Processing.';
        throw new RuleProcessingException($msg, $this, RuleProcessingException::EXIT_RESPONSE);
    }

    /**
     * @return string
     */
    protected function getRuleType(): string
    {
        return ExitResponse::class;
    }
}
