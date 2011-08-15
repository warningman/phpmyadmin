<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * tests for Advisor class
 *
 * @package phpMyAdmin-test
 */

/*
 * Include to test.
 */
require_once 'libraries/Advisor.class.php';
require_once 'libraries/php-gettext/gettext.inc';
require_once 'libraries/url_generating.lib.php';
require_once 'libraries/core.lib.php';

class Advisor_test extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider escapeStrings
     */
    public function testEscape($text, $expected)
    {
        $this->assertEquals(Advisor::escapePercent($text), $expected);
    }

    public function escapeStrings() {
        return array(
            array('80%', '80%%'),
            array('%s%', '%s%%'),
            array('80% foo', '80%% foo'),
            array('%s% foo', '%s%% foo'),
            );
    }

    public function testParse()
    {
        $advisor = new Advisor();
        $parseResult = $advisor->parseRulesFile();
        $this->assertEquals($parseResult['errors'], array());
    }

    /**
     * @depends testParse
     * @dataProvider rulesProvider
     */
    public function testAddRule($rule, $expected)
    {
        $advisor = new Advisor();
        $parseResult = $advisor->parseRulesFile();
        $this->assertEquals($parseResult['errors'], array());
        $this->variables['value'] = 0;
        $advisor->addRule('fired', $rule);
        $this->assertEquals($advisor->runResult['fired'], array($expected));
    }

    public function rulesProvider()
    {
        return array(
            array(
                array('justification' => 'foo', 'name' => 'name', 'issue' => 'issue', 'recommendation' => 'Recommend'),
                array('justification' => 'foo', 'name' => 'name', 'issue' => 'issue', 'recommendation' => 'Recommend'),
            ),
        );
    }
}
?>
