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

namespace qtism\data\content\xhtml\tables;

use InvalidArgumentException;
use qtism\data\content\BlockStatic;
use qtism\data\content\BodyElement;
use qtism\data\content\FlowStatic;
use qtism\data\content\FlowTrait;
use qtism\data\QtiComponentCollection;

/**
 * The XHTML table class.
 */
class Table extends BodyElement implements BlockStatic, FlowStatic
{
    use FlowTrait;

    /**
     * The summary attribute.
     *
     * @var string
     * @qtism-bean-property
     */
    private $summary = '';

    /**
     * A caption.
     *
     * @var Caption
     * @qtism-bean-property
     */
    private $caption = null;

    /**
     * From IMS QTI:
     *
     * If a table directly contains a col then it must not contain any colgroup elements.
     *
     * @var ColCollection
     * @qtism-bean-property
     */
    private $cols;

    /**
     * From IMS QTI:
     *
     * If a table contains a colgroup it must not directly contain any col elements.
     *
     * @var ColgroupCollection
     * @qtism-bean-property
     */
    private $colgroups;

    /**
     * A thead.
     *
     * @var Thead
     * @qtism-bean-property
     */
    private $thead = null;

    /**
     * A tfoot.
     *
     * @var Tfoot
     * @qtism-bean-property
     */
    private $tfoot = null;

    /**
     * The tbody elements.
     *
     * @var TbodyCollection
     * @qtism-bean-property
     */
    private $tbodies;

    /**
     * Create a new Table object.
     *
     * @param TbodyCollection $tbodies A collection of Tbody objects.
     * @param string $id The id of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws InvalidArgumentException If any of arguments is invalid.
     */
    public function __construct(TbodyCollection $tbodies, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($id, $class, $lang, $label);
        $this->setTbodies($tbodies);
        $this->setColgroups(new ColgroupCollection());
        $this->setCols(new ColCollection());
    }

    /**
     * Set the value of the summary attribute. An empty string
     * means there is no summary.
     *
     * @param string $summary
     * @throws InvalidArgumentException If $summary is not a string.
     */
    public function setSummary($summary): void
    {
        if (is_string($summary)) {
            $this->summary = $summary;
        } else {
            $msg = "The 'summary' argument must be a string, '" . gettype($summary) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the value of the summary attribute. An empty string means there is
     * no summary.
     *
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Whether a value for the summary attribute is defined.
     *
     * @return bool
     */
    public function hasSummary(): bool
    {
        return $this->getSummary() !== '';
    }

    /**
     * Set the Caption object of the Table. A null value means there
     * is no Caption.
     *
     * @param Caption $caption A Caption object or null.
     */
    public function setCaption(Caption $caption = null): void
    {
        $this->caption = $caption;
    }

    /**
     * Get the Caption object of the Table. A null value means there
     * is no Caption.
     *
     * @return Caption|null A Caption object or null.
     */
    public function getCaption(): ?Caption
    {
        return $this->caption;
    }

    /**
     * Whether the Table contains a Caption object.
     *
     * @return bool
     */
    public function hasCaption(): bool
    {
        return $this->getCaption() !== null;
    }

    /**
     * Set the Col objects composing the Table.
     *
     * @param ColCollection $cols A collection of Col objects.
     */
    public function setCols(ColCollection $cols): void
    {
        $this->cols = $cols;
    }

    /**
     * Get the Col objects composing the Table.
     *
     * @return ColCollection A collection of Col objects.
     */
    public function getCols(): ColCollection
    {
        return $this->cols;
    }

    /**
     * Set the Colgroup objects composing the Table.
     *
     * @param ColgroupCollection $colgroups A collection of Colgroup objects.
     */
    public function setColgroups(ColgroupCollection $colgroups): void
    {
        $this->colgroups = $colgroups;
    }

    /**
     * Get the Colgroup objects composing the Table.
     *
     * @return ColgroupCollection A collection of Colgroup objects.
     */
    public function getColgroups(): ColgroupCollection
    {
        return $this->colgroups;
    }

    /**
     * Set the Thead object. A null value means there is no
     * Thead.
     *
     * @param Thead $thead A Thead object or null.
     */
    public function setThead(Thead $thead = null): void
    {
        $this->thead = $thead;
    }

    /**
     * Get the Thead object. A null value means there is no Thead.
     *
     * @return Thead|null A Thead object or null.
     */
    public function getThead(): ?Thead
    {
        return $this->thead;
    }

    /**
     * Whether the Table contains a Thead object.
     *
     * @return bool
     */
    public function hasThead(): bool
    {
        return $this->getThead() !== null;
    }

    /**
     * Set the Tfoot object
     *
     * @param Tfoot $tfoot
     */
    public function setTfoot(Tfoot $tfoot): void
    {
        $this->tfoot = $tfoot;
    }

    /**
     * Get the Tfoot object of the Table. A null value means there is no
     * Tfoot.
     *
     * @return Tfoot|null A Tfoot object or null.
     */
    public function getTfoot(): ?Tfoot
    {
        return $this->tfoot;
    }

    /**
     * Whether the Table contains a Tfoot object.
     *
     * @return bool
     */
    public function hasTfoot(): bool
    {
        return $this->getTfoot() !== null;
    }

    /**
     * Set the Tbody objects composing the Table.
     *
     * @param TbodyCollection $tbodies A collection of Tbody objects.
     */
    public function setTbodies(TbodyCollection $tbodies): void
    {
        $this->tbodies = $tbodies;
    }

    /**
     * Get the Tbody objects composing the Table.
     *
     * @return TbodyCollection A collection of Tbody objects.
     */
    public function getTbodies(): TbodyCollection
    {
        return $this->tbodies;
    }

    /**
     * @return QtiComponentCollection
     */
    public function getComponents(): QtiComponentCollection
    {
        $array = [];

        if ($this->hasCaption() === true) {
            $array[] = $this->getCaption();
        }

        $array = array_merge($array, $this->getCols()->getArrayCopy(), $this->getColgroups()->getArrayCopy());

        if ($this->hasThead() === true) {
            $array[] = $this->getThead();
        }

        if ($this->hasTfoot() === true) {
            $array[] = $this->getTfoot();
        }

        $array = array_merge($array, $this->getTbodies()->getArrayCopy());

        return new QtiComponentCollection($array);
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'table';
    }
}
