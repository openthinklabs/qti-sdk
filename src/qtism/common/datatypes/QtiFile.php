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
 * Copyright (c) 2014-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\common\datatypes;

use RuntimeException;

/**
 * The interface to implement to create a new QTI File datatype
 * implementation.
 *
 * From IMS QTI:
 *
 * A file value is any sequence of octets (bytes) qualified by a
 * content-type and an optional filename given to the file
 * (for example, by the candidate when uploading it as part
 * of an interaction). The content type of the file is one
 * of the MIME types defined by [RFC2045].
 */
interface QtiFile extends QtiDatatype
{
    /**
     * Get the sequence of bytes composing the file.
     *
     * @return string
     */
    public function getData(): string;

    /**
     * Get the MIME type of the file. This MIME type is one of the MIME
     * types defined by RFC2045.
     */
    public function getMimeType();

    /**
     * Whether a file name is defined for this file.
     *
     * @return bool
     */
    public function hasFilename(): bool;

    /**
     * Get the file name of this file. If no file name is defined,
     * an empty string is returned.
     *
     * @return string
     */
    public function getFilename(): string;

    /**
     * Get a brand new stream resource on the file. It is the responsibility of the
     * client code to close the stream when it not needed anymore.
     *
     * @return resource An open stream.
     * @throws RuntimeException If the stream on the file cannot be open.
     */
    public function getStream();

    /**
     * Get the unique identifier of the file in the storage system it
     * is stored.
     *
     * @return string A unique identifier.
     * @throws RuntimeException If an error occurs while retrieving the file.
     */
    public function getIdentifier(): string;
}
