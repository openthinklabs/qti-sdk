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
 * The QTI templateElse class.
 */
class TemplateElse extends QtiComponent
{
    /**
     * The collection of TemplateRule objects to be evaluated.
     *
     * @var TemplateRuleCollection
     * @qtism-bean-property
     */
    private $templateRules;

    /**
     * Create a new TemplateElse object.
     *
     * @param TemplateRuleCollection $templateRules A collection of TemplateRule objects.
     */
    public function __construct(TemplateRuleCollection $templateRules)
    {
        $this->setTemplateRules($templateRules);
    }

    /**
     * Set the TemplateRule objects to be evaluated.
     *
     * @param TemplateRuleCollection $templateRules A collection of TemplateRule objects.
     */
    public function setTemplateRules(TemplateRuleCollection $templateRules): void
    {
        $this->templateRules = $templateRules;
    }

    /**
     * Get the TemplateRule objects to be evaluated.
     *
     * @return TemplateRuleCollection A collection of TemplateRule objects.
     */
    public function getTemplateRules(): TemplateRuleCollection
    {
        return $this->templateRules;
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        return new QtiComponentCollection($this->getTemplateRules()->getArrayCopy());
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'templateElse';
    }
}
