<?php
/**
 * Class:  XmlUtil
 *
 * @author: Minh Duc Nguyen <dekarvn@gmail.com>
 */

namespace MinhD\Util;


class XmlUtil
{

    /**
     * Check if 2 xml strings are identical
     * If there's any difference, returns false
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public static function isIdentical($from, $to)
    {
        return sizeof(self::diff($from, $to)) == 0 ? true : false;
    }


    /**
     * Returns the differences between 2 XML
     * In the form of an array
     * Deep inspection including xml attributes
     *
     * @param $from
     * @param $to
     * @return array
     */
    public static function diff($from, $to)
    {
        return self::arrayRecursiveDiff(
            self::toArrayWithAttributes($from),
            self::toArrayWithAttributes($to)
        );
    }

    /**
     * Converts an xml string into an array representation
     *
     * @param $xmlString
     * @return mixed
     */
    public static function toArray($xmlString)
    {
        $xml = simplexml_load_string($xmlString, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        return $array;
    }

    /**
     * Convert an xml string into an array preserving attributes
     * When there are attributes
     *
     * @see DOMToArray
     * @param $xmlString
     * @return array|string
     */
    public static function toArrayWithAttributes($xmlString)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlString);
        $root = $doc->documentElement;
        $output = self::DOMToArray($root);
        $output['@root'] = $root->tagName;
        return $output;
    }

    /**
     * Recursively convert a DOM Node into an array
     * Helper file for toArrayWithAttributes
     *
     * @see toArrayWithAttributes
     * @param $node
     * @return array|string
     */
    public static function DOMToArray($node)
    {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i = 0, $m = $node->childNodes->length; $i < $m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = self::DOMToArray($child);
                    if (isset($child->tagName)) {
                        $t = $child->tagName;
                        if (!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    } elseif ($v || $v === '0') {
                        $output = (string)$v;
                    }
                }

                //Has attributes but isn't an array
                if ($node->attributes->length && !is_array($output)) {
                    //Change output into an array.
                    $output = array('@content' => $output);
                }

                if (is_array($output)) {
                    if ($node->attributes->length) {
                        $a = array();
                        foreach ($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string)$attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if (is_array($v) && count($v) == 1 && $t != '@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }
                break;
        }
        return $output;
    }


    /**
     * Recursively returns the differences betwen 2 arrays
     *
     * @param $from
     * @param $to
     * @return array
     */
    public static function arrayRecursiveDiff($from, $to)
    {
        if (sizeof($from) > sizeof($to)) {
            $smaller = $to;
            $bigger = $from;
            $arr1_is_big = 1;
            $arr2_is_big = 0;
        } elseif (sizeof($from) < sizeof($to)) {
            $smaller = $from;
            $bigger = $to;
            $arr1_is_big = 0;
            $arr2_is_big = 1;
        } else {
            $smaller = $from;
            $bigger = $to;
            $arr1_is_big = 0;
            $arr2_is_big = 0;
        }
        $result = array();

        foreach ($bigger as $key => $value) {
            if (is_array($smaller) && array_key_exists($key, $smaller)) {
                if (is_array($value)) {
                    $aRecursiveDiff = self::arrayRecursiveDiff($value, $smaller[$key]);
                    if (sizeof($aRecursiveDiff)) {
                        $result[$key] = $aRecursiveDiff;
                    }
                } else {
                    if ($value != $smaller[$key]) {
                        $result[$key] = $value;
                    }
                }
            } else {
                $result[$key] = $value;
            }
        }
        if ($arr1_is_big)
            $result["arr1_is_big"] = $arr1_is_big;

        if ($arr2_is_big)
            $result["arr2_is_big"] = $arr2_is_big;

        return ($result);
    }
}