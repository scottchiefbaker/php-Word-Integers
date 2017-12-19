# PHP-Word-Integers
PHP class that converts numbers to words strings and vice versa. Similar to GfyCat/Twitch's URL [naming scheme](https://medium.com/@Gfycat/naming-conventions-97960fc40179).

## Usage:

```PHP
require("wordInt.class.php");

$word   = wordInt::number_to_string(1234567890);
$number = wordInt::string_to_number("ConveyNovaAiry");
```

Uses a `dictionary.txt` file for the mapping. The dictionary must be located in the same directory as the PHP class file.

#### Alternatives:
* [Hashids](http://hashids.org/)
