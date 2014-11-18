<?php

namespace FlorianKoerner\ChimpDrill;

/**
 * ChimpDrill a simple mailchimp / mandrill merge tags parser
 */
class ChimpDrill
{
    /**
     * @var array callback => syntax pattern
     */
    protected $pattern = array(
        'placeholder' => '/\*\|([A-Za-z0-9_]+)\|\*/',
        'if'          => '/\*\|(IF|IFNOT|ELSEIF):([A-Za-z0-9_]+)(?:[\s]*(=|!=|&gt;=|&lt;=|&gt;|&lt;)[\s]*(.+?))?\|\*/',
        'else'        => '/\*\|ELSE:\|\*/',
        'endif'       => '/\*\|END:IF\|\*/',
        'filter'      => '/\*\|(HTML|TITLE|LOWER|UPPER):([A-Za-z0-9_]+)\|\*/',
        'date'        => '/\*\|DATE:(.+?)\|\*/'
    );

    /**
     * @var string parsed message
     */
    protected $parsed = null;

    /**
     * @var string message
     */
    protected $message;

    /**
     * @var array placeholder
     */
    protected $placeholder;

    /**
     * @param string $message     Message to parse
     * @param array  $placeholder Placeholder
     */
    public function __construct($message, array $placeholder)
    {
        $this->message = $message;
        $this->placeholder = $placeholder;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getParsed();
    }

    /**
     * Returns parsed message.
     * 
     * @return string
     */
    public function getParsed()
    {
        $this->parseMessage();

        return $this->parsed;
    }

    /**
     * Parse the message (If this haven't be done yet).
     * 
     * @return ChimpDrill
     */
    protected function parseMessage()
    {
        if ($this->parsed === null) {
            // Escape message
            $message = $this->escapeValue($this->message);

            // Replace Syntax with PHP
            foreach ($this->pattern as $type => $pattern) {
                $method = 'parse' . ucfirst($type);
                $message = preg_replace_callback($pattern, array($this, $method), $message);
            }

            // Write file
            $file = tempnam('/tmp', 'chimpdrill-');

            file_put_contents($file, '<?php ob_start(); ?>' . $message . '<?php return ob_get_clean(); ?>');

            $this->parsed = $this->unescapeValue(include_once($file));

            unlink($file);
        }

        return $this;
    }

    /**
     * Searches for a placeholder and returns the found or default value.
     * 
     * @param string $name
     * @param mixed  $default
     * 
     * @return mixed
     */
    protected function getPlaceholder($name, $default = null)
    {
        return isset($this->placeholder[$name]) ? $this->placeholder[$name] : $default;
    }

    /**
     * @param mixed $value
     * 
     * @return mixed
     */
    protected function exportValue($value)
    {
        return var_export($value, true);
    }

    /**
     * Escape an string.
     * 
     * @param string $value
     * 
     * @return string
     */
    protected function escapeValue($value)
    {
        return htmlspecialchars($value, null, 'UTF-8');
    }

    /**
     * Rolls back escaping.
     * 
     * @param string $value
     * 
     * @return string
     */
    protected function unescapeValue($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * Compares two values with an operator.
     * 
     * @param mixed  $val1
     * @param string $operator
     * @param mixed  $val2
     * 
     * @return boolean
     */
    protected function compare($val1, $operator, $val2)
    {
        switch ($operator) {
            case '=':
                return ($val1 == $val2);

            case '!=':
                return ($val1 != $val2);

            case '>=':
                return ($val1 >= $val2);

            case '<=':
                return ($val1 <= $val2);

            case '>':
                return ($val1 > $val2);

            case '<':
                return ($val1 < $val2);
        }
    }

    /**
     * Parses placeholder merge tags.
     * 
     * @param array $match
     * 
     * @return string
     */
    protected function parsePlaceholder(array $match)
    {
        // Yes, double escaping is correct here
        return $this->escapeValue(
            $this->escapeValue(
                $this->getPlaceholder($match[1], '*|' . $match[1] . '|*')
            )
        );
    }

    /**
     * Parses `IF|ELSEIF|IFNOT` conditional merge tags.
     *
     * @param array $match
     * 
     * @return string
     */
    protected function parseIf(array $match)
    {
        $condition = $this->getPlaceholder($match[2]);

        if (count($match) == 5) {
            $condition = $this->compare($condition, $this->unescapeValue($match[3]), $this->getPlaceholder($match[4], $match[4]));
        } else {
            $condition = (bool) $condition;
        }

        switch ($match[1]) {
            case 'IF':
                return '<?php if (' . $this->exportValue($condition) . '): ?>';

            case 'ELSEIF':
                return '<?php elseif (' . $this->exportValue($condition) . '): ?>';

            case 'IFNOT':
                return '<?php if (!' . $this->exportValue($condition) . '): ?>';
        }
    }

    /**
     * Parses `ELSE` conditional merge tags.
     * 
     * @return string
     */
    protected function parseElse()
    {
        return '<?php else: ?>';
    }

    /**
     * Parses `ENDIF` conditional merge tags.
     * 
     * @return string
     */
    protected function parseEndif()
    {
        return '<?php endif; ?>';
    }

    /**
     * Parses `HTML|TITLE|LOWER|UPPER` filter merge tags.
     *
     * @param array $match
     *
     * @return string
     */
    protected function parseFilter(array $match)
    {
        $value = $this->getPlaceholder($match[2], '*|' . $match[2] . '|*');

        switch ($match[1]) {
            case 'HTML':
                return $this->escapeValue($value);

            case 'TITLE':
                return ucwords(strtolower($value));

            case 'LOWER':
                return strtolower($value);

            case 'UPPER':
                return strtoupper($value);
        }
    }

    /**
     * Parses date merge tags.
     *
     * @param array $match
     *
     * @return bool|string
     */
    protected function parseDate(array $match)
    {
        return date($match[1] ?: 'Y-m-d');
    }
}
