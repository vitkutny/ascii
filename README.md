       _|_|       _|_|_|     _|_|_|   _|_|_|   _|_|_|  
     _|    _|   _|         _|           _|       _|    
     _|_|_|_|     _|_|     _|           _|       _|    
     _|    _|         _|   _|           _|       _|    
     _|    _|   _|_|_|       _|_|_|   _|_|_|   _|_|_|  
     
     _|_   _       _|_      ,_    _          _|    _   ,_    _   ,_  
      |   |/  /\/   |      /  |  |/  /|/|   / |   |/  /  |  |/  /  | 
      |_/ |_/  /\/  |_/       |/ |_/  | |_/ \/|_/ |_/    |/ |_/    |/

Usage
-----

```php
use VitKutny\Ascii\Text;

$text = new Text\Renderer('Hello world!');
$text->setFont(new Text\Font\Figlet(__DIR__ . '/fonts/figlet-font.flf');
$text->render(); //or echo $text;
```

Now this will work fine only in console. Because no lineBreakWrapper and stringWrapper are set.

Wrappers
--------
Basic render in <pre> tag:

```php
$text->setStringWrapper(Text\Wrapper::el('pre'));
$text->setLineBreak(Text\Wrapper::el('br'));
```

Render ascii text in table:

```php
$text->setStringWrapper(Text\Wrapper::el('table'));
$text->setLineWrapper(Text\Wrapper::el('tr'));
$text->setPixelWrapper(Text\Wrapper::el('td'));
```

Using wrappers output will have some usefull classes e.g. for styling.

```html
<table class="ascii-string ascii-font-figlet-font">
  <tr class="ascii-line">
      <td class="ascii-pixel"></td>
      ...
  </tr>
  ...
</table>
```

CharacterLineWrapper also has class containing ASCII number of the character, e.g. for letter A:

```html
<span class="ascii-character ascii-character-65">
  ...
</span>
```

Console
-------

ASCII text renderer works like a charm in console by removing HTML tags and replacing Text\Renderer::$lineBreak by PHP_EOL.
