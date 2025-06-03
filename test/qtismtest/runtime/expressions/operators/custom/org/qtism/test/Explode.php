<?php

namespace org\qtism\test;

use qtism\common\datatypes\QtiString;
use qtism\common\enums\BaseType;
use qtism\runtime\common\OrderedContainer;
use qtism\runtime\expressions\operators\CustomOperatorProcessor;
use qtism\runtime\expressions\operators\OperandsCollection;
use qtism\runtime\expressions\operators\OperatorProcessingException;

/**
 * Class Explode
 */
class Explode extends CustomOperatorProcessor
{
    /**
     * @param OperandsCollection $operands
     */
    public function setOperands(OperandsCollection $operands): void
    {
        $count = count($operands);

        if ($count === 0) {
            $msg = "The 'org.qtism.test.Explode' custom operator implementation requires 1 operand. None given.";
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::NOT_ENOUGH_OPERANDS);
        } elseif ($count > 1) {
            $msg = "The 'org.qtism.test.Explode' custom operator implementation requires 1 operand. {$count} given.";
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::TOO_MUCH_OPERANDS);
        } else {
            parent::setOperands($operands);
        }
    }

    /**
     * @return array|OrderedContainer|string
     */
    public function process()
    {
        $operands = $this->getOperands();

        if ($operands->containsNull() === true) {
            return '';
        } elseif ($operands->exclusivelySingle() === false) {
            $msg = "The 'org.qtism.test.Explode' custom operator only accepts operands with single cardinality.";
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_CARDINALITY);
        } elseif ($operands->exclusivelyString() === false) {
            $msg = "The 'org.qtism.test.Explode' custom operator only accepts operands string baseType.";
            throw new OperatorProcessingException($msg, $this, OperatorProcessingException::WRONG_BASETYPE);
        }

        $operand = $operands[0];

        // Get the delimiter.
        $xml = $this->getExpression()->getXml();
        $delimiter = $xml->documentElement->getAttributeNS('http://www.qtism.org/xsd/custom_operators/explode', 'delimiter');

        $strings = explode($delimiter, $operand->getValue());

        $ordered = new OrderedContainer(BaseType::STRING);
        foreach ($strings as $str) {
            $ordered[] = new QtiString($str);
        }

        return $ordered;
    }
}
