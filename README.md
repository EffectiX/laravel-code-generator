# Laravel Code Generator

This package generates pseudo-random codes. You can configure the character pool and the code length.

## Usage

This example shows how to generate a random hex color code. First, we define the character pool to be that of a hexadecimal color. Then the length (6). Then when we make the code, we get a valid hex representing a color.
```php
    $hex = CodeGen::init()->setCharacterPool('1234567890abcdef')->setCodeLength(6)->make();
    echo "<div style='width:100px;height:100px;background-color:#{$hex}'></div>";
```

This versatile code generator can be used to generate any sort of string by selecting characters at random from another string, which I lovingly call, the character pool string. (I enjoy pools in a hot summer... Do you?)
