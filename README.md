# Navigation
Creates a HTML navigation menu and Breadcrumb menu from a PHP array

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

// For this example we are saying we are on the home page of the website
// You should use something like $_SERVER['REQUEST_URI'] or filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL)
$currentURI = '/';

$navigation = new Navigation($menu, $currentURI);
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

### Multi Level Navigation Menu

#### PHP Code
```php
require 'src/navigation.php';

use Nav\Navigation;

$menu = array(
    'Home' => '/',
    'Link Text' => '/link-2',
    'Sample Submenu' => '/sample',
    array(
        'Sub item 1' => '/sub-pages/subpage1',
        'Sub item 2' => '/sub-pages/subpage2',
        'Has another level' => '/sub-pages/has-sub-pages',
        array(
            'Final Level' => '/sub-sub-pages/final-sub-level',
            'Hello World' => '/sub-sub-pages/hello-world',
        ),
    ),
    'Another Page' => '/yet-another-link',
    'Google' => 'https://www.google.co.uk',
    'Final Link' => '/final-page',
);

$currentURI = '/sub-sub-pages/hello-world'; // $_SERVER['REQUEST_URI']

$navigation = new Navigation($menu, $currentURI);
echo($navigation->createNavigation());

```

#### Output
```html

<ul class="nav navbar-nav">
    <li><a href="/" title="Home">Home</a></li>
    <li><a href="/link-2" title="Link Text">Link Text</a></li>
    <li class="active"><a href="/sample" title="Sample Submenu" class="active">Sample Submenu</a>
        <ul>
            <li><a href="/sub-pages/subpage1" title="Sub item 1">Sub item 1</a></li>
            <li><a href="/sub-pages/subpage2" title="Sub item 2">Sub item 2</a></li>
            <li class="active"><a href="/sub-pages/has-sub-pages" title="Has another level" class="active">Has another level</a>
                <ul>
                    <li><a href="/sub-sub-pages/final-sub-level" title="Final Level">Final Level</a></li>
                    <li class="active"><a href="/sub-sub-pages/hello-world" title="Hello World" class="active">Hello World</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="/yet-another-link" title="Another Page">Another Page</a></li>
    <li><a href="https://www.google.co.uk" title="Google">Google</a></li>
    <li><a href="/final-page" title="Final Link">Final Link</a></li>
</ul>

```

### Breadcrumb menu

#### PHP Code
```php
require 'src/navigation.php';

use Nav\Navigation;

$menu = array(
    'Home' => '/',
    'Link Text' => '/link-2',
    'Sample Submenu' => '/sample',
    array(
        'Sub item 1' => '/sub-pages/subpage1',
        'Sub item 2' => '/sub-pages/subpage2',
        'Has another level' => '/sub-pages/has-sub-pages',
        array(
            'Final Level' => '/sub-sub-pages/final-sub-level',
            'Hello World' => '/sub-sub-pages/hello-world',
        ),
    ),
    'Another Page' => '/yet-another-link',
    'Google' => 'https://www.google.co.uk',
    'Final Link' => '/final-page',
);

$currentURI = '/sub-sub-pages/hello-world'; // $_SERVER['REQUEST_URI']

$navigation = new Navigation($menu, $currentURI);

// Example 1
echo($navigation->createBreadcrumb());

// Example 2
echo($navigation->setBreadcrumbElement('ol')->createBreadcrumb(true, 'my-bc-class', 'bc-item'));

// Example 3
echo($navigation->setBreadcrumbElement('nav')->createBreadcrumb());

// Example 4
echo($navigation->createBreadcrumb(false));

```

#### Output
```html

// Example 1
<ul class="breadcrumb">
    <li class="breadcrumb-item"><a href="/" title="Home">Home</a></li>
    <li class="breadcrumb-item"><a href="/sample" title="Sample Submenu">Sample Submenu</a></li>
    <li class="breadcrumb-item"><a href="/sub-pages/has-sub-pages" title="Has another level">Has another level</a></li>
    <li class="breadcrumb-item active">Hello World</li>
</ul>

// Exmaple 2
<ol class="my-bc-class">
    <li class="bc-item"><a href="/" title="Home">Home</a></li>
    <li class="bc-item"><a href="/sample" title="Sample Submenu">Sample Submenu</a></li>
    <li class="bc-item"><a href="/sub-pages/has-sub-pages" title="Has another level">Has another level</a></li>
    <li class="bc-item active">Hello World</li>
</ol>

// Example 3
<nav class="breadcrumb">
    <a href="/" title="Home" class="breadcrumb-item">Home</a>
    <a href="/sample" title="Sample Submenu" class="breadcrumb-item">Sample Submenu</a>
    <a href="/sub-pages/has-sub-pages" title="Has another level" class="breadcrumb-item">Has another level</a>
    <span class="breadcrumb-item active">Hello World</span>
</nav>

// Example 4
<a href="/" title="Home">Home</a> &gt; <a href="/sample" title="Sample Submenu">Sample Submenu</a> &gt; <a href="/sub-pages/has-sub-pages" title="Has another level">Has another level</a> &gt; Hello World
```

