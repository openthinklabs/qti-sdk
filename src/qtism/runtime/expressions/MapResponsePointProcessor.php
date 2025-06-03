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

namespace qtism\runtime\expressions;

use qtism\common\datatypes\QtiFloat;
use qtism\common\enums\BaseType;
use qtism\data\expressions\MapResponsePoint;
use qtism\runtime\common\MultipleContainer;
use qtism\runtime\common\ResponseVariable;

/**
 * The MapResponsePointProcessor class aims at processing QTI Data Model MapResponsePoint
 * Expression objects.
 *
 * From IMS QTI:
 *
 * This expression looks up the value of a response variable that must be of base-type point,
 * and transforms it using the associated areaMapping. The transformation is similar to
 * mapResponse except that the points are tested against each area in turn. When mapping
 * containers each area can be mapped once only. For example, if the candidate identified
 * two points that both fall in the same area then the mappedValue is still added to the
 * calculated total just once.
 */
class MapResponsePointProcessor extends ExpressionProcessor
{
    /**
     * Process the MapResponsePoint Expression.
     *
     * An ExpressionProcessingException is throw if:
     *
     * * The expression's identifier attribute does not point a variable in the current State object.
     * * The targeted variable is not a ResponseVariable object.
     * * The targeted variable has no areaMapping.
     * * The target variable has the RECORD cardinality.
     *
     * @return QtiFloat A transformed float value according to the areaMapping of the target variable.
     * @throws ExpressionProcessingException
     */
    public function process(): QtiFloat
    {
        $expr = $this->getExpression();
        $identifier = $expr->getIdentifier();
        $state = $this->getState();
        $var = $state->getVariable($identifier);

        if ($var !== null) {
            if ($var instanceof ResponseVariable) {
                $areaMapping = $var->getAreaMapping();

                if ($areaMapping === null) {
                    return new QtiFloat(0.0);
                }

                // Correct cardinality ?
                if ($var->getBaseType() === BaseType::POINT && ($var->isSingle() || $var->isMultiple())) {
                    // We can begin!

                    // -- Null value, nothing will match
                    if ($var->isNull()) {
                        return new QtiFloat($areaMapping->getDefaultValue());
                    }

                    if ($var->isSingle()) {
                        $val = new MultipleContainer(BaseType::POINT, [$state[$identifier]]);
                    } else {
                        $val = $state[$identifier];
                    }

                    $result = 0;
                    $mapped = [];

                    foreach ($val as $point) {
                        foreach ($areaMapping->getAreaMapEntries() as $areaMapEntry) {
                            $coords = $areaMapEntry->getCoords();

                            if (!in_array($coords, $mapped) && $coords->inside($point)) {
                                $mapped[] = $coords;
                                $result += $areaMapEntry->getMappedValue();
                            }
                        }
                    }

                    // If no relevant mapping found, return the default.
                    if (count($mapped) === 0) {
                        return new QtiFloat($areaMapping->getDefaultValue());
                    } elseif ($areaMapping->hasLowerBound() && $result < $areaMapping->getLowerBound()) {
                        // Check upper and lower bound.
                        return new QtiFloat($areaMapping->getLowerBound());
                    } elseif ($areaMapping->hasUpperBound() && $result > $areaMapping->getUpperBound()) {
                        return new QtiFloat($areaMapping->getUpperBound());
                    } else {
                        return new QtiFloat((float)$result);
                    }
                } elseif ($var->isRecord()) {
                    $msg = 'The MapResponsePoint expression cannot be applied to RECORD variables.';
                    throw new ExpressionProcessingException($msg, $this, ExpressionProcessingException::WRONG_VARIABLE_CARDINALITY);
                } else {
                    $strBaseType = BaseType::getNameByConstant($var->getBaseType());
                    $msg = "The MapResponsePoint expression applies only on variables with baseType 'point', baseType '{$strBaseType}' given.";
                    throw new ExpressionProcessingException($msg, $this, ExpressionProcessingException::WRONG_VARIABLE_BASETYPE);
                }
            } else {
                $msg = "The variable with identifier '{$identifier}' is not a ResponseVariable.";
                throw new ExpressionProcessingException($msg, $this, ExpressionProcessingException::WRONG_VARIABLE_TYPE);
            }
        } else {
            $msg = "No variable with identifier '{$identifier}' could be found in the current State object.";
            throw new ExpressionProcessingException($msg, $this, ExpressionProcessingException::NONEXISTENT_VARIABLE);
        }
    }

    /**
     * @return string
     */
    protected function getExpressionType(): string
    {
        return MapResponsePoint::class;
    }
}
