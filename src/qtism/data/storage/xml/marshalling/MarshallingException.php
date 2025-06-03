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

namespace qtism\data\storage\xml\marshalling;

use Exception;
use qtism\common\QtiSdkPackageContentException;
use qtism\data\QtiComponent;

/**
 * Exception to be thrown when an error occurs during the marshalling process
 * of a QtiComponent.
 */
class MarshallingException extends Exception implements QtiSdkPackageContentException
{
    /**
     * A QtiComponent object that caused the exception to be thrown.
     *
     * @var QtiComponent
     */
    private $component;

    /**
     * Create a new instance of MarshallingException.
     *
     * @param string $message A human-readable message which describes the exception.
     * @param QtiComponent $component A QtiComponent object that caused the exception to be thrown.
     * @param Exception $previous A previous exception that caused the exception to be thrown.
     */
    public function __construct($message, QtiComponent $component, $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->setComponent($component);
    }

    /**
     * Get the QtiComponent object that caused the exception to be thrown.
     *
     * @return QtiComponent A QtiComponent object.
     */
    public function getComponent(): QtiComponent
    {
        return $this->component;
    }

    /**
     * Set the QTIcomponent object that caused the exception to be thrown.
     *
     * @param QtiComponent $component A QTI Component object.
     */
    protected function setComponent(QtiComponent $component): void
    {
        $this->component = $component;
    }
}
