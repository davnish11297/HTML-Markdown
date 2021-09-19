# HTML-Markdown

## Installation

Install any PHP development environment such as [XAMPP] https://www.apachefriends.org/index.html
and run the Tests.php file using it. All the tests will be run. You can also add/edit the tests in
this file.

There are three files included in this:
1. html_markdown.php
2. Parser.php
3. Tests.php

## Features

1. `#` => Parse Header
2. `''` => Parse Unformatted text
3. `[with an inline link](http://google.com)` => Parse Link
4. `:` => Parse Ordered List
5. `-` => Parse Unordered List
6. `@` => Parse image
7. `>` => Parse Blockquote
8. `_Text_` => Parse bold text

## Example

```php
$Parser = new Parser();

echo $Parser->ParseText('Hello _Parser_'); # prints: <p>Hello <b>Parser</b></p>
```

```php
$Parser = new Parser();

echo $Parser->ParseText('# Hello!'); # prints: <h1>Hello!</h1>
```

You can also use inline and multiline texts to parse as well.

## Questions

How does HTML Markdown work?

It tries to read Markdown like a human. First, it looks at the lines. It’s interested in how the lines start. This helps it recognise blocks. It knows, for example, that if a line starts with a # then perhaps it belongs to a header.
