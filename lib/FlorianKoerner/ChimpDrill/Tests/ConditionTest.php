<?php

namespace FlorianKoerner\ChimpDrill\Tests;

use FlorianKoerner\ChimpDrill\ChimpDrill;

class ConditionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                'message' => '*|IF:HAPPY|*We are Happy.*|ELSE:|*We are very sad. But why?*|END:IF|*',
                'placeholder' => array(),
                'expected' => 'We are very sad. But why?'
            ),
            array(
                'message' => '*|IF:HAPPY|*We are Happy.*|ELSE:|*We are very sad. But why?*|END:IF|*',
                'placeholder' => array(
                    'HAPPY' => false
                ),
                'expected' => 'We are very sad. But why?'
            ),
            array(
                'message' => '*|IF:HAPPY|*We are Happy.*|ELSE:|*We are very sad. But why?*|END:IF|*',
                'placeholder' => array(
                    'HAPPY' => true
                ),
                'expected' => 'We are Happy.'
            ),
            array(
                'message' => 'Our happiness is *|IF:HAPPINESS > 75|*so high*|ELSEIF:HAPPINESS > 50|*ok*|ELSE:|*- wo don\'t want to talk about it*|END:IF|*.',
                'placeholder' => array(
                    'HAPPINESS' => 55
                ),
                'expected' => 'Our happiness is ok.'
            ),
            array(
                'message' => 'This is *|IF:EQUAL = equal|*equal*|END:IF|*',
                'placeholder' => array(
                    'EQUAL' => 'equal'
                ),
                'expected' => 'This is equal'
            ),
            array(
                'message' => 'This is *|IFNOT:EQUAL = equal|*not equal*|END:IF|*',
                'placeholder' => array(
                    'EQUAL' => 'not so equal'
                ),
                'expected' => 'This is not equal'
            ),
            array(
                'message' => 'This is *|IF:EQUAL != equal|*not equal*|END:IF|*',
                'placeholder' => array(
                    'EQUAL' => 'not so equal'
                ),
                'expected' => 'This is not equal'
            ),
            array(
                'message' => 'This is *|IF:NUMBER > 0|*greater than 0*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 7
                ),
                'expected' => 'This is greater than 0'
            ),
            array(
                'message' => 'This is *|IF:NUMBER < 10|*lesser than 10*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 7
                ),
                'expected' => 'This is lesser than 10'
            ),
            array(
                'message' => 'This is *|IF:NUMBER >= 0|*greater than or equal 0*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 0
                ),
                'expected' => 'This is greater than or equal 0'
            ),
            array(
                'message' => 'This is *|IF:NUMBER >= 0|*greater than or equal 0*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 3
                ),
                'expected' => 'This is greater than or equal 0'
            ),
            array(
                'message' => 'This is *|IF:NUMBER <= 10|*lesser than or equal 10*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 7
                ),
                'expected' => 'This is lesser than or equal 10'
            ),
            array(
                'message' => 'This is *|IF:NUMBER <= 10|*lesser than or equal 10*|END:IF|*',
                'placeholder' => array(
                    'NUMBER' => 10
                ),
                'expected' => 'This is lesser than or equal 10'
            ),
            array(
                'message' => '*|IF:COOL|*' . PHP_EOL .
                                 'You are cool*|IF:BEAUTIFUL|* and beautiful*|END:IF|*.' . PHP_EOL .
                             '*|END:IF|*',
                'placeholder' => array(
                    'COOL' => true,
                    'BEAUTIFUL' => true
                ),
                'expected' => 'You are cool and beautiful.' . PHP_EOL
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
    public function testConditionalMergeTags($message, array $placeholder, $expected)
    {
        $this->assertEquals($expected, (string) new ChimpDrill($message, $placeholder));
    }
}