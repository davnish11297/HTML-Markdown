<?php

include_once 'Parser/html_markdown.php';

/**
 * This class parse the markdown text to HTML.
 */
class Parser extends HTMLMarkdown
{

    public function __construct() {
    }

    /**
     * This method is used to parse the markdown text to HTML.
     *
     * @param string $Text
     * @return string
     */
    public function ParseText($Text)
    {
        $HTMLMarkdown = new HTMLMarkdown($Text);
        $Parsedtext = $HTMLMarkdown->Text();
        
        return $Parsedtext;
    }
}
