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

namespace qtism\common\beans;

use ReflectionException;
use ReflectionMethod;

/**
 * Represents a Bean method such as a  bean getter or setter.
 */
class BeanMethod
{
    /**
     * The wrapped ReflectionObject representing the BeanMethod.
     *
     * @var ReflectionMethod
     */
    private $method;

    /**
     * Create a new Method object.
     *
     * @param mixed $class Name of the class or instance of this class containing the method.
     * @param string $name Name of the method.
     * @throws BeanException
     */
    public function __construct($class, $name)
    {
        try {
            $this->setMethod(new ReflectionMethod($class, $name));
        } catch (ReflectionException $e) {
            $msg = "The method '{$name}' does not exist.";
            throw new BeanException($msg, BeanException::NO_METHOD, $e);
        }
    }

    /**
     * Get the name of the bean method.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getMethod()->getName();
    }

    /**
     * Set the wrapper object representing the BeanMethod.
     *
     * @param ReflectionMethod $method A ReflectionMethod object.
     */
    protected function setMethod(ReflectionMethod $method): void
    {
        $this->method = $method;
    }

    /**
     * Get the wrapper object representing the BeanMethod.
     *
     * @return ReflectionMethod A ReflectionMethod object.
     */
    public function getMethod(): ReflectionMethod
    {
        return $this->method;
    }
}
