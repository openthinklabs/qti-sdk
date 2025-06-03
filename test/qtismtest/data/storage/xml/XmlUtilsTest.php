<?php

declare(strict_types=1);

namespace qtismtest\data\storage\xml;

use DOMDocument;
use DOMElement;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\storage\xml\Utils;
use qtismtest\QtiSmTestCase;

/**
 * Class XmlUtilsTest
 */
class XmlUtilsTest extends QtiSmTestCase
{
    /**
     * @param string $originalXmlString
     * @param string $expectedXmlString
     * @dataProvider anonimizeElementProvider
     */
    public function testAnonimizeElement($originalXmlString, $expectedXmlString): void
    {
        $elt = $this->createDOMElement($originalXmlString);
        $newElt = Utils::anonimizeElement($elt);

        $this::assertEquals($expectedXmlString, $newElt->ownerDocument->saveXML($newElt));
    }

    /**
     * @return array
     */
    public function anonimizeElementProvider(): array
    {
        return [
            [
                '<m:math xmlns:m="http://www.w3.org/1998/Math/MathML" display="inline"><m:mn>1</m:mn><m:mo>+</m:mo><m:mn>2</m:mn><m:mo>=</m:mo><m:mn>3</m:mn></m:math>',
                '<math display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
            ],

            [
                '<math xmlns="http://www.w3.org/1998/Math/MathML" display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
                '<math display="inline"><mn>1</mn><mo>+</mo><mn>2</mn><mo>=</mo><mn>3</mn></math>',
            ],

            [
                '<math xmlns="http://www.w3.org/1998/Math/MathML" display="inline"><mn><![CDATA[1]]></mn><mo>+</mo><mn><![CDATA[2]]></mn><mo>=</mo><mn><![CDATA[3]]></mn></math>',
                '<math display="inline"><mn><![CDATA[1]]></mn><mo>+</mo><mn><![CDATA[2]]></mn><mo>=</mo><mn><![CDATA[3]]></mn></math>',
            ],
        ];
    }

    /**
     * @dataProvider getXsdLocationProvider
     *
     * @param string $file
     * @param string $namespaceUri
     * @param bool|string $expectedLocation
     */
    public function testGetXsdLocation($file, $namespaceUri, $expectedLocation): void
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->load($file);
        $location = Utils::getXsdLocation($document, $namespaceUri);

