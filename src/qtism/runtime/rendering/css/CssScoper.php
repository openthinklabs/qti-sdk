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
 * Copyright (c) 2014-2020 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\runtime\rendering\css;

use qtism\common\storage\MemoryStream;
use qtism\common\storage\MemoryStreamException;
use qtism\runtime\rendering\css\Utils as CssUtils;
use qtism\runtime\rendering\Renderable;
use qtism\runtime\rendering\RenderingException;

/**
 * The CssScoper aims at rescoping a CSS stylesheet to a specific element on an
 * identifier basis.
 */
class CssScoper implements Renderable
{
    public const RUNNING = 0;

    public const IN_ATRULE = 1;

    public const IN_ATRULESTRING = 2;

    public const IN_MAINCOMMENT = 3;

    public const IN_SELECTOR = 4;

    public const IN_CLASSBODY = 5;

    public const IN_CLASSSTRING = 6;

    public const IN_CLASSCOMMENT = 7;

    public const IN_ATRULEBODY = 8;

    public const CHAR_AT = '@';

    public const CHAR_DOUBLEQUOTE = '"';

    public const CHAR_TERMINATOR = ';';

    public const CHAR_ESCAPE = "\\";

    public const CHAR_TAB = "\t";

    public const CHAR_SPACE = ' ';

    public const CHAR_NEWLINE = "\n";

    public const CHAR_CARRIAGERETURN = "\r";

    public const CHAR_VERTICALTAB = "\v";

    public const CHAR_OPENINGBRACE = '{';

    public const CHAR_CLOSINGBRACE = '}';

    public const CHAR_STAR = '*';

    public const CHAR_SLASH = '/';

    /**
     * The current state.
     *
     * @var int
     */
    private $state = self::RUNNING;

    /**
     * The identifier used as a scope.
     *
     * @var string
     */
    private $id = '';

    /**
     * The stream to read.
     *
     * @var MemoryStream
     */
    private $stream;

    /**
     * The previously read char.
     *
     * @var string
     */
    private $previousChar = false;

    /**
     * The currently read char.
     *
     * @var string
     */
    private $currentChar = false;

    /**
     * The buffer.
     *
     * @var array
     */
    private $buffer;

    /**
     * The output string.
     *
     * @var string
     */
    private $output = '';

    /**
     * The previous state.
     *
     * @var int
     */
    private $previousState = false;

    /**
     * Whether map QTI classes to their qti-X CSS classes.
     *
     * @var bool
     */
    private $mapQtiClasses = false;

    /**
     * Whether map -qti-* like peuso classes to qti-X CSS classes.
     */
    private $mapQtiPseudoClasses = false;

    /**
     * @var bool Whether using the Web Component Friendly mode.
     */
    private $webComponentFriendly = false;

