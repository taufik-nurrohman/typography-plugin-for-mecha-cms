<?php

require __DIR__ . DS . 'workers' . DS . 'typography.php';

$parser = new Converter\Typography();

function do_typography($content) {
    if( ! is_string($content)) {
        return $content;
    }
    global $parser;
    return $parser->run($content);
}

Filter::add(array(
    'author',
    'content',
    'description',
    'excerpt',
    'message',
    'menu:output',
    'name',
    'title'
// `do_parse_markdown` filter is on priority `1`
), 'do_typography', 1.1);