# PHP-Word-Integers
PHP class that converts numbers to words strings and vice versa. Similar to GfyCat/Twitch's URL [naming scheme](https://medium.com/@Gfycat/naming-conventions-97960fc40179).

## Usage:

```PHP
require("wordInt.class.php");

$word   = number_to_string(57893);
$number = string_to_number("FloodsNeedyCharm");
```

Uses a `dictionary.txt` file for the mapping. The dictionary must be located in the same directory as the PHP class file.

#### Alternatives:
* [Hashids](http://hashids.org/)
