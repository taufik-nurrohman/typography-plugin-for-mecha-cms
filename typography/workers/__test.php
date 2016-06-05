<!DOCTYPE html>
<html dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Test</title>
    <style>
* {
  margin:0;
  padding:0;
}
body {
  background-color:black;
  border:4px solid;
  border-bottom-width:0;
  font:normal normal 18px/1.4 Consolas,"Courier New",Courier,"Nimbus Mono L",Monospace;
  color:white;
}
div,
pre {
  font:inherit;
  word-wrap:break-word;
  width:48%;
  padding:1%;
  float:left;
}
code {font:inherit}
pre {
  background-color:#3F3F3F;
  white-space:pre-wrap;
}
div div,
div pre {
  float:none;
  width:auto;
}
div pre,
div code {
  font-weight:normal;
  background-color:white;
  color:black;
}
hr {
  color:inherit;
  border:0;
  border-top:4px solid;
  height:0;
  clear:both;
}
div hr {
  border-top:1px dashed;
  margin:1.4em 0;
}
    </style>
  </head>
  <body>
<?php

include __DIR__ . '/converter.typography.php';

$str = array();
$str[] = <<<EOL
<p>'lorem ipsum dolor sit am\'et</p>
<p>"lorem ipsum dolor sit am\"et</p>

<p>lorem ipsum 'dolor' sit am\'et</p>
<p>lorem ipsum "dolor" sit am\"et</p>

<p>lorem ipsum 'dolo"r' sit am\'et</p>
<p>lorem ipsum "dolo'r" sit am\"et</p>

<p>'lorem ipsum dolor sit am\'et'</p>
<p>"lorem ipsum dolor sit am\"et"</p>

<p>'lorem ipsum 'dolor' sit am\'et'</p>
<p>"lorem ipsum "dolor" sit am\"et"</p>

<p>'lorem ipsum "dolor" sit am\'et'</p>
<p>"lorem ipsum 'dolor' sit am\"et"</p>

<p>'lorem ipsum' dolor 'sit am\'et'</p>
<p>"lorem ipsum" dolor "sit am\"et"</p>

<p>'lorem ipsum" dolor "sit am\'et'</p>
<p>"lorem ipsum' dolor 'sit am\"et"</p>

<p>lorem ipsum dolor sit am\'et'</p>
<p>lorem ipsum dolor sit am\"et"</p>

<p>'"lorem ipsum dolor sit am\'et"'</p>
<p>"'lorem ipsum dolor sit am\"et'"</p>

<p>lorem ipsum '"dolor"' sit am\'et</p>
<p>lorem ipsum "'dolor'" sit am\"et</p>

<p>lorem ipsum'" dolor "'sit am\'et</p>
<p>lorem ipsum"' dolor '"sit am\"et</p>

<p>'"lorem" ipsum dolor sit am\'et'</p>
<p>"'lorem' ipsum dolor sit am\"et"</p>

<p>lorem --ipsum dolor-- sit am\--et</p>
<p>lorem ---ipsum dolor--- sit am\--et</p>
<p>lorem ----ipsum dolor---- sit am\--et</p>
<p>lorem -----ipsum dolor----- sit am\--et</p>

<p>lorem ...ipsum dolor... sit am\...et</p>
<p>lorem ....ipsum dolor.... sit am\...et</p>
<p>lorem .....ipsum dolor..... sit am\...et</p>
<p>lorem ......ipsum dolor...... sit am\...et</p>

<hr>

<div style="white-space:pre-wrap;">
'lorem ipsum dolor sit am\'et
"lorem ipsum dolor sit am\"et

lorem ipsum 'dolor' sit am\'et
lorem ipsum "dolor" sit am\"et

lorem ipsum 'dolo"r' sit am\'et
lorem ipsum "dolo'r" sit am\"et

'lorem ipsum dolor sit am\'et'
"lorem ipsum dolor sit am\"et"

'lorem ipsum 'dolor' sit am\'et'
"lorem ipsum "dolor" sit am\"et"

