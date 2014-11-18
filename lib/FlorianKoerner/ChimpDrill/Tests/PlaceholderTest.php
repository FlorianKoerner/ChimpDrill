<?php

namespace FlorianKoerner\ChimpDrill\Tests;

use FlorianKoerner\ChimpDrill\ChimpDrill;

/**
 * Test placeholder merge tags
 */
class PlaceholderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                'message' => 'Hello *|BEST_FRIEND|*',
                'placeholder' => array(
                    'BEST_FRIEND' => 'John Doe'
                ),
                'expected' => 'Hello John Doe'
            ),
            array(
                'message' => 'Your name is *|YOUR_NAME|* & my name is *|MY_NAME|*.',
                'placeholder' => array(
                    'MY_NAME' => 'John Doe',
                    'YOUR_NAME' => 'Jane Doe'
                ),
                'expected' => 'Your name is Jane Doe & my name is John Doe.'
            ),
            array(
                'message' => 'You live in *|NYC|* *|AND|* I live in *|NYC|*. And we love *|FOOD|*.',
                'placeholder' => array(
                    'NYC' => 'New York City',
                    'FOOD' => 'fish and chips',
                    'AND' => '&'
                ),
                'expected' => 'You live in New York City &amp; I live in New York City. And we love fish and chips.'
            ),
            array(
                'message' => 'This is *|NOT:ALLOWED|*. And this *|PLACEHOLDER_NAME|* is missing, *|NAME|*.',
                'placeholder' => array(
                    'NOT:ALLOWED' => 'Sure?',
                    'NAME' => 'John'
                ),
                'expected' => 'This is *|NOT:ALLOWED|*. And this *|PLACEHOLDER_NAME|* is missing, John.'
            ),
            array(
                'message' => 'German umlauts: *|UMLAUTS|*',
                'placeholder' => array(
                    'UMLAUTS' => 'äöü'
                ),
                'expected' => 'German umlauts: äöü'
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
    public function testMergeTags($message, array $placeholder, $expected)
    {
        $this->assertEquals($expected, (string) new ChimpDrill($message, $placeholder));
    }
}