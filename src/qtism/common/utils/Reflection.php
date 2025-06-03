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

namespace qtism\common\utils;

use ReflectionClass;

/**
 * A utility class focusing on Reflection.
 */
class Reflection
{
    /**
     * An abstraction of the call to ReflectionClass::newInstanceArgs. The main
     * goal of this method is to avoid to encounter the issue with empty $args
     * argument described at: http://www.php.net/manual/en/reflectionclass.newinstanceargs.php#99517
     *
     * @param ReflectionClass $class
     * @param array $args
     * @return mixed An instance of $class
     * @see http://www.php.net/manual/en/reflectionclass.newinstanceargs.php#99517 The awful bug!
     */
    #[\ReturnTypeWillChange]
    public static function newInstance(ReflectionClass $class, $args = [])
    {
        if (empty($args)) {
            $fqName = $class->getName();

            return new $fqName();
        } else {
            return $class->newInstanceArgs($args);
        }
    }

    /**
     * Obtains the short class name of a given $object.
     *
     * If $object is not an object, false is returned instead of a string.
     *
     * Examples:
     *
     * + my\namespace\A -> A
     * + A -> A
     * + \my\A -> A
     *
     * @param mixed $object An object or a fully qualified class name.
     * @return bool|string A short class name or false if $object is not an object nor a string.
     */
    public static function shortClassName($object)
    {
        $shortClassName = false;

        if (is_object($object)) {
            $parts = explode("\\", get_class($object));
            $shortClassName = array_pop($parts);
        } elseif (is_string($object) && empty($object) === false) {
            $parts = explode("\\", $object);
            $shortClassName = array_pop($parts);
        }

        return empty($shortClassName) ? false : $shortClassName;
    }

    /**
     * Whether a given $object is an instance of $className. This method
     * exists because is_sublcass_of() does not take into account interfaces
     * in PHP 5.3.
     *
     * @param mixed $object The object you want to know it is an instance of $className.
     * @param string $className A class name. It can be fully qualified.
     * @return bool
     */
    public static function isInstanceOf($object, $className): bool
    {
        $givenType = get_class($object);

        return $givenType === $className || is_subclass_of($givenType, $className) === true || in_array($className, class_implements($givenType));
    }
}