        $this::assertSame($expectedLocation, $location);
    }

    /**
     * @return array
     */
    public function getXsdLocationProvider(): array
    {
        return [
            // Valid.
            [
                self::samplesDir() . 'custom/items/versiondetection_20_singlespace.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_multispace.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_singletab.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_multitab.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_leading.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_trailing.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                'http://www.imsglobal.org/xsd/imsqti_v2p0.xsd',
            ],
            [
                self::samplesDir() . 'ims/items/2_1_1/associate.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p1',
                'http://www.imsglobal.org/xsd/qti/qtiv2p1/imsqti_v2p1p1.xsd',
            ],
            [
                self::samplesDir() . 'ims/items/2_1/associate.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p1',
                'http://www.imsglobal.org/xsd/qti/qtiv2p1/imsqti_v2p1.xsd',
            ],

            // Invalid
            [
                self::samplesDir() . 'custom/items/versiondetection_20_trailing.xml',
                'wrong-ns',
                false,
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_emptyschemalocation.xml',
                'wrong-ns',
                false,
            ],
            [
                self::samplesDir() . 'custom/items/versiondetection_20_noschemalocationattribute.xml',
                'http://www.imsglobal.org/xsd/imsqti_v2p0',
                false,
            ],
        ];
    }

    public function testChangeNamespaceElementName(): void
    {
        $foo = $this->createDOMElement('<foo xmlns:bar="http://baz" bar:attr="foo"/>');
        $foo = Utils::changeElementName($foo, 'bar');
        $this::assertEquals('bar', $foo->tagName);
        $this::assertEquals('foo', $foo->getAttributeNS('http://baz', 'attr'));
    }

    /**
     * @dataProvider escapeXmlSpecialCharsProvider
     * @param string $str
     * @param bool $isAttribute
     * @param string $expected
     */
    public function testEscapeXmlSpecialChars($str, $isAttribute, $expected): void
    {
        $this::assertEquals($expected, Utils::escapeXmlSpecialChars($str, $isAttribute));
    }

    /**
     * @return array
     */
    public function escapeXmlSpecialCharsProvider(): array
    {
        return [
            ['\'"&<>', false, '&apos;&quot;&amp;&lt;&gt;'],
            ['<blah>', false, '&lt;blah&gt;'],
            ['blah', false, 'blah'],
            ['&"', true, '&amp;&quot;'],
            ['blah & "cool" & \'cool\'', true, 'blah &amp; &quot;cool&quot; &amp; \'cool\''],
        ];
    }

    /**
     * @dataProvider webComponentFriendlyAttributeNameProvider
     * @param string $qtiName
     * @param string $expected
     */
    public function testWebComponentFriendlyAttributeName($qtiName, $expected): void
    {
        $this::assertEquals($expected, Utils::webComponentFriendlyAttributeName($qtiName));
    }

    /**
     * @return array
     */
    public function webComponentFriendlyAttributeNameProvider(): array
    {
        return [
            ['minChoices', 'min-choices'],
            ['identifier', 'identifier'],
        ];
    }

    /**
     * @dataProvider webComponentFriendlyClassNameProvider
     * @param string $qtiName
     * @param string $expected
     */
    public function testWebComponentFriendlyClassName($qtiName, $expected): void
    {
        $this::assertEquals($expected, Utils::webComponentFriendlyClassName($qtiName));
    }

    /**
     * @return array
     */
    public function webComponentFriendlyClassNameProvider(): array
    {
        return [
            ['choiceInteraction', 'qti-choice-interaction'],
            ['simpleChoice', 'qti-simple-choice'],
            ['prompt', 'qti-prompt'],
        ];
    }

    /**
     * @dataProvider qtiFriendlyNameProvider
     * @param string $wcName
     * @param string $expected
     */
    public function testQtiFriendlyName($wcName, $expected): void
    {
        $this::assertEquals($expected, Utils::qtiFriendlyName($wcName));
    }

    /**
     * @return array
     */
    public function qtiFriendlyNameProvider(): array
    {
        return [
            ['qti-choice-interaction', 'choiceInteraction'],
            ['qti-prompt', 'prompt'],
            ['min-choices', 'minChoices'],
        ];
    }

    /**
     * @dataProvider getDOMElementAttributeAsProvider
     * @param DOMElement $element
     * @param string $attribute
     * @param string $datatype
     * @param mixed $expected
     */
    public function testGetDOMElementAttributeAs(DOMElement $element, $attribute, $datatype, $expected): void
    {
        $result = Utils::getDOMElementAttributeAs($element, $attribute, $datatype);
        $this::assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function getDOMElementAttributeAsProvider(): array
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML('<foo string="str&amp;str" integer="1" float="1.1" double="1.1" boolean="true" baseType="duration" wrongEnumValue="blah"/>');
        $elt = $dom->documentElement;

        return [
            [$elt, 'string', 'string', 'str&str'],
            [$elt, 'integer', 'integer', 1],
            [$elt, 'float', 'float', 1.1],
            [$elt, 'double', 'double', 1.1],
            [$elt, 'boolean', 'boolean', true],
            [$elt, 'not-existing', '', null],
            [$elt, 'baseType', BaseType::class, BaseType::DURATION],
            [$elt, 'wrongEnumValue', BaseType::class, 'blah'],
            [$elt, 'cardinality', Cardinality::class, null],
        ];
    }

    /**
     * @dataProvider setDOMElementAttributeProvider
     * @param string $attribute
     * @param string $value
     * @param mixed $expected
     */
    public function testSetDOMElementAttribute($attribute, $value, $expected): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $element = $dom->createElement('foo');
        $dom->appendChild($element);

        Utils::setDOMElementAttribute($element, $attribute, $value);
        $result = $dom->saveXML($element);

        $this::assertSame($expected, $result);
    }

    /**
     * @return array
     */
    public function setDOMElementAttributeProvider(): array
    {
        return [
            ['string', 'str&str', '<foo string="str&amp;str"/>'],
            ['integer', 1, '<foo integer="1"/>'],
            ['float', 1.1, '<foo float="1.1"/>'],
            ['double', 1.1, '<foo double="1.1"/>'],
            ['boolean', true, '<foo boolean="true"/>'],
            ['not-existing', null, '<foo not-existing=""/>'],
        ];
    }

    public function testGetChildElementsByTagName(): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        // There are 3 child elements. 2 at the first level, 1 at the second.
        // We should find only 2 direct child elements.
        $dom->loadXML('<parent><child/><child/><parent><child/></parent></parent>');
        $element = $dom->documentElement;

        $this::assertCount(2, Utils::getChildElementsByTagName($element, 'child'));
    }

    public function testGetChildElementsByTagNameMultiple(): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML('<parent><child/><child/><grandChild/><uncle/></parent>');
        $element = $dom->documentElement;

        $this::assertCount(3, Utils::getChildElementsByTagName($element, ['child', 'grandChild']));
    }

    public function testGetChildElementsByTagNameEmpty(): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        // There is only 1 child but at the second level. Nothing
        // should be found.
        $dom->loadXML('<parent><parent><child/></parent></parent>');
        $element = $dom->documentElement;

        $this::assertCount(0, Utils::getChildElementsByTagName($element, 'child'));
    }

    public function testFindCustomNamespaces(): void
    {
        $xml = ('<?xml version="1.0" encoding="UTF-8"?>
<assessmentItem
        xmlns:qh5="http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0"
        xmlns="http://www.imsglobal.org/xsd/imsqti_v2p2"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        identifier="video-tracks" title="Big Buck Bunny" timeDependent="false" adaptive="false"
        xsi:schemaLocation="
          http://www.imsglobal.org/xsd/imsqti_v2p2 http://www.imsglobal.org/xsd/qti/qtiv2p2/imsqti_v2p2.xsd
        "
>
  <responseDeclaration identifier="RESPONSE" cardinality="single" baseType="identifier">
    <correctResponse>
      <value>C</value>
    </correctResponse>
  </responseDeclaration>
  <outcomeDeclaration identifier="SCORE" cardinality="single" baseType="float">
    <defaultValue>
      <value>0</value>
    </defaultValue>
  </outcomeDeclaration>
  <itemBody xmlns:qh5="http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0">
    <p>
      Below is a Big Buck Bunny video trailer with two different Closed Caption tracks: English and Japanese.
      Use the video player\'s controls to turn the caption tracks (the "CC" button) on and off.
    </p>
    <qh5:video xmlns:qh5="http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0" controls="true" height="240" width="320">
      <qh5:source src="images/big_buck_bunny.mp4" type="video/mp4"/>
      <qh5:source src="images/big_buck_bunny.webm" type="video/webm"/>
      <qh5:track label="English" src="images/texttrack-en.vtt" default="true" kind="captions" srclang="en"/>
      <qh5:track label="Japanese" src="images/texttrack-jpn.vtt" kind="captions" srclang="ja"/>
    </qh5:video>
    <p>
    This trailer, and the Big Buck Bunny project, is (c) copyright 2008, Blender Foundation / www.bigbuckbunny.org
    </p>
    <choiceInteraction responseIdentifier="RESPONSE" maxChoices="1">
      <prompt>
        <p>How many Caption Tracks are included in the video presentation above?</p>
      </prompt>
      <simpleChoice identifier="A">0</simpleChoice>
      <simpleChoice identifier="B">1</simpleChoice>
      <simpleChoice identifier="C">2</simpleChoice>
      <simpleChoice identifier="D">3</simpleChoice>
    </choiceInteraction>
    <mediaInteraction xmlns:qh5="http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0" responseIdentifier="RESPONSE1" autostart="false">
      <qh5:audio xmlns:qh5="http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0" src="images/big_buck_bunny.mp4"/>
    </mediaInteraction>
  </itemBody>
  <responseProcessing template="http://www.imsglobal.org/question/qti_v2p2/rptemplates/match_correct"/>
</assessmentItem>
');

        self::assertSame(
            ['qh5' => 'http://www.imsglobal.org/xsd/imsqtiv2p2_html5_v1p0'],
            Utils::findExternalNamespaces($xml)
        );
    }

    public function testValueAsStringReplaceSpecialSymbols(): void
    {
        $this->assertEquals("160\u{FFFD}", Utils::valueAsString("160\u{0008}"));
    }

    public function testProcessSpecialCharsetWithoutError(): void
    {
        $xml = <<<'XML'
<assessmentResult
    xmlns="http://www.imsglobal.org/xsd/imsqti_result_v2p1"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <context/>
    <testResult identifier="44127db28512-suomynona#903756e974e7#e94025be336b1f89159af64b1f6eda5d470ac8d61#local-dev-acc.nextgen-stack-local" datestamp="2024-10-30T12:56:32+00:00"/>
    <itemResult identifier="item-1" datestamp="2024-10-30T12:56:32+00:00" sessionStatus="final">
        <responseVariable identifier="numAttempts" cardinality="single" baseType="integer">
            <candidateResponse>
                <value>1</value>
            </candidateResponse>
        </responseVariable>
        <responseVariable identifier="duration" cardinality="single" baseType="duration">
            <candidateResponse>
                <value>PT22S</value>
            </candidateResponse>
        </responseVariable>
        <outcomeVariable identifier="completionStatus" cardinality="single" baseType="identifier">
            <value>completed</value>
        </outcomeVariable>
        <outcomeVariable identifier="SCORE" cardinality="single" baseType="float">
            <value>0</value>
        </outcomeVariable>
        <outcomeVariable identifier="MAXSCORE" cardinality="single" baseType="float">
            <value>1</value>
        </outcomeVariable>
        <responseVariable identifier="RESPONSE" cardinality="single" baseType="string">
            <candidateResponse>
                <value>%s</value>
            </candidateResponse>
        </responseVariable>
    </itemResult>
</assessmentResult>
XML;
        $this->assertNotNull(Utils::findExternalNamespaces(sprintf($xml, Utils::valueAsString("160\u{0008}"))));
    }

    public function testremoveAllButFirstOccurrence(): void
    {
        $subject = 'abc 12 abc 345abc678abc';
        $toRemove = 'abc';
        $expected = 'abc 12  345678';
        self::assertSame(
            $expected,
            Utils::removeAllButFirstOccurrence($subject, $toRemove)
        );
    }
}
