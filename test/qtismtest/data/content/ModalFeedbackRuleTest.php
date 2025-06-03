<?php

namespace qtismtest\data\content;

use InvalidArgumentException;
use qtism\data\content\ModalFeedbackRule;
use qtism\data\ShowHide;
use qtismtest\QtiSmTestCase;

/**
 * Class ModalFeedbackRuleTest
 */
class ModalFeedbackRuleTest extends QtiSmTestCase
{
    public function testCreateWrongOutcomeIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The 'outcomeIdentifier' argument must be a valid QTI identifier, '999' given.");
        $modalFeedbackRule = new ModalFeedbackRule(999, ShowHide::SHOW, 'IDENTIFIER', 'Title');
    }

    public function testCreateWrongShowHide(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The 'showHide' argument must be a value from the ShowHide enumeration, 'boolean' given.");
        $modalFeedbackRule = new ModalFeedbackRule('OUTCOME', true, 'IDENTIFIER', 'Title');
    }

    public function testCreateWrongIdentifier(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The 'identifier' argument must be a valid QTI identifier, '999' given.");
        $modalFeedbackRule = new ModalFeedbackRule('OUTCOME', ShowHide::SHOW, 999, 'Title');
    }

    public function testCreateWrongTitle(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The 'title' argument must be a string, 'boolean' given.");
        $modalFeedbackRule = new ModalFeedbackRule('OUTCOME', ShowHide::SHOW, 'IDENTIFIER', false);
    }
}