    /**
     * QTI classes to qti-* classes.
     *
     * An array containing a mapping between QTI class names
     * and their runtime XHTML rendering equivalent.
     *
     * This array is associative. Keys are the QTI class names
     * and values are their XHTML rendering equivalent.
     *
     * @var array
     */
    private static $qtiClassMapping = [
        // HTML components of QTI.
        'abbr' => 'qti-abbr',
        'acronym' => 'qti-acronym',
        'address' => 'qti-address',
        'blockquote' => 'qti-blockquote',
        'br' => 'qti-br',
        'cite' => 'qti-cite',
        'code' => 'qti-code',
        'dfn' => 'qti-dfn',
        'div' => 'qti-div',
        'em' => 'qti-em',
        'h1' => 'qti-h1',
        'h2' => 'qti-h2',
        'h3' => 'qti-h3',
        'h4' => 'qti-h4',
        'h5' => 'qti-h5',
        'h6' => 'qti-h6',
        'kbd' => 'qti-kbd',
        'p' => 'qti-p',
        'pre' => 'qti-pre',
        'q' => 'qti-q',
        'samp' => 'qti-samp',
        'span' => 'qti-span',
        'strong' => 'qti-strong',
        'var' => 'qti-var',
        'dl' => 'qti-dl',
        'dt' => 'qti-dt',
        'dd' => 'qti-dd',
        'ol' => 'qti-ol',
        'ul' => 'qti-ul',
        'li' => 'qti-li',
        'object' => 'qti-object',
        'param' => 'qti-param',
        'b' => 'qti-b',
        'big' => 'qti-big',
        'hr' => 'qti-hr',
        'i' => 'qti-i',
        'small' => 'qti-small',
        'sub' => 'qti-sub',
        'sup' => 'qti-sup',
        'tt' => 'qti-tt',
        'table' => 'qti-table',
        'caption' => 'qti-caption',
        'col' => 'qti-col',
        'colgroup' => 'qti-colgroup',
        'tbody' => 'qti-tbody',
        'td' => 'qti-td',
        'tfoot' => 'qti-tfoot',
        'th' => 'qti-th',
        'thead' => 'qti-thead',
        'tr' => 'qti-tr',
        'img' => 'qti-img',
        'a' => 'qti-a',

        // QTI Components considered to be safe CSS selector targets.
        'assessmentItem' => 'qti-assessmentItem',
        'itemBody' => 'qti-itemBody',
        'feedbackBlock' => 'qti-feedbackBlock',
        'feedbackInline' => 'qti-feedbackInline',
        'rubricBlock' => 'qti-rubricBlock',
        'printedVariable' => 'qti-printedVariable',
        'prompt' => 'qti-prompt',
        'choiceInteraction' => 'qti-choiceInteraction',
        'orderInteraction' => 'qti-orderInteraction',
        'simpleChoice' => 'qti-simpleChoice',
        'associateInteraction' => 'qti-associateInteraction',
        'matchInteraction' => 'qti-matchInteraction',
        'simpleAssociableChoice' => 'qti-simpleAssociableChoice',
        'gapMatchInteraction' => 'qti-gapMatchInteraction',
        'gap' => 'qti-gap',
        'gapText' => 'qti-gapText',
        'gapImg' => 'qti-gapImg',
        'inlineChoiceInteraction' => 'qti-inlineChoiceInteraction',
        'textEntryInteraction' => 'qti-textEntryInteraction',
        'extendedTextInteraction' => 'qti-extendedTextInteraction',
        'hottextInteraction' => 'qti-hottextInteraction',
        'hottext' => 'qti-hottext',
        'hotspotChoice' => 'qti-hotspotChoice',
        'associableHotspot' => 'qti-associableHotspot',
        'hotspotInteraction' => 'qti-hotspotInteraction',
        'selectPointInteraction' => 'qti-selectPointInteraction',
        'graphicOrderInteraction' => 'qti-graphicOrderInteraction',
        'graphicAssociateInteraction' => 'qti-graphicAssociateInteraction',
        'graphicGapMatchInteraction' => 'qti-graphicGapMatchInteraction',
        'positionObjectInteraction' => 'qti-positionObjectInteraction',
        'positionObjectStage' => 'qti-positionObjectStage',
        'sliderInteraction' => 'qti-sliderInteraction',
        'mediaInteraction' => 'qti-mediaInteraction',
        'drawingInteraction' => 'qti-drawingInteraction',
        'uploadInteraction' => 'qti-uploadInteraction',
        'customInteraction' => 'qti-customInteraction',
        'endAttemptInteraction' => 'qti-endAttemptInteraction',
        'infoControl' => 'qti-infoControl',
        'modalFeedback' => 'qti-modalFeedback',
        'templateInline' => 'qti-templateInline',
        'templateBlock' => 'qti-templateBlock',
    ];

