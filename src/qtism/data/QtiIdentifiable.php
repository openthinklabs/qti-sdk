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

use SplObjectStorage;
use SplSubject;

/**
 * Any QTI class which has an identifier that makes its instances
 * unique must implement this class.
 */
interface QtiIdentifiable extends SplSubject
{
    /**
     * Get the identifier of the QTI class instance.
     *
     * @return string A QTI Identifier.
     */
    public function getIdentifier(): string;

    /**
     * Get the observers of the object.
     *
     * @return SplObjectStorage An SplObjectStorage object.
     */
    public function getObservers(): SplObjectStorage;

    /**
     * Set the observers of the object.
     *
     * @param SplObjectStorage $observers An SplObjectStorage object.
     */
    public function setObservers(SplObjectStorage $observers);
}
