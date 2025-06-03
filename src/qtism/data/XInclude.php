<?php

namespace qtism\data;

use qtism\data\content\BlockStatic;
use qtism\data\content\FlowStatic;
use qtism\data\content\FlowTrait;
use qtism\data\content\InlineStatic;
use qtism\data\rules\OutcomeRule;
use qtism\data\rules\ResponseRule;

/**
 * From IMS QTI:
 *
 * Fragments are included using the Xinclude mechanism. (See [XINCLUDE].) The instance of include is treated as if
 * it was actually an instance of the root element of the fragment referred to by the href attribute of the include
 * element. For the purposes of this specification the xpointer mechanism defined by the XInclude specification must
 * not be used. Also, all included fragments must be treated as parsed xml.
 *
 * This technique is similar to the inclusion of media objects (using object) but allows the inclusion of data that
 * conforms to this specification, specifically, it allows the inclusion of interactions, static content, processing
 * rules or, at test level whole sections, to be included from externally defined fragments.
 *
 * When including externally defined fragments the content of the fragment must satisfy the requirements of the
 * specification in the context of the item in which it is being included. For example, interactions included
 * from fragments must be correctly bound to response variables declared in the items.
 */
class XInclude extends ExternalQtiComponent implements BlockStatic, FlowStatic, InlineStatic, OutcomeRule, ResponseRule
{
    use FlowTrait;

    /**
     * Create a new XInclude object.
     *
     * @param string $xmlString The XML Content of the node.
     */
    public function __construct($xmlString)
    {
        parent::__construct($xmlString);
    }

    /**
     * Get the value of the xi:href attribute in the XML content.
     *
     * This is a convenience method.
     *
     * @return string
     */
    public function getHref(): string
    {
        $xml = $this->getXml();
        return $xml->documentElement->getAttribute('href');
    }

    /**
     * @return string
     */
    public function getQtiClassName(): string
    {
        return 'include';
    }

    protected function buildTargetNamespace(): void
    {
        $this->setTargetNamespace('http://www.w3.org/2001/XInclude');
    }
}
