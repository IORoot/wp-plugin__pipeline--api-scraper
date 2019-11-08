<?php


trait textreplacement {

    /**
     * instagram_newline_to_br
     * 
     *  Note - the 'space' is NOT a space. Contains unicode U+2800 (Instagram newline)
     *  See http://unicode.scarfboy.com/?s=U%2B2800
     *
     * @param mixed $text
     * @return void
     */
    public function filter_instagram_newline_to_br($text){

        $output = str_replace('⠀', '<br/>', $text);

        return $output;
    }


    /**
     * first_sentence_in_paragraph
     * 
     * First sentence (before fullstop).
     *
     * @param mixed $text
     * @return void
     */
    public function filter_first_sentence_in_paragraph($text){

        $arr = preg_split( "/(\.|!)/", $text );

        return $arr[0] . '.';

    }


    /**
     * filter_newlines_to_br
     *
     * @param mixed $text
     * @return void
     */
    public function filter_newlines_to_br($text){

        $output = trim(preg_replace('/\r\n|\r|\n/', '<br/>', $text));

        return $output;
    }



    /**
     * filter_pseudo_markdown
     *
     * @param mixed $text
     * @return void
     */
    public function filter_pseudo_markdown($text){

        // Bold (double ** bold this **)
        $text = $this->markdown_bold_to_tags($text);

        // Underline
        $text = $this->markdown_underline_to_tags($text);

        // H2      
        $text = $this->markdown_h2_to_tags($text);

        // h1
        $text = $this->markdown_h1_to_tags($text);

        // UL / LI?
        $text = $this->markdown_dash_to_li($text);
        
        // Image
        $text = $this->markdown_image_to_img($text);    

        // HR
        $text = $this->markdown_underscores_to_hr($text);


        return $text;
    }

    /**
     * markdown_bold_to_tags
     * 
     * Double stars around things.
     *
     * @param mixed $text
     * @return void
     */
    public function markdown_bold_to_tags($text){

        $output = preg_replace('/\*\*([\w|\s|.|,|:|\']*)\*\*/', '<b>$1</b>', $text);

        return $output;
    }

    /**
     * markdown_underline_to_tags
     *
     * @param mixed $text
     * @return void
     */
    public function markdown_underline_to_tags($text){

        $output = preg_replace('/__([\w|\s|.|,|:|\']*)__/', '<u>$1</u>', $text);

        return $output;
    }

    /**
     * markdown_h2_to_tags
     *
     * Convert a ### to an H2 tag.
     * 
     * @param mixed $text
     * @return void
     */
    public function markdown_h2_to_tags($text){

        $output = preg_replace('/###([\w|\s]*)/', '<h3>$1</h3>', $text);

        return $output;
    }

    /**
     * markdown_h1_to_tags
     *
     * Convert a ## to an H1 tag.
     * 
     * @param mixed $text
     * @return void
     */
    public function markdown_h1_to_tags($text){

        $output = preg_replace('/##([\w|\s]*)/', '<h2>$1</h2>', $text);

        return $output;
    }


    /**
     * markdown_underscores_to_hr
     *
     * @param mixed $text
     * @return void
     */
    public function markdown_underscores_to_hr($text){

        $output = preg_replace('/\_\_\_/', '<hr style="height: 1px;"/>', $text);

        return $output;
    }   

    /**
     * markdown_image_to_img
     * 
     * Convert the [title](url) into an image.
     *
     * @param mixed $text
     * @return void
     */
    public function markdown_image_to_img($text){

        $output = preg_replace('/\[(.*)\]\((.*)\)/', '<img title="$1" src="$2" >', $text);

        return $output;
    }


    /**
     * markdown_dash_to_li
     *
     * @param mixed $text
     * @return void
     */
    public function markdown_dash_to_li($text){

        // Replace all -- for <li>
        $stepB = preg_replace('/-- ([\w|\s|.|,|:|\']*)<br\/>/', '<li>$1</li>', $text);

        // replace leading <br> to <ul>
        $stepC = preg_replace('/<br\/><li>/', '<ul><li>', $stepB);

        // Replace last <li><br> to <ul>
        $output = preg_replace('/<\/li><br\/>/', '</li></ul>', $stepC);

        return $output;
    }


}