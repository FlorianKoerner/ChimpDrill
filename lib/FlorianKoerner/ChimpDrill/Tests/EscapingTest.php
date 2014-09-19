<?php

namespace FlorianKoerner\ChimpDrill\Tests;

use FlorianKoerner\ChimpDrill\ChimpDrill;

class EscapingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                'message' => 'Damn! Very cool PHP-Code: <?php echo "evil"; ?>',
                'placeholder' => array(),
                'expected' => 'Damn! Very cool PHP-Code: <?php echo "evil"; ?>'
            ),
            array(
                'message' => 'And take a look at these HTML-Tags: <strong>Unbelievable!</strong>',
                'placeholder' => array(),
                'expected' => 'And take a look at these HTML-Tags: <strong>Unbelievable!</strong>'
            ),
            array(
                'message' => 'Your input was *|INPUT|*. Thank you, *|NAME|*.',
                'placeholder' => array(
                    'NAME' => 'John <hacks>',
                    'INPUT' => '<?php var_dump($_SERVER); ?> <strong>I\'m a very bad boy</strong>'
                ),
                'expected' => 'Your input was &lt;?php var_dump($_SERVER); ?&gt; &lt;strong&gt;I\'m a very bad boy&lt;/strong&gt;. Thank you, John &lt;hacks&gt;.'
            ),
            array(
                'message' => 'Your input was *|HTML:INPUT|*. Thank you, *|HTML:NAME|*.',
                'placeholder' => array(
                    'NAME' => 'John <hacks>',
                    'INPUT' => '<?php var_dump($_SERVER); ?> <strong>I\'m a very bad boy</strong>'
                ),
                'expected' => 'Your input was <?php var_dump($_SERVER); ?> <strong>I\'m a very bad boy</strong>. Thank you, John <hacks>.'
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
    public function testEscaping($message, array $placeholder, $expected)
    {
        $this->assertEquals($expected, (string) new ChimpDrill($message, $placeholder));
    }
}