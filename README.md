# Torchlight Client for Ibis

[![Latest Stable Version](https://poser.pugx.org/torchlight/torchlight-ibis/v)](//packagist.org/packages/torchlight/torchlight-ibis) [![Total Downloads](https://poser.pugx.org/torchlight/torchlight-ibis/downloads)](//packagist.org/packages/torchlight/torchlight-ibis) [![License](https://poser.pugx.org/torchlight/torchlight-ibis/license)](//packagist.org/packages/torchlight/torchlight-ibis)


A [Torchlight](https://torchlight.dev) syntax highlighting extension for the ebook builder [Ibis](https://github.com/themsaid/ibis).

Torchlight is a VS Code-compatible syntax highlighter that requires no JavaScript, supports every language, every VS Code theme, line highlighting, git diffing, and more.

## Installation

To install, require the package from composer:

```
composer require torchlight/torchlight-ibis
```

If you haven't already, Composer will ask you to create a `composer.json` file in the root of your book directory.  

In your `ibis.php` file, add the following to your configuration:

```php
return [
    /**
     * The book title.
     */
    'title' => 'Laravel Queues in Action',

    // .....

    'configure_commonmark' => function ($environment) {
        \Torchlight\Ibis\TorchlightExtension::make()->register($environment);
    },
];
```

At the top of your `ibis.php` file, you'll also need to add the composer autoloader if it's not already there:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

return [
    /**
     * The book title.
     */
    'title' => 'Laravel Queues in Action',

    // .....

    'configure_commonmark' => function ($environment) {
        \Torchlight\Ibis\TorchlightExtension::make()->register($environment);
    },
];
```

To publish the Torchlight configuration file, you may run `./vendor/bin/torchlight`. It will place a `torchlight.php` file next to your `ibis.php` file.

You'll need to add a few styles to your stylesheet. These are slightly different from the default styles that Torchlight uses, because Ibis uses mPDF to convert the HTML to a PDF, and it has a few specific requirements.

Here is a good starting set of styles, but feel free to tweak them to match your needs.

```css
/*
 Margin and rounding are personal preferences,
 overflow-x-auto is recommended.
*/
pre {
    page-break-inside: avoid;
    border: solid 1px #eee;
    margin-bottom: 30px;
    border-radius: 5px;
    overflow-x: auto;
}

/*
 Add some vertical padding and expand the width
 to fill its container. The horizontal padding
 comes at the line level so that background
 colors extend edge to edge.
*/
pre div.torchlight {
    padding-top: 16px;
    padding-bottom: 16px;
}

/*
 Horizontal line padding to match the vertical
 padding from the code block above. Vertical 
 for line height.
*/
pre div.torchlight .line {
    padding: 2px 16px;
}
```
