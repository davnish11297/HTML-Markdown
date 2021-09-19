<?php

include_once 'Parser/html_markdown.php';

/**
 * This class parse the markdown text to HTML.
 * 
 * @author Davnish Singh <davnishsingh46@gmail.com>
 * @version 1.0.0
 */
class Parser extends HTMLMarkdown
{
    /**
     * Constructor
     */
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
