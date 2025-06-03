<?php

namespace qtismtest\runtime\common;

use qtism\common\datatypes\files\FileSystemFileManager;
use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiFile;
use qtism\common\datatypes\QtiFloat;
use qtism\common\datatypes\QtiInteger;
use qtism\common\datatypes\QtiPair;
use qtism\common\datatypes\QtiPoint;
use qtism\common\datatypes\QtiString;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\AreaMapping;
use qtism\data\state\Mapping;
use qtism\data\state\ResponseDeclaration;
use qtism\runtime\common\MultipleContainer;
use qtism\runtime\common\OrderedContainer;
use qtism\runtime\common\RecordContainer;
use qtism\runtime\common\ResponseVariable;
use qtismtest\QtiSmTestCase;

/**
 * Class ResponseVariableTest
 */
class ResponseVariableTest extends QtiSmTestCase
{
    public function testCreateFromVariableDeclarationExtended(): void
    {
        $factory = $this->getMarshallerFactory('2.1.0');
        $element = $this->createDOMElement('
			<responseDeclaration xmlns="http://www.imsglobal.org/xsd/imsqti_v2p0" 
								identifier="outcome1" 
								baseType="pair" 
								cardinality="ordered">
				<defaultValue>
					<value>A B</value>
					<value>C D</value>
					<value>E F</value>
				</defaultValue>
				<correctResponse interpretation="Up to you :)!">
					<value>A B</value>
					<value>E F</value>
				</correctResponse>
				<mapping>
					<mapEntry mapKey="A B" mappedValue="1.0" caseSensitive="true"/>
					<mapEntry mapKey="C D" mappedValue="2.0" caseSensitive="true"/>
					<mapEntry mapKey="E F" mappedValue="3.0" caseSensitive="true"/>
				</mapping>
				<areaMapping>
					<areaMapEntry shape="rect" coords="10, 20, 40, 20" mappedValue="1.0"/>
					<areaMapEntry shape="rect" coords="20, 30, 50, 30" mappedValue="2.0"/>
					<areaMapEntry shape="rect" coords="30, 40, 60, 40" mappedValue="3.0"/>
				</areaMapping>
			</responseDeclaration>
		');
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $factory->createMarshaller($element)->unmarshall($element);
        $responseVariable = ResponseVariable::createFromDataModel($responseDeclaration);
        $this::assertInstanceOf(ResponseVariable::class, $responseVariable);

        $this::assertEquals('outcome1', $responseVariable->getIdentifier());
        $this::assertEquals(BaseType::PAIR, $responseVariable->getBaseType());
        $this::assertEquals(Cardinality::ORDERED, $responseVariable->getCardinality());

        // Value should be NULL to begin.
        $this::assertTrue($responseVariable->isNull());

        $defaultValue = $responseVariable->getDefaultValue();
        $this::assertEquals(
            $defaultValue,
            new OrderedContainer(BaseType::PAIR, [new QtiPair('A', 'B'), new QtiPair('C', 'D'), new QtiPair('E', 'F')])
        );

        $mapping = $responseVariable->getMapping();
        $this::assertInstanceOf(Mapping::class, $mapping);
        $mapEntries = $mapping->getMapEntries();
        $this::assertCount(3, $mapEntries);
        $this::assertInstanceOf(QtiPair::class, $mapEntries[0]->getMapKey());

        $areaMapping = $responseVariable->getAreaMapping();
        $this::assertInstanceOf(AreaMapping::class, $areaMapping);
        $areaMapEntries = $areaMapping->getAreaMapEntries();
        $this::assertCount(3, $areaMapEntries);
        $this::assertInstanceOf(QtiCoords::class, $areaMapEntries[0]->getCoords());

        $this::assertTrue($responseVariable->hasCorrectResponse());
        $correctResponse = $responseVariable->getCorrectResponse();
        $this::assertInstanceOf(OrderedContainer::class, $correctResponse);
        $this::assertCount(2, $correctResponse);
        $this::assertTrue($correctResponse[0]->equals(new QtiPair('A', 'B')));
        $this::assertTrue($correctResponse[1]->equals(new QtiPair('E', 'F')));

        $responseVariable->setValue(new OrderedContainer(BaseType::PAIR, [new QtiPair('A', 'B'), new QtiPair('E', 'F')]));
        $this::assertTrue($responseVariable->isCorrect());
        $responseVariable->setValue(new OrderedContainer(BaseType::PAIR, [new QtiPair('E', 'F'), new QtiPair('A', 'B')]));
        $this::assertFalse($responseVariable->isCorrect());

        // If I reinitialize the value, we must find a NULL container into this variable.
        $responseVariable->initialize();
        $this::assertInstanceOf(OrderedContainer::class, $responseVariable->getValue());
        $this::assertTrue($responseVariable->isNull());
        $this::assertFalse($responseVariable->isInitializedFromDefaultValue());

        // If I apply the default value...
        $responseVariable->applyDefaultValue();
        $this::assertInstanceOf(OrderedContainer::class, $responseVariable->getValue());
        $this::assertCount(3, $responseVariable->getValue());
        $this::assertTrue($responseVariable->getValue()->equals($defaultValue));
        $this::assertTrue($responseVariable->isInitializedFromDefaultValue());

        // If I set value once more...
        $responseVariable->setValue($defaultValue);
        $this::assertFalse($responseVariable->isInitializedFromDefaultValue());
    }

    public function testIsCorrectWithNullCorrectResponse(): void
    {
        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::INTEGER, new QtiInteger(25));
        $this::assertFalse($responseVariable->isCorrect());
    }

