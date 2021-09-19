<?php

/**
 * This class is used to Parse the markdown to HTML.
 * 
 * @author Davnish Singh <davnishsingh46@gmail.com>
 * @version 1.0.0
 */
class HTMLMarkdown
{
    /**
     * Private variables
     */
    private $Text;
    private $key;
    private $value = [];

    /**
     * Protected variables
     */
    protected $RegexUnformattedText = '/[\^:£$%&*()}{@#~><>,|=_+¬-]/';
    protected $RegexForURL = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
    protected $RegexForList = '/[^a-zA-Z]+/';
    protected $RegexForImages = '/@/';
    protected $RegexForBoldText = '/_/';
    protected $RegexForBlockQuote = '/>/';

    /**
     * Constructor
     * This method will accept the text.
     *
     * @param string $Text
     */
    public function __construct($Text) {
        $this->Text = $Text;
    }

    /**
     * This method is used to parse the markdown text to html.
     *
     * @return string
     */
    public function Text()
    {
        // parse the header
        $this->ParseHeader($this->Text);

        // parse the unformatted text
        $this->ParseUnformattedText($this->Text);

        // parse the links
        $this->ParseLink($this->Text);

        // parse the bold text
        $this->ParseBoldText($this->Text);

        // parse images
        $this->ParseImage($this->Text);

        // parse lists
        $this->ParseList($this->Text);

        // parse block quotes
        $this->ParseBlockQuote($this->Text);

        $Strings = array();

        // loop through the multidimentional array
        foreach ($this->value as $key => $row){
            foreach ($row as $key => $value) {
                $Strings[$key] = $value;
            }
        }

        // sort the array by keys
        ksort($Strings);

        // implode the array into string by new line
        $Text = implode("\n", $Strings);

        return $Text;

    }
    
    /**
     * This method is used to check if the string has header or not.
     * 
     * @param string $Text
     * @return bool
     */
    public function IsHeader($Text)
    {
        $IsHeader = false;
        $array = str_split($Text);

        foreach ($array as $key => $value) {
            // check if a string has #
            if ($array[0] === '#') {
                // check if a string has space in it
                if ($value == ' ') {
                    // if there is no space before # then return false
                    if ($array[$key-1] != '#') {
                        $IsHeader = false;
                        break;
                    } elseif ($array[$key+1] == '#') {
                        $IsHeader = true;
                        break;
                    } else {
                        // if there is some value after a space then return true
                        if (!empty($array[$key+1])) {
                            $IsHeader = true;
                            break;
                        }
                    }
                }
            }
        }

        return $IsHeader;
    }

