Typography Plugin for Mecha CMS
===============================

> Inspired by the **SmartyPants** plugin created by [John Gruber](http://daringfireball.net/projects/smartypants "John Gruber’s Website").

Typography
----------

The `Converter\Typography` class will convert some plain ASCII punctuation characters into “smart” punctuation HTML entities:

 - Convert straight quotes (`"` and `'`) into “curly” quote HTML entities
 - Convert dashes (`--` and `---`) into ‘en-dash’ and ‘em-dash’ HTML entities
 - Convert three consecutive dots (`...`) into an ‘ellipsis’ HTML entity.

This class will not modify characters within `<pre>`, `<code>`, `<kbd>` or `<script>` tag block because these tags are commonly used to display text where smart quotes and other “smart punctuation” would not be appropriate, such as source code or example markup.

---

### Class Usage

~~~ .php
require '../workers/converter.typography.php';

$parser = new Converter\Typography();
$parser->ignore = 'pre|code'; // settings ...

echo $parser->run('He said, "Yes, you\'re gross!"');
~~~