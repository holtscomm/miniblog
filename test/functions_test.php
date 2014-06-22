<?php

include __DIR__ . '/../src/includes/functions.php';

class GetValueTests extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->test_array = array(
            "stuff" => "junk",
            "juff" => "stunk"
        );
    }

    public function test_get_value_for_non_existent_index_returns_default()
    {
        $expected = false;

        $actual = get_value($this->test_array, "poop", $expected);

        $this->assertEquals($expected, $actual);
    }

    public function test_get_value_for_existing_index_returns_expected()
    {
        $expected = "junk";

        $actual = get_value($this->test_array, "stuff", false);

        $this->assertEquals($expected, $actual);
    }
}