    /**
     * aQTI classes to qti-* classes.
     *
     * An array containing a mapping between aQTI class names
     * and their runtime XHTML rendering equivalent.
     *
     * This array is associative. Keys are the aQTI class names
     * and values are their XHTML rendering equivalent.
     *
     * @var array
     */
    private static $wcFriendlyQtiClassMapping = [
        'qti-assessment-item' => 'qti-assessmentItem',
        'qti-item-body' => 'qti-itemBody',
        'qti-feedback-block' => 'qti-feedbackBlock',
        'qti-feedback-inline' => 'qti-feedbackInline',
        'qti-rubric-block' => 'qti-rubricBlock',
        'qti-printed-variable' => 'qti-printedVariable',
        'qti-prompt' => 'qti-prompt',
        'qti-choice-interaction' => 'qti-choiceInteraction',
        'qti-order-interaction' => 'qti-orderInteraction',
        'qti-simple-choice' => 'qti-simpleChoice',
        'qti-associate-interaction' => 'qti-associateInteraction',
        'qti-match-interaction' => 'qti-matchInteraction',
        'qti-simple-associable-choice' => 'qti-simpleAssociableChoice',
        'qti-gap-match-interaction' => 'qti-gapMatchInteraction',
        'qti-gap' => 'qti-gap',
        'qti-gap-text' => 'qti-gapText',
        'qti-gap-img' => 'qti-gapImg',
        'qti-inline-choice-interaction' => 'qti-inlineChoiceInteraction',
        'qti-text-entry-interaction' => 'qti-textEntryInteraction',
        'qti-extended-text-interaction' => 'qti-extendedTextInteraction',
        'qti-hottext-interaction' => 'qti-hottextInteraction',
        'qti-hottext' => 'qti-hottext',
        'qti-hotspot-choice' => 'qti-hotspotChoice',
        'qti-associable-hotspot' => 'qti-associableHotspot',
        'qti-hotspot-interaction' => 'qti-hotspotInteraction',
        'qti-select-point-interaction' => 'qti-selectPointInteraction',
        'qti-graphic-order-interaction' => 'qti-graphicOrderInteraction',
        'qti-graphic-associate-interaction' => 'qti-graphicAssociateInteraction',
        'qti-graphic-gap-match-interaction' => 'qti-graphicGapMatchInteraction',
        'qti-position-object-interaction' => 'qti-positionObjectInteraction',
        'qti-position-object-stage' => 'qti-positionObjectStage',
        'qti-slider-interaction' => 'qti-sliderInteraction',
        'qti-media-interaction' => 'qti-mediaInteraction',
        'qti-drawing-interaction' => 'qti-drawingInteraction',
        'qti-upload-interaction' => 'qti-uploadInteraction',
        'qti-custom-interaction' => 'qti-customInteraction',
        'qti-end-attempt-interaction' => 'qti-endAttemptInteraction',
        'qti-info-control' => 'qti-infoControl',
        'qti-modal-feedback' => 'qti-modalFeedback',
        'qti-template-inline' => 'qti-templateInline',
        'qti-template-block' => 'qti-templateBlock',
    ];

    /**
     * -qti-* pseudo classes to CSS class map.
     *
     * @var array
     */
    private static $qtiPseudoClassMapping = [
        'qti-selected' => 'qti-selected',
    ];

    /**
     * Create a new CssScoper object.
     *
     * @param bool $mapQtiClasses Whether to map QTI classes (e.g. simpleChoice) to their qti-X CSS class equivalent. Default is false.
     * @param bool $mapQtiPseudoClasses Whether to map QTI pseudo classes (e.g. -qti-selected) to their qti-X CSS class equivalent. Default is false.
     */
    public function __construct($mapQtiClasses = false, $mapQtiPseudoClasses = false)
    {
        $this->mapQtiClasses($mapQtiClasses);
        $this->mapQtiPseudoClasses($mapQtiPseudoClasses);
    }

    /**
     * Whether or not QTI classes are mapped to their qti-X CSS class equivalent.
     *
     * @return bool
     */
    public function doesMapQtiClasses(): bool
    {
        return $this->mapQtiClasses;
    }

    /**
     * Whether or not map QTI classes to their qti-X CSS class equivalent.
     *
     * @param bool $mapQtiClasses
     */
    public function mapQtiClasses($mapQtiClasses): void
    {
        $this->mapQtiClasses = $mapQtiClasses;
    }

    /**
     * Whether or not QTI pseudo classes are mapped to their QTI-X CSS class equivalent.
     *
     * @return bool
     */
    public function doesMapQtiPseudoClasses(): bool
    {
        return $this->mapQtiPseudoClasses;
    }

    /**
     * Whether or not map QTI pseudo classes to their QTI-X CSS class equivalent.
     *
     * @param bool $mapQtiPseudoClasses
     */
    public function mapQtiPseudoClasses($mapQtiPseudoClasses): void
    {
        $this->mapQtiPseudoClasses = $mapQtiPseudoClasses;
    }

    /**
     * @param $webComponentFriendly
     */
    public function setWebComponentFriendly($webComponentFriendly): void
    {
        $this->webComponentFriendly = $webComponentFriendly;
    }

    /**
     * @return bool
     */
    public function isWebComponentFriendly(): bool
    {
        return $this->webComponentFriendly;
    }

