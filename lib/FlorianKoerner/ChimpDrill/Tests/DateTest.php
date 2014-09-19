<?php

namespace FlorianKoerner\ChimpDrill\Tests;

use FlorianKoerner\ChimpDrill\ChimpDrill;

class DateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                'message' => '&copy; *|DATE:Y|* by my company',
                'placeholder' => array(),
                'expected' => '&copy; ' . date('Y') . ' by my company'
            ),
            array(
                'message' => '&copy; *|DATE:Y\m|* by my company',
                'placeholder' => array(),
                'expected' => '&copy; ' . date('Y\m') . ' by my company'
            ),
            array(
                'message' => '&copy; *|DATE:Y|* by my company',
                'placeholder' => array(
                    'DATE' => '2000-01-01 00:00:00'
                ),
                'expected' => '&copy; ' . date('Y') . ' by my company'
            )
        );
    }

    /**
     * @param string $message
     * @param array  $placeholder
     * @param string $expected
     *
     * @dataProvider dataProvider
     */
    public function testDateMergeTags($message, array $placeholder, $expected)
    {
        $this->assertEquals($expected, (string) new ChimpDrill($message, $placeholder));
    }
}