### Change HTML Classes/Navigation ID

You can change the default class elements on the navigation and breadcrumb items by using the following commands

#### PHP Code
```php
require 'src/navigation.php';

use Nav\Navigation;

$menu = array(
    'Home' => '/',
    'Link Text' => '/link-2',
    'Sample Submenu' => '/sample',
    array(
        'Sub item 1' => '/sub-pages/subpage1',
        'Sub item 2' => '/sub-pages/subpage2',
        'Has another level' => '/sub-pages/has-sub-pages',
        array(
            'Final Level' => '/sub-sub-pages/final-sub-level',
            'Hello World' => '/sub-sub-pages/hello-world',
        ),
    ),
    'Another Page' => '/yet-another-link',
);

$navigation = new Navigation($menu, '/sub-sub-pages/hello-world');
$navigation->setActiveClass('current-item c-item')
           ->setNavigationClass('my-nav-class')
           ->setNavigationID('my-unique-navigation-id')
           ->setDropDownClass('my-drop-down-class drop-down');
echo($navigation->createNavigation());

```
#### Output
```html

<ul id="my-unique-navigation-id" class="my-nav-class">
    <li><a href="/" title="Home">Home</a></li>
    <li><a href="/link-2" title="Link Text">Link Text</a></li>
    <li class="current-item c-item"><a href="/sample" title="Sample Submenu" class="current-item c-item">Sample Submenu</a>
        <ul class="my-drop-down-class drop-down">
            <li><a href="/sub-pages/subpage1" title="Sub item 1">Sub item 1</a></li>
            <li><a href="/sub-pages/subpage2" title="Sub item 2">Sub item 2</a></li>
            <li class="current-item c-item"><a href="/sub-pages/has-sub-pages" title="Has another level" class="current-item c-item">Has another level</a>
                <ul class="my-drop-down-class drop-down">
                    <li><a href="/sub-sub-pages/final-sub-level" title="Final Level">Final Level</a></li>
                    <li class="current-item c-item"><a href="/sub-sub-pages/hello-world" title="Hello World" class="current-item c-item">Hello World</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="/yet-another-link" title="Another Page">Another Page</a></li>
</ul>

```

### Additional Features

You can also choose to display only a given number of navigation levels starting at any level you choose

#### PHP Code
```php
require 'src/navigation.php';

use Nav\Navigation;

$menu = array(
    'Home' => '/',
    'Link Text' => '/link-2',
    'Sample Submenu' => '/sample',
    array(
        'Sub item 1' => '/sub-pages/subpage1',
        'Sub item 2' => '/sub-pages/subpage2',
        'Has another level' => '/sub-pages/has-sub-pages',
        array(
            'Final Level' => '/sub-sub-pages/final-sub-level',
            'Hello World' => '/sub-sub-pages/hello-world',
        ),
    ),
    'Another Page' => '/yet-another-link',
    'Google' => 'https://www.google.co.uk',
    'Final Link' => '/final-page',
);

$navigation = new Navigation($menu, '/sub-sub-pages/hello-world');

// Example 1
$levels = 1;

echo($navigation->createNavigation($levels));

// Example 2
$levels = 2;
$start_level = 1;

echo($navigation->createNavigation($levels, $start_level));

```

#### Output
```html

// Example 1
// Only displays the top menu level
<ul class="nav navbar-nav">
    <li><a href="/" title="Home">Home</a></li>
    <li><a href="/link-2" title="Link Text">Link Text</a></li>
    <li class="active"><a href="/sample" title="Sample Submenu" class="active">Sample Submenu</a></li>
    <li><a href="/yet-another-link" title="Another Page">Another Page</a></li>
    <li><a href="https://www.google.co.uk" title="Google">Google</a></li>
    <li><a href="/final-page" title="Final Link">Final Link</a></li>
</ul>

// Example 2
// Display 2 levels of the navigation starting at level 1 (the first sub level of the current item)
<ul class="nav navbar-nav">
    <li><a href="/sub-pages/subpage1" title="Sub item 1">Sub item 1</a></li>
    <li><a href="/sub-pages/subpage2" title="Sub item 2">Sub item 2</a></li>
    <li class="active"><a href="/sub-pages/has-sub-pages" title="Has another level" class="active">Has another level</a>
        <ul>
            <li><a href="/sub-sub-pages/final-sub-level" title="Final Level">Final Level</a></li>
            <li class="active"><a href="/sub-sub-pages/hello-world" title="Hello World" class="active">Hello World</a></li>
        </ul>
    </li>
</ul>

```