    public function testGetScalarDataModelValuesSingleCardinality(): void
    {
        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::INTEGER, new QtiInteger(10));
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(1, $values);
        $this::assertSame(10, $values[0]->getValue());
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());

        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::FLOAT, new QtiFloat(10.1));
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(1, $values);
        $this::assertSame(10.1, $values[0]->getValue());
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());
    }

    public function testGetNonScalarDataModelValuesSingleCardinality(): void
    {
        // QtiPair
        $qtiPair = new QtiPair('A', 'B');
        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::PAIR, $qtiPair);
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(1, $values);
        $this::assertTrue($qtiPair->equals($values[0]->getValue()));
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());

        // QtiPoint
        $qtiPoint = new QtiPoint(1, 1);
        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::POINT, $qtiPoint);
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(1, $values);
        $this::assertTrue($qtiPoint->equals($values[0]->getValue()));
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());

        // QtiFile
        $fileManager = new FileSystemFileManager();

        $path = __FILE__;
        $fileName = basename($path);
        $mimeType = 'text/plain';
        $qtiFile = $fileManager->createFromFile($path, $mimeType, $fileName);

        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::FILE, $qtiFile);
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(1, $values);
        $actual = $values[0]->getValue();
        $this::assertInstanceOf(QtiFile::class, $actual);
        $this::assertEquals($fileName, $actual->getFilename());
        $this::assertEquals($mimeType, $actual->getMimeType());
        $this::assertStringEqualsFile($path, $actual->getData());
    }

    public function testGetScalarDataModelValuesMultipleCardinality(): void
    {
        $responseVariable = new ResponseVariable(
            'MYVAR',
            Cardinality::MULTIPLE,
            BaseType::INTEGER,
            new MultipleContainer(
                BaseType::INTEGER,
                [new QtiInteger(10), new QtiInteger(12)]
            )
        );
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(2, $values);
        $this::assertEquals(10, $values[0]->getValue());
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());
        $this::assertEquals(12, $values[1]->getValue());
        $this::assertSame('', $values[1]->getFieldIdentifier());
        $this::assertSame(-1, $values[1]->getBaseType());
    }

    public function testGetNonScalarDataModelValuesMultipleCardinality(): void
    {
        // QtiPair
        $qtiPair1 = new QtiPair('A', 'B');
        $qtiPair2 = new QtiPair('C', 'D');

        $responseVariable = new ResponseVariable(
            'MYVAR',
            Cardinality::MULTIPLE,
            BaseType::PAIR,
            new MultipleContainer(
                BaseType::PAIR,
                [$qtiPair1, $qtiPair2]
            )
        );
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(2, $values);
        $this::assertTrue($qtiPair1->equals($values[0]->getValue()));
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());
        $this::assertTrue($qtiPair2->equals($values[1]->getValue()));
        $this::assertSame('', $values[1]->getFieldIdentifier());
        $this::assertSame(-1, $values[1]->getBaseType());

        // QtiPoint
        $qtiPoint1 = new QtiPoint(0, 0);
        $qtiPoint2 = new QtiPoint(1, 1);

        $responseVariable = new ResponseVariable(
            'MYVAR',
            Cardinality::MULTIPLE,
            BaseType::POINT,
            new MultipleContainer(
                BaseType::POINT,
                [$qtiPoint1, $qtiPoint2]
            )
        );
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(2, $values);
        $this::assertTrue($qtiPoint1->equals($values[0]->getValue()));
        $this::assertSame('', $values[0]->getFieldIdentifier());
        $this::assertSame(-1, $values[0]->getBaseType());
        $this::assertTrue($qtiPoint2->equals($values[1]->getValue()));
        $this::assertSame('', $values[1]->getFieldIdentifier());
        $this::assertSame(-1, $values[1]->getBaseType());
    }

    public function testGetDataModelValuesRecordCardinality(): void
    {
        $qtiPair = new QtiPair('A', 'B');

        $responseVariable = new ResponseVariable(
            'MYVAR',
            Cardinality::RECORD,
            -1,
            new RecordContainer(
                [
                    'twelve' => new QtiInteger(12),
                    'foo' => new QtiString('bar'),
                    'null' => null,
                    'pair' => $qtiPair,
                ]
            )
        );
        $values = $responseVariable->getDataModelValues();

        $this::assertCount(4, $values);
        $this::assertSame(12, $values[0]->getValue());
        $this::assertSame('twelve', $values[0]->getFieldIdentifier());
        $this::assertSame(BaseType::INTEGER, $values[0]->getBaseType());
        $this::assertSame('bar', $values[1]->getValue());
        $this::assertSame('foo', $values[1]->getFieldIdentifier());
        $this::assertSame(BaseType::STRING, $values[1]->getBaseType());
        $this::assertNull($values[2]->getValue());
        $this::assertSame('null', $values[2]->getFieldIdentifier());
        $this::assertSame(-1, $values[2]->getBaseType());
        $this::assertTrue($qtiPair->equals($values[3]->getValue()));
        $this::assertSame('pair', $values[3]->getFieldIdentifier());
        $this::assertSame(BaseType::PAIR, $values[3]->getBaseType());
    }

    public function testClone(): void
    {
        // value, default value and correct response must be independent after cloning.
        $responseVariable = new ResponseVariable('MYVAR', Cardinality::SINGLE, BaseType::INTEGER, new QtiInteger(25));
        $responseVariable->setDefaultValue(new QtiInteger(1));
        $responseVariable->setCorrectResponse(new QtiInteger(1337));

        $clone = clone $responseVariable;
        $this::assertNotSame($responseVariable->getValue(), $clone->getValue());
        $this::assertNotSame($responseVariable->getDefaultValue(), $clone->getDefaultValue());
        $this::assertNotSame($responseVariable->getCorrectResponse(), $clone->getCorrectResponse());
    }
}
