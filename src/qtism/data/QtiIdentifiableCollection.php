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

namespace qtism\data;

use InvalidArgumentException;
use OutOfRangeException;
use SplObserver;
use SplSubject;
use UnexpectedValueException;

/**
 * This extension of QtiComponentCollection can retrieve items it contains by QTI identifier.
 *
 * This collection implementation aims at storing QTI components having an "identifier" attribute.
 */
class QtiIdentifiableCollection extends QtiComponentCollection implements SplObserver
{
    /**
     * Create a new QtiIdentifiableCollection object.
     *
     * @param array $array (optional) An array of QtiIdentifiable objects to populate the new collection.
     * @throws InvalidArgumentException If a value of $array is not a QtiIdentifiable object.
     */
    public function __construct(array $array = [])
    {
        foreach ($array as $a) {
            $this->offsetSet(null, $a);
        }
    }

    /**
     * @param mixed $value
     */
    protected function checkType($value): void
    {
        if (!$value instanceof QtiIdentifiable) {
            $msg = 'The QtiIdentifiable class only accepts to store QtiIdentifiable objects.';
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether a QtiIdentifiable object with 'identifier' = $offset is in
     * the collection.
     *
     * @param mixed $offset
     * @return bool
     * @throws OutOfRangeException If the request $offset is not a string or is empty.
     */
    public function offsetExists($offset): bool
    {
        if (!is_string($offset) && empty($offset) === false) {
            $msg = 'The requested offset must be a string.';
            throw new OutOfRangeException($msg);
        }

        $data = &$this->getDataPlaceHolder();

        return isset($data[$offset]);
    }

    /**
     * Retrieve a QtiIdentifiable object from the collection.
     *
     * @param mixed $offset
     * @return QtiIdentifiable|null The requested QtiIdentifiable object or null if no object with 'identifier' = $offset is found.
     * @throws OutOfRangeException If the request $offset is not a string or is empty.
     */
    public function offsetGet($offset): ?QtiIdentifiable
    {
        if (!is_string($offset)) {
            $msg = 'The requested offset must be a non-empty string.';
            throw new OutOfRangeException($msg);
        }

        $returnValue = null;
        $data = &$this->getDataPlaceHolder();

        if (isset($data[$offset])) {
            $returnValue = $data[$offset];
        }

        return $returnValue;
    }

    /**
     * Put a QtiIdentifiable object into the collection. No specific offset must be
     * set because the key associated to $value is always its 'identifier' attribute's
     * value.
     *
     * @param null $offset
     * @param QtiIdentifiable $value A QtiIdentifiable object.
     * @throws InvalidArgumentException If $value is not a QtiIdentifiable object.
     * @throws OutOfRangeException If the offset is not null.
     */
    public function offsetSet($offset, $value): void
    {
        $this->checkType($value);

        if ($offset !== null) {
            $msg = 'No specific offset can be set in a QtiIdentifiableCollection. ';
            $msg .= "The offset is always infered from the 'identifier' attribute of ";
            $msg .= "the given QtiIdentifiable object. Given offset is '{$offset}'.";

            throw new OutOfRangeException($msg);
        }

        $this->dataPlaceHolder[$value->getIdentifier()] = $value;

        // Listen to events thrown by this $value.
        $value->attach($this);
    }

    /**
     * Attach a given QtiIdentifiable $object to the collection. Its key in the collection
     * will be the value value of its 'identifier' attribute.
     *
     * This method overrides AbstractCollection::attach.
     *
     * @param QtiIdentifiable $object A QtiIdentifiable object.
     * @throws InvalidArgumentException If $object is not a QtiIdentifiable object.
     */
    public function attach($object): void
    {
        $this->offsetSet(null, $object);
    }

    /**
     * Remove a QTIIdentifiable object from the collection that has its
     * 'identifier' attribute equals to $offset.
     *
     * @param mixed $offset
     * @throws OutOfRangeException If $offset is not a string.
     */
    public function offsetUnset($offset): void
    {
        if (is_string($offset)) {
            $data = &$this->getDataPlaceHolder();
            if (isset($data[$offset])) {
                $data[$offset]->detach($this);
                unset($data[$offset]);
            }
        } else {
            $msg = 'The requested offset must be a non-empty string.';
            throw new OutOfRangeException($msg);
        }
    }

    /**
     * Replace an $object in the collection by another $replacement $object.
     *
     * @param mixed $object An object to be replaced.
     * @param mixed $replacement An object to be used as a replacement.
     * @throws InvalidArgumentException If $object or $replacement are not compliant with the current collection typing.
     * @throws UnexpectedValueException If $object is not contained in the collection.
     */
    public function replace($object, $replacement): void
    {
        $this->checkType($object);
        $this->checkType($replacement);

        if (($search = array_search($object, $this->dataPlaceHolder, true)) !== false) {
            $objectKey = $search;
            $replacementKey = $replacement->getIdentifier();

            if ($objectKey === $replacementKey) {
                // If they share the same key, just replace.
                $this->dataPlaceHolder[$objectKey] = $replacement;
            } else {
                // Otherwise, we have to insert the $replacement object at the appropriate offset (just before $object),
                // and then remove the former $object.
                $objectOffset = array_search($objectKey, array_keys($this->dataPlaceHolder));

                $this->dataPlaceHolder = array_merge(
                    array_slice($this->dataPlaceHolder, 0, $objectOffset),
                    [$replacementKey => $replacement],
                    array_slice($this->dataPlaceHolder, $objectOffset, null)
                );

                $this->offsetUnset($objectKey);
            }

            $replacement->attach($this);
            $object->detach($this);
        } else {
            $msg = 'The object you want to replace could not be found.';
            throw new UnexpectedValueException($msg);
        }
    }

    /**
     * Implementation of SplObserver::update.
     *
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject): void
    {
        // -- case 1 (QtiIdentifiable)
        // If it is a QtiIdentifiable, it has changed its identifier.
        $this->replace($subject, $subject);
    }

    public function __clone()
    {
        $oldPlaceHolder = $this->getDataPlaceHolder();
        $newPlaceHolder = [];
        $this->setDataPlaceHolder($newPlaceHolder);

        foreach (array_keys($oldPlaceHolder) as $k) {
            $cloned = clone $oldPlaceHolder[$k];
            $cloned->attach($this);
            $this->offsetSet(null, $cloned);
        }
    }
}
