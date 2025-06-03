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

namespace qtism\runtime\common;

use qtism\data\QtiComponent;

/**
 * The AbstractEngine class is the common sub-class to all engines.
 */
abstract class AbstractEngine implements Processable
{
    /**
     * The QtiComponent that will be the object of the
     * processing.
     *
     * @var QtiComponent
     */
    private $component;

    /**
     * The StackTrace of the processing, giving some
     * information about the running processing.
     *
     * @var StackTrace
     */
    private $stackTrace;

    /**
     * A Context for the ExpressionEngine.
     *
     * @var State
     */
    private $context;

    /**
     * Create a new AbstractEngine object.
     *
     * @param QtiComponent $component A QtiComponent object to process.
     * @param State $context (optional) The execution context. If no execution context is given, a virgin one will be set up.
     */
    public function __construct(QtiComponent $component, State $context = null)
    {
        $this->setComponent($component);
        $this->setContext($context ?? new State());
        $this->setStackTrace(new StackTrace());
    }

    /**
     * Set the QtiComponent object to be processed by the Engine.
     *
     * @param QtiComponent $component A QtiComponent object.
     */
    public function setComponent(QtiComponent $component): void
    {
        $this->component = $component;
    }

    /**
     * Get the QtiComponent object to be processed by the Engine.
     *
     * @return QtiComponent A QtiComponent object.
     */
    public function getComponent(): QtiComponent
    {
        return $this->component;
    }

    /**
     * Set the execution context of the ExpressionEngine.
     *
     * @param State $context A State object representing the execution context.
     */
    public function setContext(State $context): void
    {
        $this->context = $context;
    }

    /**
     * Get the execution context of the ExpressionEngine.
     *
     * @return State A State object representing the execution context.
     */
    public function getContext(): State
    {
        return $this->context;
    }

    /**
     * Set the StackTrace object that will hold information
     * about the running processing.
     *
     * @param StackTrace $stackTrace A StackTrace object.
     */
    protected function setStackTrace(StackTrace $stackTrace): void
    {
        $this->stackTrace = $stackTrace;
    }

    /**
     * Get the StackTrace object that will hold information
     * about the running processing.
     *
     * @return StackTrace A StackTrace object.
     */
    public function getStackTrace(): StackTrace
    {
        return $this->stackTrace;
    }

    /**
     * Add an entry in the stack trace about the QtiComponent being
     * processed.
     *
     * @param string $message A trace message.
     */
    protected function trace($message): void
    {
        $item = new StackTraceItem($this->getComponent(), $message);
        $this->stackTrace->push($item);
    }
}
