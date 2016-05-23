<?php

use MinhD\Util\XmlUtil as util;

class XmlUtilTest extends PHPUnit_Framework_TestCase
{

    /**
     * todo test for exactly the same xml -> same
     * todo test for reorder elements xml -> same
     * todo test for adding 1 element -> diff
     * todo test for adding 1 attribute -> diff
     * todo test for removing elements and removing attribute -> diff
     * todo test for invalid xml
     * todo test for changing 1 attribute
     * todo unit test toArray
     * todo unit test isIdentical
     * todo unit test toArrayWithAttributes
     * todo unit test DOMToArray
     * todo unit test arrayRecursiveDiff
     */
    public function testSample()
    {

        $xml1 = <<<XML
<?xml version='1.0'?>
<document>
 <title>Forty What?</title>
 <from attr="gone">Joe</from>
 <to attr="old">Jane</to>
 <body>
  I know that's the answer -- but what's the question?
 </body>
</document>
XML;

        $xml2 = <<<XML
<?xml version='1.0'?>
<document>
<from>Joe</from>
 <to attr="new">Jane</to>
 <titles>
 <title>
 <name>Fish2</name>
 <name>Fish</name>
</title>

</titles>
 <title>Forty What?</title>

 <body>
  I know that's the answer -- but what's the question?
 </body>
</document>
XML;

//        var_dump(util::toArrayWithAttributes($xml2));

        var_dump(util::diff($xml2, $xml1));

    }
}