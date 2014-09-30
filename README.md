# ChimpDrill - Merge Tag Parser

[![Build Status](https://travis-ci.org/FlorianKoerner/ChimpDrill.svg)](https://travis-ci.org/FlorianKoerner/ChimpDrill)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/FlorianKoerner/ChimpDrill/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/FlorianKoerner/ChimpDrill/?branch=master)
[![Coverage Status](https://coveralls.io/repos/FlorianKoerner/ChimpDrill/badge.png)](https://coveralls.io/r/FlorianKoerner/ChimpDrill)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a7a165ab-fa72-4a98-834c-1ed8896901a8/mini.png)](https://insight.sensiolabs.com/projects/a7a165ab-fa72-4a98-834c-1ed8896901a8)

Parser for the merge tags syntax of [Mailchimp] and [Mandrill]. Supports
[placeholder], [filters] and [conditions].

[Mailchimp]: http://mailchimp.com
[Mandrill]: http://mandrill.com
[placeholder]: http://help.mandrill.com/entries/21678522-How-do-I-use-merge-tags-to-add-dynamic-content-
[filters]: http://kb.mailchimp.com/merge-tags/all-the-merge-tags-cheatsheet#Content-encoding-merge-tags
[conditions]: http://kb.mailchimp.com/merge-tags/how-conditional-or-smart-merge-tags-work


## Installation

Download ChimpDrill by using [composer](https://getcomposer.org):

``` bash
php composer.phar require florian-koerner/chimpdrill:dev-master
```

Or add the code below to your `composer.json`:

``` json
{
    "require": {
        "florian-koerner/chimpdrill": "dev-master"
    }
}
```


## Usage

``` php
$chimpdrill = new \FlorianKoerner\ChimpDrill\ChimpDrill($message, $placeholder);

var_dump((string) $chimpdrill);
// or
var_dump($chimpdrill->getParsed());
```


## Example

**Message:**
``` html
<h1>Hi *|NAME|*</h1>

<p>
    *|IF:CUSTOMER|*
        We want your money!
    *|ELSE:|*
        We wish you all the best.
    *|END:IF|*
</p>

<p>
    *|IF:INVOICE_COUNT == 0|*
        All invoices payed. You are the best!
    *|ELSEIF:INVOICE_COUNT <= 5|*
        Oops... You have open invoices.
    *|ELSE:|*
        Are you kidding?
    *|END:IF|*
</p>

<ul>
    <li>The current year is: *|DATE:Y|*</li>
    <li>Current weather: *|UPPER:WEATHER|*</li>
    <li>Best movie ever: *|TITLE:MOVIE|*</li>
</ul>
```

**Parameters:**
``` php
array(
    'NAME' => 'John Doe',
    'CUSTOMER' => true,
    'INVOICE_COUNT' => 18,
    'WEATHER' => 'rainy',
    'MOVIE' => 'The last song'
)
```

**Result:**
``` html
<h1>Hi John Doe</h1>

<p>
    We want your money!
</p>

<p>
    Are you kidding?
</p>

<ul>
    <li>The current year is: 2014</li>
    <li>Current weather: RAINY</li>
    <li>Best movie ever: The Last Song</li>
</ul>
```


## I Love Open Source

[![I Love Open Source](http://www.iloveopensource.io/images/logo-lightbg.png)](http://www.iloveopensource.io/users/FlorianKoerner)
