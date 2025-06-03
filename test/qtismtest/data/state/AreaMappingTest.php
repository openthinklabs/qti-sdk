<?php

namespace qtismtest\data\state;

use InvalidArgumentException;
use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiShape;
use qtism\data\state\AreaMapEntry;
use qtism\data\state\AreaMapEntryCollection;
use qtism\data\state\AreaMapping;
use qtismtest\QtiSmTestCase;

/**
 * Class AreaMappingTest
 */
class AreaMappingTest extends QtiSmTestCase
{
    public function testCreateNoAreaMapEntries(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('An AreaMapping object must contain at least one AreaMapEntry object. none given.');

        $mapping = new AreaMapping(
            new AreaMapEntryCollection(
                []
            )
        );
    }

    public function testSetLowerBoundWrongType(): void
    {
        $mapping = new AreaMapping(
            new AreaMapEntryCollection(
                [
                    new AreaMapEntry(QtiShape::RECT, new QtiCoords(QtiShape::RECT, [0, 0, 1, 1]), 0.0),
                ]
            )
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The lowerBound argument must be a float or false if no lower bound, 'boolean' given.");

        $mapping->setLowerBound(true);
    }

    public function testSetUpperBoundWrongType(): void
    {
        $mapping = new AreaMapping(
            new AreaMapEntryCollection(
                [
                    new AreaMapEntry(QtiShape::RECT, new QtiCoords(QtiShape::RECT, [0, 0, 1, 1]), 0.0),
                ]
            )
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The upperBound argument must be a float or false if no upper bound, 'boolean' given.");

        $mapping->setUpperBound(true);
    }

    public function testSetDefaultValueWrongType(): void
    {
        $mapping = new AreaMapping(
            new AreaMapEntryCollection(
                [
                    new AreaMapEntry(QtiShape::RECT, new QtiCoords(QtiShape::RECT, [0, 0, 1, 1]), 0.0),
                ]
            )
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The defaultValue argument must be a numeric value, 'boolean'.");

        $mapping->setDefaultValue(true);
    }
}