    /**
     * Rescope the content of a given CSS file.
     *
     * @param string $file The path to the file that has to be rescoped.
     * @param string $id The scope identifier. If not given, will be randomly generated.
     * @return string The rescoped content of $file.
     * @throws MemoryStreamException
     * @throws RenderingException If something goes wrong while rescoping the content.
     */
    public function render($file, $id = ''): string
    {
        if (empty($id)) {
            $id = uniqid();
        }

        $this->init($id, $file);

        $stream = $this->getStream();

        while ($stream->eof() === false) {
            try {
                $char = $stream->read(1);
                $this->beforeCharReading($char);

                switch ($this->getState()) {
                    case self::RUNNING:
                        $this->runningState();
                        break;

                    case self::IN_ATRULE:
                        $this->inAtRuleState();
                        break;

                    case self::IN_ATRULESTRING:
                        $this->inAtRuleStringState();
                        break;

                    case self::IN_SELECTOR:
                        $this->inSelectorState();
                        break;

                    case self::IN_CLASSBODY:
                        $this->inClassbodyState();
                        break;

                    case self::IN_MAINCOMMENT:
                        $this->inMainCommentState();
                        break;

                    case self::IN_CLASSSTRING:
                        $this->inClassStringState();
                        break;

                    case self::IN_CLASSCOMMENT:
                        $this->inClassCommentState();
                        break;

                    case self::IN_ATRULEBODY:
                        $this->inAtRuleBodyState();
                        break;
                }

                $this->afterCharReading($char);
            } catch (MemoryStreamException $e) {
                $stream->close();
                $msg = "An unexpected error occurred while reading the CSS file '{$file}'.";
                throw new RenderingException($msg, RenderingException::RUNTIME, $e);
            }
        }

        $stream->close();

        return $this->getOutput();
    }

    /**
     * Initialize the object to be ready for a new rescoping.
     *
     * @param string $id The identifier to be used for scoping.
     * @param string $file The path to the CSS file to be scoped.
     * @throws MemoryStreamException
     * @throws RenderingException
     */
    protected function init($id, $file): void
    {
        $this->setState(self::RUNNING);
        $this->setId($id);
        $this->setBuffer([]);
        $this->setOutput('');
        $this->setPreviousChar(false);

        if (($data = @file_get_contents($file)) !== false) {
            $stream = new MemoryStream($data);
            $stream->open();
            $this->setStream($stream);
        } else {
            throw new RenderingException("The CSS file '{$file}' could not be open.", RenderingException::RUNTIME);
        }
    }

    /**
     * Set the current state.
     *
     * @param int $state
     */
    protected function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * Get the current state.
     *
     * @return int
     */
    protected function getState(): int
    {
        return $this->state;
    }

    /**
     * Get the current id used as a scope.
     *
     * @param string $id
     */
    protected function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * Get the current id used as a scope.
     *
     * @return string
     */
    protected function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the stream to be read.
     *
     * @param MemoryStream $stream
     */
    protected function setStream(MemoryStream $stream): void
    {
        $this->stream = $stream;
    }

    /**
     * Get the stream to be read.
     *
     * @return MemoryStream
     */
    protected function getStream(): MemoryStream
    {
        return $this->stream;
    }

    /**
     * Instructions to perform before any character read.
     *
     * @param string $char
     */
    protected function beforeCharReading($char): void
    {
        $this->setCurrentChar($char);
    }

    /**
     * Instructions to perform after any character read.
     *
     * @param string $char
     */
    protected function afterCharReading($char): void
    {
        $this->setPreviousChar($char);
    }

    /**
     * Get the previously read character.
     *
     * @return string
     */
    protected function getPreviousChar(): string
    {
        return $this->previousChar;
    }

    /**
     * Set the previously read character.
     *
     * @param string $char
     */
    protected function setPreviousChar($char): void
    {
        $this->previousChar = $char;
    }

    /**
     * Set the current char.
     *
     * @param string $char
     */
    protected function setCurrentChar($char): void
    {
        $this->currentChar = $char;
    }

    /**
     * Get the current char.
     *
     * @return string $char A char or false if no current char is set.
     */
    protected function getCurrentChar(): string
    {
        return $this->currentChar;
    }

