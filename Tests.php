<?php 

include_once 'Parser/Parser.php';

/**
 * This file includes all the tests.
 * 
 * Following are the symbols:
 * 
 * # => Header
 * '' => Unformatted text
 * [with an inline link](http://google.com) => Link
 * : => Ordered List
 * - => Unordered List
 * @ => image
 * > => Blockquote
 * _Text_ => Parse bold text
 */

// Test 1
$Test1 = "# Sample Document
Hello
This is sample markdown for the [Mailchimp](https://www.mailchimp.com) homework assignment";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test1);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 2
$Test2 = "# Header one

Hello there

How are you?
What's going on?

## Another Header

This is a paragraph [with an inline link](http://google.com). Neat, eh?

## This is a header [with a link](http://yahoo.com)";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test2);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 3
$Test3 = "# Header one

## Header two

What's up?";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test3);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 4
$Test4 = "@image.jpg

> This is blockquote

: Ordered List

- Unordered List";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test4);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 5
$Test5 = "Below is a bold _text_
## and this is a header";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test5);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 6
$Test6 = "Here
#### and this is a header
####### Not a header because it has 7 hashes
#### ####";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test6);

echo $ParsedText;

/////////////////////////////////////////////////////////////////

// Test 7
$Test7 = "Hello!
## Header ##
#####";

$Parser = new Parser();
$ParsedText = $Parser->ParseText($Test7);

echo $ParsedText;
