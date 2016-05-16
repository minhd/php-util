<?php

/**
 * Class:  TestStringUtil
 *
 * @author: Minh Duc Nguyen <minh.nguyen@ands.org.au>
 */
use MinhD\Util\StringUtil as SU;

class StringUtilTest extends PHPUnit_Framework_TestCase
{
    public function testIsString()
    {
        $this->assertTrue(SU::isString("String"));
        $this->assertFalse(SU::isString(true));
    }

    public function testIsIp()
    {
        $this->assertTrue(SU::isIp("115.42.150.37"));
        $this->assertTrue(SU::isIp("115.42.150.38"));
        $this->assertTrue(SU::isIp("115.42.150.50"));
        $this->assertTrue(SU::isIp("192.168.0.1"));
        $this->assertTrue(SU::isIp("110.234.52.124"));
    }

    public function testIsNotIp()
    {
        $this->assertFalse(SU::isIp("210.110"));
        //must have 4 octets
        $this->assertFalse(SU::isIp("255"));
        //must have 4 octets
        $this->assertFalse(SU::isIp("y.y.y.y"));
        //only digits are allowed
        $this->assertFalse(SU::isIp("255.0.0.y"));
        //only digits are allowed
        $this->assertFalse(SU::isIp("666.10.10.20"));
        //octet number must be between [0-255]
        $this->assertFalse(SU::isIp("4444.11.11.11"));
        //octet number must be between [0-255]
        $this->assertFalse(SU::isIp("33.3333.33.3"));
        //octet number must be between [0-255]
    }

    public function testStartsWith()
    {
        $this->assertTrue(SU::startsWith("abc", "a"));
        $this->assertTrue(SU::startsWith("The brown", "The b"));
        $this->assertTrue(SU::beginsWith("abc", "a"));
    }

    public function testEndsWith()
    {
        $this->assertTrue(SU::endsWith("abc", "c"));
        $this->assertTrue(SU::endsWith("The brown", "brown"));
    }

    public function testIsCreditCard()
    {
        $this->asserttrue(SU::isCreditCard("4111 1111 1111 1111"));
        $this->asserttrue(SU::isCreditCard("4111-1111-1111-1111"));
        $this->asserttrue(SU::isCreditCard("4111111111111111"));
    }

    public function testCamelize()
    {
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment class name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment class Name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment Class name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment Class Name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("Equipment class name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("Equipment class Name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("Equipment Class name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("Equipment Class Name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment className"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipment ClassName"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("Equipment ClassName"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipmentClass name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("equipmentClass Name"));
        $this->assertEquals("equipmentClassName",
            SU::camelize("EquipmentClass Name"));
    }

    /**
     * todo
     */
    public function testReplaceAll()
    {
    }

    /**
     * todo
     */
    public function testGetCreditCardType()
    {
    }

    public function __construct()
    {
        parent::__construct();
        require_once(dirname(__FILE__) . "/../vendor/autoload.php");
    }
}