    /**
     * This method is used to parse the header.
     * 
     * @example # Heading 1 => <h1>Heading 1</h1>
     *          ## Heading 2 => <h2>Heading 2</h2>
     * 
     * @param string $Text
     * @return string
     */
    public function ParseHeader($Text) 
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);

        $Headers = [];
        foreach ($ArrayOfStrings as $key => $String) {

            // check if it strings are a header
            $IsHeader = $this->IsHeader($String) == true;

            if ($IsHeader) {

                // explode the words in a string by array to get the count of '#'
                $words = explode(" ", $String);

                // count the headers
                $HeaderCount = strlen($words[0]);

                // if the header count is more than 6, then return the string without markdown
                if ($HeaderCount > 6) {
                    $Headers[$this->key+1] = $String;
                }

                // sanitize the string and remove the '#' from the strings
                $SanitizedHeaders = substr($String, strpos($String, "#") + $HeaderCount);

                $this->key = $key;

                // only create a markdown if the header count is less than or equal to 6
                if ($HeaderCount <= 6) {
                    $Headers[$this->key] = html_entity_decode("<h$HeaderCount>" . trim($SanitizedHeaders) . "</h$HeaderCount>");
                }
            } else {
                $Headers[$this->key+1] = $String;
            }
        }

        // parse the headers
        $this->value[] = $Headers;

        return $this->value;
    }

    /**
     * This method is used to check the unformatted text and format it.
     * 
     * @example Unformatted text
     * will be converted to <p>Unformatted text</p>
     *
     * @param string $Text
     * @return string
     */
    public function ParseUnformattedText($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);
        $UnformattedTexts = [];
        foreach ($ArrayOfStrings as $key => $String) {
            $this->key = $key;
            if (!preg_match($this->RegexUnformattedText, $String)) {
                $UnformattedTexts[$this->key] = "<p>$String</p>";
            }
        }

        $this->value[] = $UnformattedTexts;

        return $this->value;
    }

    /**
     * This method is used to check if a string has a link or not.
     *
     * @param string $Text
     * @return bool
     */
    public function IsLink($Text)
    {
        if (strpos($Text, 'http') !== false || strpos($Text, 'www.') !== false) {
            return true;
        }

        return false;
    }

    /**
     * This method is used to parse the link or the link in the text.
     * 
     * @example [Link text](https://www.example.com) will be converted
     * to <a href="https://www.example.com">Link text</a>
     *
     * @param string $Text
     * @return string
     */
    public function ParseLink($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);

        $Strings = [];
        foreach ($ArrayOfStrings as $key => $String) {
            $this->key = $key;
            $HasLink = $this->IsLink($String);

            if ($HasLink) {
                
                // get the string between '[]'
                preg_match('#\[(.*?)\]#', $String, $match);

                // sanitize the string by removing the string inside the '[]'
                $SanitizedString = preg_replace('/[\[{].*?[\]}]/', '', $String);
                $SanitizedString = str_replace('(', '', $SanitizedString);
                $SanitizedString = str_replace(')', '', $SanitizedString);

                $Strings[$this->key] = preg_replace($this->RegexForURL, '<a href="$0">' . $match[1] .'</a>', $SanitizedString);
            }
        }

        $this->value[] = $Strings;

        return $this->value;
    }

    /**
     * This method is used to parse the lists in the string.
     * 
     * @example :Ordered List => <ol><li>Ordered List</li></ol>
     *          -Unordered List => <ul><li>Unordered List</li></ul>
     *
     * @param string $Text
     * @return void
     */
    public function ParseList($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);

        $Strings = [];
        foreach ($ArrayOfStrings as $key => $String) {
            $this->key = $key;
            preg_match($this->RegexForList, $String, $matches);

            // if a string has ':', then it is an ordered list
            if (isset($matches[0]) && trim($matches[0]) == ':') {
                $SubString = trim(substr($String, 1));
                $Strings[$this->key] = "<ol><li>$SubString</li></ol>";
            }

            // if a string has '-', then it is an unordered list
            if (isset($matches[0]) && trim($matches[0]) == '-') {
                $SubString = trim(substr($String, 1));
                $Strings[$this->key] = "<ul><li>$SubString</li></ul>";
            }
        }

        $this->value[] = $Strings;

        return $this->value;
    }

    /**
     * This method is used to check if a string has
     * an image in it.
     *
     * @param string $Text
     * @return bool
     */
    public function IsImage($Text)
    {
        if (preg_match($this->RegexForImages, $Text)) {
            return true;
        }

        return false;
    }

    /**
     * This method is used to parse an image in a string.
     * 
     * @example @image.jpg => <img src="image.jpg">
     *
     * @param string $Text
     * @return string
     */
    public function ParseImage($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);
        $ParsedImages = [];
        foreach ($ArrayOfStrings as $key => $String) {
            $this->key = $key;
            $IsImage = $this->IsImage($String);
            if ($IsImage) {
                $Image = str_replace('@', '', $String);
                $ParsedImages[$this->key] = "<img src=". $Image .">";
            }
        }

        if (empty($ParsedImages)) {
            return '';
        }

        $this->value[] = $ParsedImages;

        return $this->value;
    }

    /**
     * This method is used to check the bold text.
     * 
     * @example _Hello_ => <b>Hello</b>
     *
     * @param string $Text
     * @return string
     */
    public function IsBoldText($Text)
    {
        $IsBold = false;
        if (preg_match($this->RegexForBoldText, $Text)) {
            $IsBold = true;
        }

        return $IsBold;
    }

    /**
     * This method is used to parse the bold text.
     * 
     * @example __Hello__ => <b>Hello</b>
     *
     * @param string $Text
     * @return string
     */
    public function ParseBoldText($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);

        $BoldTexts = [];
        foreach ($ArrayOfStrings as $key => $String) {

            $this->key = $key;
            $IsBold = $this->IsBoldText($String);
            if ($IsBold) {

                // check if the word with _$i_ appear in a same line or on a different line
                preg_match('#\_(.*?)\_#', $String, $match);
                if (!empty($match)) {
                    $BoldTexts[$this->key] = preg_replace('/'. $match[0] .'/', '<b>' . $match[1] .'</b>', $String);   
                } else {
                    $SanitizedString = trim(str_replace('_', ' ', $String));
                    $BoldTexts[$this->key] = "<b>$SanitizedString</b>";
                }
            }
        }

        $this->value[] = $BoldTexts;

        return $this->value;
    }

    /**
     * This method is used to check the block quote
     * in the string.
     *
     * @param string $Text
     * @return bool
     */
    public function CheckBlockQuote($Text)
    {
        $IsBlockQuote = false;
        if (preg_match($this->RegexForBlockQuote, $Text)) {
            $IsBlockQuote = true;
        }

        return $IsBlockQuote;
    }

    /**
     * This method is used to parse the blockquote in a string.
     * 
     * @example > Quote => <blockquote><p>Quote</p></blockquote>
     *
     * @param string $Text
     * @return string
     */
    public function ParseBlockQuote($Text)
    {
        $ArrayOfStrings = explode(PHP_EOL, $Text);

        $BlockQuoteTexts = [];
        foreach ($ArrayOfStrings as $key => $String) {
            $this->key = $key;
            $IsBlockQuote = $this->CheckBlockQuote($String);
            if ($IsBlockQuote) {
                $SanitizedString = trim(str_replace('>', ' ', $String));
                $BlockQuoteTexts[$this->key] = "<blockquote><p>$SanitizedString</p></blockquote>";
            }
        }

        $this->value[] = $BlockQuoteTexts;

        return $this->value;
    }
}