'lorem ipsum "dolor" sit am\'et'
"lorem ipsum 'dolor' sit am\"et"

'lorem ipsum' dolor 'sit am\'et'
"lorem ipsum" dolor "sit am\"et"

'lorem ipsum" dolor "sit am\'et'
"lorem ipsum' dolor 'sit am\"et"

lorem ipsum dolor sit am\'et'
lorem ipsum dolor sit am\"et"

'"lorem ipsum dolor sit am\'et"'
"'lorem ipsum dolor sit am\"et'"

lorem ipsum '"dolor"' sit am\'et
lorem ipsum "'dolor'" sit am\"et

lorem ipsum'" dolor "'sit am\'et
lorem ipsum"' dolor '"sit am\"et

'"lorem" ipsum dolor sit am\'et'
"'lorem' ipsum dolor sit am\"et"

lorem --ipsum dolor-- sit am\--et
lorem ---ipsum dolor--- sit am\--et
lorem ----ipsum dolor---- sit am\--et
lorem -----ipsum dolor----- sit am\--et

lorem ...ipsum dolor... sit am\...et
lorem ....ipsum dolor.... sit am\...et
lorem .....ipsum dolor..... sit am\...et
lorem ......ipsum dolor...... sit am\...et
</div>
EOL;
$str[0] = str_replace("\n\n", "\n", $str[0]);
$str[] = <<<EOL
<p>lorem -- ipsum 'dolor' "sit" --- amet ...</p>
<p>lorem \-- ipsum \'dolor\' \"sit\" \--\- amet \...</p>
<pre>lorem -- ipsum 'dolor' "sit" --- amet ...
lorem \-- ipsum \'dolor\' \"sit\" \--\- amet \...</pre>
<p>lor<code>em -- ipsum 'dolor' "sit" --- am</code>et ...</p>
<p>lor<code>em \-- ipsum \'dolor\' \"sit\" \--\- am</code>et \...</p>
EOL;
$str[] = <<<EOL
<p>"lorem ipsum '45"</p>
<p>'lorem ipsum '45'</p>
EOL;
$str[] = <<<EOL
<p>He said : here it is.</p>
<p>That's what I said ; that's what he said.</p>
<p>What ?</p>
EOL;

$parser = new Converter\Typography();

$text = "";

foreach($str as $v) {
    $text .= '<pre>' . htmlentities($v) . '</pre>';
    $text .= '<div>' . $parser->run($v) . '</div>';
    $text .= '<hr>';
    $v = str_replace(' ', '!', $v);
    $v = str_replace('<div!', '<div ', $v);
    $text .= '<pre>' . htmlentities($v) . '</pre>';
    $text .= '<div>' . $parser->run($v) . '</div>';
    $text .= '<hr>';
    $v = str_replace(array('ipsum', 'dolo'), array('ip<b>s</b>um', 'do<i>l</i>o'), $v);
    $text .= '<pre>' . htmlentities($v) . '</pre>';
    $text .= '<div>' . $parser->run($v) . '</div>';
    $text .= '<hr>';
}

// debug ...
echo str_replace(
    array(
        $parser->q[0],
        $parser->q[1],
        $parser->Q[0],
        $parser->Q[1],
        $parser->d[0],
        $parser->d[1],
        $parser->e[0],
        $parser->s[0],
        $parser->s[1]
    ),
    array(
        '<span style="color:green;">' . $parser->q[0] . '</span>',
        '<span style="color:green;">' . $parser->q[1] . '</span>',
        '<span style="color:blue;">' . $parser->Q[0] . '</span>',
        '<span style="color:blue;">' . $parser->Q[1] . '</span>',
        '<span style="color:brown;">' . $parser->d[0] . '</span>',
        '<span style="color:red;">' . $parser->d[1] . '</span>',
        '<span style="color:yellow;">' . $parser->e[0] . '</span>',
        '<span style="background:gray;">' . $parser->s[0] . '</span>',
        '<span style="background:orange;">' . $parser->s[1] . '</span>'
    ),
$text);

?>
  </body>
</html>