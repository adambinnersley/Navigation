# Navigation
Creates a HTML navigation menu and Breadcrumb items from a PHP array

## Installation

Installation is available via [Composer/Packagist](https://packagist.org/packages/adamb/database), you can add the following line to your `composer.json` file:

```json
"adamb/navigation": "^1.0"
```

or

```sh
composer require adamb/navigation
```

## Features

- Build HTML navigation from PHP array
- Can build multi-dimensional menus from multi-dimensional arrays
- Create breadcrumb menus
- Customisable HTML classes
- Change the current selected item (if not the current page)

## License

This software is distributed under the [MIT](https://github.com/AdamB7586/pdo-dbal/blob/master/LICENSE) license. Please read LICENSE for information on the
software availability and distribution.

## Usage

Menus and breadcrumbs can be created from either simple arrays or multi-dimensional arrays

### Basic Navigation Menu

#### PHP Code
```php

require 'src/navigation.php';

use Nav\Navigation;

$menu = array(
    'Home' => '/',
    'Link Text' => '/link-2',
    'Sample' => '/sample',
    'Another Page' => '/yet-another-link',
    'Google' => 'https://www.google.co.uk',
    'Final Link' => '/final-page',
);

// For this example we are saying we are on the home page of the website but you can use something like $_SERVER['REQUEST_URI'] or filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL)
$currentUrl = '/';

$navigation = new Navigation($menu, $currentUrl);
echo($navigation->createNavigation());

```

#### Output
```html
    <ul class="nav navbar-nav">
        <li class="active"><a title="Home" class="active">Home</a></li>
        <li><a href="/link-2" title="Link Text">Link Text</a></li>
        <li><a href="/sample" title="Sample">Sample</a></li>
        <li><a href="/yet-another-link" title="Another Page">Another Page</a></li>
        <li><a href="https://www.google.co.uk" title="Google">Google</a></li>
        <li><a href="/final-page" title="Final Link">Final Link</a></li>
    </ul>
```