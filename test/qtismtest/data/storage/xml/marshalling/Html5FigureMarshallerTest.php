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
 * Copyright (c) 2022 (original work) Open Assessment Technologies SA;
 */

namespace qtismtest\data\storage\xml\marshalling;

use qtism\data\content\FlowCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\html5\Figcaption;
use qtism\data\content\xhtml\html5\Figure;
use qtism\data\storage\xml\marshalling\MarshallerNotFoundException;
use qtism\data\storage\xml\marshalling\MarshallingException;

class Html5FigureMarshallerTest extends Html5ElementMarshallerTest
{
    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshallerDoesNotExistInQti21(): void
    {
        $this->assertHtml5MarshallingOnlyInQti22AndAbove(new Figure(), Figure::QTI_CLASS_NAME_FIGURE);
    }

    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshall22(): void
    {
        $id = 'id';
        $class = 'testclass';

        $expected = sprintf(
            '<%1$s id="%2$s" class="%3$s"><%5$s>text content</%5$s></%4$s>',
            $this->namespaceTag(Figure::QTI_CLASS_NAME_FIGURE),
            $id,
            $class,
            $this->prefixTag(Figure::QTI_CLASS_NAME_FIGURE),
            $this->prefixTag(Figcaption::QTI_CLASS_NAME_FIGCAPTION)
        );

        $figcaption = new Figcaption();
        $figcaption->setContent(new InlineCollection([new TextRun('text content')]));

        $object = new Figure(null, null, $id, $class);
        $object->setContent(new FlowCollection([$figcaption]));

        $this->assertMarshalling($expected, $object);
    }

    /**
     * @throws MarshallerNotFoundException
     * @throws MarshallingException
     */
    public function testMarshall22WithDefaultValues(): void
    {
        $expected = sprintf(
            '<%s/>',
            $this->namespaceTag(Figure::QTI_CLASS_NAME_FIGURE)
        );

        $video = new Figure();

        $this->assertMarshalling($expected, $video);
    }

    /**
     * @throws MarshallerNotFoundException
     */
    public function testUnMarshallerDoesNotExistInQti21(): void
    {
        $this->assertHtml5UnmarshallingOnlyInQti22AndAbove(
            sprintf(
                '<%s></%s>',
                $this->namespaceTag(Figure::QTI_CLASS_NAME_FIGURE),
                $this->prefixTag(Figure::QTI_CLASS_NAME_FIGURE)
            ),
            Figure::QTI_CLASS_NAME_FIGURE
        );
    }

    /**
     * @throws MarshallerNotFoundException
     */
    public function testUnmarshall22(): void
    {
        $id = 'id';
        $class = 'testclass';

        $xml = sprintf(
            '<%1$s id="%2$s" class="%3$s"></%4$s>',
            $this->namespaceTag(Figure::QTI_CLASS_NAME_FIGURE),
            $id,
            $class,
            $this->prefixTag(Figure::QTI_CLASS_NAME_FIGURE)
        );

        $expected = new Figure(null, null, $id, $class);

        $this->assertUnmarshalling($expected, $xml);
    }

    public function testUnmarshall22WithDefaultValues(): void
    {
        $xml = sprintf(
            '<%s></%s>',
            $this->namespaceTag(Figure::QTI_CLASS_NAME_FIGURE),
            $this->prefixTag(Figure::QTI_CLASS_NAME_FIGURE)
        );

        $expected = new Figure();

        $this->assertUnmarshalling($expected, $xml);
    }
}
