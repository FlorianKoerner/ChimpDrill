<?php

namespace FlorianKoerner\ChimpDrill\Tests;

use FlorianKoerner\ChimpDrill\ChimpDrill;

/**
 * Test filter merge tags
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                'message' => 'Beautiful image: *|HTML:IMAGE|*',
                'placeholder' => array(
                    'IMAGE' => '<img src="holiday.jpg" />'
                ),
                'expected' => 'Beautiful image: <img src="holiday.jpg" />'
            ),
            array(
                'message' => 'You are watching: *|TITLE:MOVIE|*',
                'placeholder' => array(
                    'MOVIE' => 'HigH SchooL MUSICAL 3'
                ),
                'expected' => 'You are watching: High School Musical 3'
            ),
            array(
                'message' => 'Soo *|LOWER:TYPE|*',
                'placeholder' => array(
                    'TYPE' => 'LowerCase'
                ),
                'expected' => 'Soo lowercase'
            ),
            array(
                'message' => 'Sorry, you are so *|UPPER:WEIGHT|* - I can\'t believe it.',
                'placeholder' => array(
                    'WEIGHT' => 'fat'
                ),
                'expected' => 'Sorry, you are so FAT - I can\'t believe it.'
            ),
            array(
                'message' => 'Sorry, you are so *|UPPER:WEIGHT|* - I can\'t believe it.',
                'placeholder' => array(
                    'UPPER:WEIGHT' => 'thin',
                    'WEIGHT' => 'fat'
                ),
                'expected' => 'Sorry, you are so FAT - I can\'t believe it.'
            ),
        );
    }

    /**
     * @param string $message
     * @param array  $placeholder
     * @param string $expected
     *
     * @dataProvider dataProvider
     */
    public function testFilterMergeTags($message, array $placeholder, $expected)
    {
        $this->assertEquals($expected, (string) new ChimpDrill($message, $placeholder));
    }
}