    /**
     * Get the array containing a mapping between QTI class names
     * and their runtime XHTML rendering equivalent.
     *
     * This array is associative. Keys are the QTI class names
     * and values are their XHTML rendering equivalent.
     *
     * @return array
     */
    protected static function getQtiClassMapping(): array
    {
        return self::$qtiClassMapping;
    }

    /**
     * Get the array containing a mapping between QTI pseudo classes
     * and their runtime XHTML rendering equivalent.
     *
     * This array is associative. Keys are the QTI pseudo class names
     * and values are their XHTML rendering equivalent.
     *
     * @return array
     */
    protected static function getQtiPseudoClassMapping(): array
    {
        return self::$qtiPseudoClassMapping;
    }

    /**
     * Instructions to be performed in 'running' state.
     */
    protected function runningState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_AT) {
            $this->setState(self::IN_ATRULE);
            $this->bufferize($char);
            $this->output($char);
        } elseif ($char === self::CHAR_STAR && $this->getPreviousChar() === self::CHAR_SLASH) {
            $this->setState(self::IN_MAINCOMMENT);
            $this->output($char);
        } elseif ($char === self::CHAR_SLASH) {
            $this->output($char);
        } elseif (self::isWhiteSpace($char) === false && $char !== self::CHAR_CLOSINGBRACE) {
            $this->bufferize($char);
            $this->setState(self::IN_SELECTOR);
        } else {
            $this->output($char);
        }
    }

    /**
     * Instructions to be performed in 'atRule' state.
     */
    protected function inAtRuleState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_DOUBLEQUOTE) {
            $this->setState(self::IN_ATRULESTRING);
        } elseif ($char === self::CHAR_TERMINATOR) {
            $this->setState(self::RUNNING);
            $this->cleanBuffer();
        } elseif ($char === self::CHAR_OPENINGBRACE && (($buffer = implode('', $this->getBuffer())) && (strpos($buffer, '@media') !== false || strpos($buffer, '@supports') !== false))) {
            $this->setState(self::RUNNING);
            $this->cleanBuffer();
        } elseif ($char === self::CHAR_OPENINGBRACE) {
            $this->setState(self::IN_ATRULEBODY);
            $this->cleanBuffer();
            $this->bufferize($char);
        } else {
            $this->bufferize($char);
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'atRuleString' state.
     */
    protected function inAtRuleStringState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_DOUBLEQUOTE && $this->isEscaping() === false) {
            $this->cleanBuffer();
            $this->setState(self::IN_ATRULE);
        } elseif ($char === self::CHAR_ESCAPE) {
            $this->bufferize($char);
        } else {
            $this->cleanBuffer();
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'atRuleBody' state.
     */
    protected function inAtRuleBodyState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_CLOSINGBRACE) {
            $buffer = implode('', $this->getBuffer());
            $openingCount = substr_count($buffer, self::CHAR_OPENINGBRACE);
            $closingCount = substr_count($buffer, self::CHAR_CLOSINGBRACE) + 1;

            if ($openingCount === $closingCount) {
                $this->cleanBuffer();
                $this->setState(self::RUNNING);
            } else {
                $this->bufferize($char);
            }
        } else {
            $this->bufferize($char);
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'selector' state.
     */
    protected function inSelectorState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_OPENINGBRACE) {
            $this->updateSelector();
            $this->cleanBuffer();
            $this->setState(self::IN_CLASSBODY);
        } else {
            $this->bufferize($char);
        }
    }

    /**
     * Instructions to be performed in 'classBody' state.
     */
    protected function inClassBodyState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_DOUBLEQUOTE) {
            $this->setState(self::IN_CLASSSTRING);
        } elseif ($char === self::CHAR_CLOSINGBRACE) {
            $this->setState(self::RUNNING);
        } elseif ($char === self::CHAR_STAR && $this->getPreviousChar() === self::CHAR_SLASH) {
            $this->setState(self::IN_CLASSCOMMENT);
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'mainComment' state.
     */
    protected function inMainCommentState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_SLASH && $this->getPreviousChar() === self::CHAR_STAR) {
            $this->setState(self::RUNNING);
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'classComment' state.
     */
    protected function inClassCommentState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_SLASH && $this->getPreviousChar() === self::CHAR_STAR) {
            $this->setState(self::IN_CLASSBODY);
        }

        $this->output($char);
    }

    /**
     * Instructions to be performed in 'classString' state.
     */
    protected function inClassStringState(): void
    {
        $char = $this->getCurrentChar();

        if ($char === self::CHAR_DOUBLEQUOTE && $this->isEscaping() === false) {
            $this->cleanBuffer();
            $this->setState(self::IN_CLASSBODY);
        } elseif ($char === self::CHAR_ESCAPE) {
            $this->bufferize($char);
        } else {
            $this->cleanBuffer();
        }

        $this->output($char);
    }

    /**
     * Whether a given $char is considered to be white space.
     *
     * @param string $char
     * @return bool
     */
    private static function isWhiteSpace($char): bool
    {
        return $char === self::CHAR_SPACE || $char === self::CHAR_CARRIAGERETURN || $char === self::CHAR_NEWLINE || $char === self::CHAR_TAB || $char === self::CHAR_VERTICALTAB;
    }

    /**
     * Get the read buffer.
     *
     * @return array
     */
    protected function getBuffer(): array
    {
        return $this->buffer;
    }

    /**
     * Set the read buffer.
     *
     * @param array $buffer
     */
    protected function setBuffer(array $buffer): void
    {
        $this->buffer = $buffer;
    }

    /**
     * Clean the read buffer.
     */
    protected function cleanBuffer(): void
    {
        $this->setBuffer([]);
    }

    /**
     * Put a given $char in the read buffer.
     *
     * @param string $char
     */
    protected function bufferize($char): void
    {
        $buffer = $this->getBuffer();
        $buffer[] = $char;
        $this->setBuffer($buffer);
    }

    /**
     * Set the output.
     *
     * @param string $output
     */
    protected function setOutput($output): void
    {
        $this->output = $output;
    }

    /**
     * Get the output.
     *
     * @return string
     */
    protected function getOutput(): string
    {
        return $this->output;
    }

    /**
     * Output a given $char as a scoping result.
     *
     * @param string $char
     */
    protected function output($char): void
    {
        $output = $this->getOutput();
        $output .= $char;
        $this->setOutput($output);
    }

    /**
     * Whether the current char is escaping something.
     *
     * @return bool
     */
    protected function isEscaping(): bool
    {
        $count = count($this->getBuffer());

        if ($count === 0) {
            return false;
        }

        return $count % 2 !== 0;
    }

    /**
     * Update selector implementation.
     *
     * Update the currently processed CSS selector by prefixing it
     * with the appropriate id.
     */
    protected function updateSelector(): void
    {
        $buffer = implode('', $this->getBuffer());
        $qtiClassMap = ($this->isWebComponentFriendly()) ? array_merge(self::$qtiClassMapping, self::$wcFriendlyQtiClassMapping) : self::$qtiClassMapping;

        if (strpos($buffer, ',') === false) {
            // Do not rescope if already scoped!
            if (strpos($buffer, '#' . $this->getId()) === false) {
                $buffer = ($this->doesMapQtiClasses() === true) ? CssUtils::mapSelector($buffer, $qtiClassMap) : $buffer;
                $buffer = ($this->doesMapQtiPseudoClasses() === true) ? CssUtils::mapPseudoClasses($buffer, self::getQtiPseudoClassMapping()) : $buffer;
                $this->output('#' . $this->getId() . ' ' . $buffer . '{');
            } else {
                $buffer = ($this->doesMapQtiPseudoClasses() === true) ? CssUtils::mapPseudoClasses($buffer, self::getQtiPseudoClassMapping()) : $buffer;
                $this->output($buffer . '{');
            }
        } else {
            $classes = explode(',', $buffer);
            $newClasses = [];

            foreach ($classes as $c) {
                // Same as above, do not rescope if already scoped...
                if (strpos($c, '#' . $this->getId()) === false) {
                    $c = ($this->doesMapQtiClasses() === true) ? CssUtils::mapSelector($c, $qtiClassMap) : $c;
                    $newC = '#' . $this->getId() . ' ' . trim($c);
                    $newC = str_replace(trim($c), $newC, $c);
                } else {
                    $newC = $c;
                }

                $newClasses[] = $newC;
            }

            $buffer = implode(',', $newClasses);
            $buffer = ($this->doesMapQtiPseudoClasses() === true) ? CssUtils::mapPseudoClasses($buffer, self::getQtiPseudoClassMapping()) : $buffer;
            $this->output($buffer . '{');
        }
    }
}
