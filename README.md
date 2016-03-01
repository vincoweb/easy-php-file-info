# easy-php-file-info
Get easily file information from local and also remote file (mime, extension, file name ...)

##Installation
```html
composer install vincoweb/easy-php-file-info
```

## Usage

```html
$finfo = new \VincoWeb\FileInfo\FileInfo();
$i = $finfo->get($file_link [, bool $return_object = false ]);
```

Variable "$file_link" can contains **path** and also **URL**. </br>
Variable "$return_object" is optional. Set to **true** for return object instead of array.

## Example

### Code

```html
<?php
require __DIR__ . '/../vendor/autoload.php';

$finfo = new \VincoWeb\FileInfo\FileInfo();

try {
	$i = $finfo->get('https://www.google.sk/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png');
} catch (FileNotFoundException  $e) {
	echo "FileNotFound Exception('{$e->getMessage()}')";
} catch (Exception $e) {
	echo "Caught Exception ('{$e->getMessage()}')";
}

```
### Result

```html
Array ( [link] => https://www.google.sk/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png [mime] => image/png [size] => 13504 [last_modified] => Fri, 04 Sep 2015 22:33:08 GMT [etag] => [basename] => googlelogo_color_272x92dp.png [extension] => png [type] => image [width] => 544 [height] => 3 )
```

Result returns array with these keys:
* **link** - where file was finded (if there was redirection, it contains reditected location)
* **mime** - mime type (only from header, if $link is URL).
* **size** - file size (if is taken from header, it can contain value "-1")
* **last_modified** - date of last modified
* **etag** - etag if remote file header etag is set otherwise md5 hash of file
* **basename** - basename, name of file
* **extension** - file extension got from header(if file is image, it contains real extension )
* **type** - mime type or string "image" if file is normal image
* [**width**] - width dimension, **this key exists only if file is image**
* [**height**] - height dimension, **this key exists only if file is image**


Class can throw **FileNotFoundException** (e.g if URL or path,"$file_link", does not contain file)


## Features

**IF YOU WANT NEW FEATURES WRITE [NEW ISSUE](https://github.com/vincoweb/easy-php-file-info/issues/new) PLS :)**


## Licence
[Unlicense](http://unlicense.org/). You can do what you want. Be free! 