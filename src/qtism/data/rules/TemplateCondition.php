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

namespace qtism\data\rules;

use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

/**
 * From IMS QTI:
 *
 * If the expression given in the templateIf or templateElseIf evaluates to true then
 * the sub-rules contained within it are followed and any following templateElseIf or
 * templateElse parts are ignored for this template condition.
 *
 * If the expression given in the templateIf or templateElseIf does not evaluate to true
 * then consideration passes to the next templateElseIf or, if there are no more
 * templateElseIf parts then the sub-rules of the templateElse are followed (if specified).
 */
class TemplateCondition extends QtiComponent implements TemplateRule
{
    /**
     * The TemplateIf object composing the template condition.
     *
     * @var TemplateIf
     * @qtism-bean-property
     */
    private $templateIf;

    /**
     * The collection of TemplateElseIf objects composing the template condition.
     *
     * @var TemplateElseIfCollection
     * @qtism-bean-property
     */
    private $templateElseIfs;

    /**
     * An optional TemplateElse object composing the complate condition.
     *
     * @var TemplateElse
     * @qtism-bean-property
     */
    private $templateElse = null;

    /**
     * Create a new TemplateCondition object.
     *
     * @param TemplateIf $templateIf The TemplateIf object composing the template condition.
     * @param TemplateElseIfCollection $templateElseIfs The collection of TemplateElseIf objects composing the template condition.
     * @param TemplateElse $templateElse An optional TemplateElse object composing the template condition.
     */
    public function __construct(
        TemplateIf $templateIf,
        ?TemplateElseIfCollection $templateElseIfs = null,
        ?TemplateElse $templateElse = null
    ) {
        $this->setTemplateIf($templateIf);
        $this->setTemplateElseIfs($templateElseIfs ?? new TemplateElseIfCollection());
        $this->setTemplateElse($templateElse);
    }

    /**
     * Set the TemplateIf object composing the template condition.
     *
     * @param TemplateIf $templateIf A TemplateIf object.
     */
    public function setTemplateIf(TemplateIf $templateIf): void
    {
        $this->templateIf = $templateIf;
    }

    /**
     * Get the TemplateIf object composing the template condition;
     *
     * @return TemplateIf A TemplateIf object.
     */
    public function getTemplateIf(): TemplateIf
    {
        return $this->templateIf;
    }

    /**
     * Set the collection of TemplateElseIf objects composing the template condition.
     *
     * @param TemplateElseIfCollection $templateElseIfs A collection of TemplateElseIf objects.
     */
    public function setTemplateElseIfs(TemplateElseIfCollection $templateElseIfs): void
    {
        $this->templateElseIfs = $templateElseIfs;
    }

    /**
     * Get the collection of TemplateElseIf objects composing the template condition.
     *
     * @return TemplateElseIfCollection A collection of TemplateElseIf objects.
     */
    public function getTemplateElseIfs(): TemplateElseIfCollection
    {
        return $this->templateElseIfs;
    }

    /**
     * Set the TemplateElse object composing the template condition.
     *
     * @param TemplateElse $templateElse A TemplateElse object.
     */
    public function setTemplateElse(TemplateElse $templateElse = null): void
    {
        $this->templateElse = $templateElse;
    }

    /**
     * Get the TemplateElse object composing the template condition.
     *
     * @return TemplateElse|null A TemplateElse object.
     */
    public function getTemplateElse(): ?TemplateElse
    {
        return $this->templateElse;
    }

    /**
     * Whether a TemplateElse object is defined for this template condition.
     *
     * @return bool
     */
    public function hasTemplateElse(): bool
    {
        return $this->getTemplateElse() !== null;
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $merge = array_merge([$this->getTemplateIf()], $this->getTemplateElseIfs()->getArrayCopy());
        $components = new QtiComponentCollection($merge);
        if (($else = $this->getTemplateElse()) !== null) {
            $components[] = $else;
        }

        return $components;
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'templateCondition';
    }
}
