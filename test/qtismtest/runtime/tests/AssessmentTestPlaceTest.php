<?php

namespace qtismtest\runtime\tests;

use qtism\runtime\tests\AssessmentTestPlace;
use qtismtest\QtiSmEnumTestCase;

/**
 * Class AssessmentTestPlaceTest
 */
class AssessmentTestPlaceTest extends QtiSmEnumTestCase
{
    /**
     * @return string
     */
    protected function getEnumerationFqcn(): string
    {
        return AssessmentTestPlace::class;
    }

    /**
     * @return array
     */
    protected function getNames(): array
    {
        return [
            'testPart',
            'assessmentSection',
            'assessmentItem',
            'assessmentTest',
        ];
    }

    /**
     * @return array
     */
    protected function getKeys(): array
    {
        return [
            'TEST_PART',
            'ASSESSMENT_SECTION',
            'ASSESSMENT_ITEM',
            'ASSESSMENT_TEST',
        ];
    }

    /**
     * @return array
     */
    protected function getConstants(): array
    {
        return [
            AssessmentTestPlace::TEST_PART,
            AssessmentTestPlace::ASSESSMENT_SECTION,
            AssessmentTestPlace::ASSESSMENT_ITEM,
            AssessmentTestPlace::ASSESSMENT_TEST,
        ];
    }
}
