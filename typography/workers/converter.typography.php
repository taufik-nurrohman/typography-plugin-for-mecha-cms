<?php namespace Converter;

// Author: Taufik Nurrohman <https://github.com/tovic>

/**
 * ====================================================================
 *  TYPOGRAPHY CONVERTER
 * ====================================================================
 *
 * -- CODE: -----------------------------------------------------------
 *
 *    $parser = new Converter\Typography();
 *
 *    echo $parser->run('He said, "Yes, you\'re gross!"');
 *
 * --------------------------------------------------------------------
 *
 */

class Typography {

    // single quote [o, c]
    public $q = array('&#8216;', '&#8217;');

    // double quote [o, c]
    public $Q = array('&#8220;', '&#8221;');

    // dash [n, m]
    public $d = array('&#8211;', '&#8212;');

    // ellipsis [h]
    public $e = array('&#8230;');

    // white-space(s) ...
    public $s = array('&#32;', '&#160;');

    // Skip parsing process if we are in these HTML tag(s)
    public $ignore = 'code|kbd|math|pre|script|style';

    // Run converter ...
    public function run($text) {
        if( ! trim($text)) return $text;
        $_ = '#(<\/?[-:\w]+(?:>|\s[^<>]*?>))#';
        $parts = preg_split($_, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $text = "";
        $x = 0;
        foreach($parts as $v) {
            if( ! trim($v)) {
                $text .= $v;
                continue;
            }
            if($v[0] === '<' && substr($v, -1) === '>') {
                if(preg_match('#^<(\/)?(?:' . $this->ignore . ')(?:>|\s[^<>]*?>)$#', $v, $m)) {
                    $x = isset($m[1]) && $m[1] === '/' ? 0 : 1;
                }
                $text .= $v; // this is a HTML tag ...
            } else {
                $text .= ! $x ? $this->replace($v) : $v; // process or skip ...
            }
        }
        return $text;
    }

    // Replace character(s) ...
    protected function replace($text) {
        // Convert some HTML entiti(y|es) back
        // to their plain text version ...
        $text = str_replace(
            array('&#34;', '&#39;', '&quot;', '&apos;'),
            array('"', "'", '"', "'"),
        $text);
        $text = $this->escape($text);
        $text = $this->nbsp($text);
        $text = $this->dot($text);
        $text = $this->dash($text);
        $text = $this->quote($text);
        return $text;
    }

    // From `'foo'` to `‘foo’` and `"bar"` to `“bar”`
    protected function quote($text) {
        $text = preg_replace(
            array(
                // single quote
                '#(?<=^|\W)\'(?!$)#',
                '#(?<!^)\'(?=\W|$)#',
                // double quote
                '#(?<=^|\W)"(?!$)#',
                '#(?<!^)"(?=\W|$)#'
            ),
            array(
                // single quote
                $this->q[0],
                $this->q[1],
                // double quote
                $this->Q[0],
                $this->Q[1]
            ),
        $text);
        // Fix for `foo”‘` and `foo’“`
        $text = str_replace(
            array(
                $this->Q[1] . $this->q[0],
                $this->q[1] . $this->Q[0]
            ),
            array(
                $this->Q[1] . $this->q[1],
                $this->q[1] . $this->Q[1]
            ),
        $text);
        // The rest of the quote(s) should be a closing quote(s)
        return str_replace(array("'", '"'), array($this->q[1], $this->Q[1]), $text);
    }

    // From `--` to `–` and `---` to `—`
    protected function dash($text) {
        return str_replace(
            array(
                '---',
                '--'
            ),
            array(
                $this->d[1],
                $this->d[0]
            ),
        $text);
    }

    // From `...` to `…`
    protected function dot($text) {
        return str_replace(
            array(
                '...',
                '. . .'
            ),
            $this->e[0],
        $text);
    }

    // ...
    protected function nbsp($text) {
        return preg_replace('#(?<=\S)\s+(?=[:;?!])#', $this->s[1], $text);
    }

    // Ignore parser with `\"foo\"`
    protected function escape($text) {
        return str_replace(
            array(
                '\\\\',
                '\"',
                "\'",
                '\.',
                '\-',
                '\`'
            ),
            array(
                '&#92;',
                '&#34;',
                '&#39;',
                '&#46;',
                '&#45;',
                '&#96;'
            ),
        $text);
    }

    // Reverse conversion, e.g. from `“foo”` to `"foo"`
    public function destroy($text) {
        return str_replace(
            array(
                $this->d[0],
                $this->d[1],
                $this->q[0],
                $this->q[1],
                $this->Q[0],
                $this->Q[1],
                $this->e[0],
                $this->s[0],
                $this->s[1]
            ),
            array(
                '--',
                '---',
                "'",
                "'",
                '"',
                '"',
                '...',
                ' ',
                ' '
            ),
        $text);
    }

}