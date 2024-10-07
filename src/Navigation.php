<?php
namespace Nav;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Nav\Operators\Check;

class Navigation
{
    /**
     * This should be the array that you wish to build the menu from
     * @var array
     */
    protected $navigation = [];
    
    /**
     * The current items as an array
     * @var array
     */
    protected $current;
    
    /**
     * The current URI string
     * @var string This needs to be the URI and match the navigation array given URI
     */
    public $currentURL;
    
    /**
     * This should be the navigation object after the array has been read
     * @var object The navigation object
     */
    protected $nav;
    
    /**
     * The navigation menu as a HTML string needed to build the menu across methods
     * @var string
     */
    protected $navItem;
    
    /**
     * Set by the build menu function will be true if sub-menu exists else will be false
     * @var boolean
     */
    protected $sub = false;
    
    /**
     * If there is no level set to false else will set integer value when building the menu
     * @var boolean|int
     */
    protected $currentLevel = false;
    
    /**
     * This counts the number of links output by the menu
     * @var int
     */
    protected $linkCount = 0;

    /**
     * The class assigned to current menu
     * @var string
     */
    public $activeClass = 'active';
    
    /**
     * The class assigned to the menu item
     * @var string
     */
    public $navigationClass = 'nav navbar-nav';
    
    /**
     * The class a li item should have
     * @var string
     */
    public $itemClass = '';
    
    /**
     * The class that should be given to all a elements
     * @var string
     */
    public $linkClass = '';
    
    /**
     * The HTML ID assigned to the menu item
     * @var string
     */
    public $navigationID = '';
    
    /**
     * The drop-down class assigned to the UL sub-menu elements of the menu
     * @var string
     */
    public $dropdownClass = '';
    
    /**
     *The type of element to wrap any text in links with e.g. span
     * @var string
     */
    public $textWrap = '';
    
    /**
     * If you only want to wrap text for top level links set to true else will wrap for all link levels
     * @var boolean
     */
    public $onlyWrapTL = true;
    
    /**
     * Gets the navigation items and sets the current menu hierarchy
     * @param array $navArray
     * @param string $currentUrl This should be the URL of the current page
     */
    public function __construct($navArray, $currentUrl)
    {
        $this->setNavigationArray($navArray);
        $this->setCurrentURL($currentUrl);
    }
    
    /**
     * Sets the navigation array menu to the array that should be parsed for the menu
     * @param array $array This should be the array of items you wish to make into a menu
     * @return $this
     */
    public function setNavigationArray($array)
    {
        if (is_array($array)) {
            $this->navigation = $array;
        }
        return $this;
    }
    
    /**
     * Returns the navigation array
     * @return array Will return a blank array if not set else will return the menu array
     */
    public function getNavigationArray()
    {
        return $this->navigation;
    }
    
    /**
     * Set the current URL so that the active menu items can be added
     * @param string $url This should be the URL of the page you want to set as the current page so active items can be retrieved
     * @return $this
     */
    public function setCurrentURL($url)
    {
        if (is_string($url)) {
            $this->currentURL = strtolower($url);
            $this->getCurrent();
        }
        return $this;
    }
    
    /**
     * Gets the URL that is set as the current item
     * @return string This should be the URL set as the current location
     */
    public function getCurrentURL()
    {
        return $this->currentURL;
    }
    
    /**
     * Sets the class(es) that is assigned to active menu items
     * @param string $className This should be the class value you want to add to active menu items
     * @return $this
     */
    public function setActiveClass($className)
    {
        if (Check::checkIfStringSet($className)) {
            $this->activeClass = trim($className);
        }
        return $this;
    }
    
    /**
     * Returns the class(es) that is given to active menu items
     * @return string The classes for active items is returned
     */
    public function getActiveClass()
    {
        return $this->activeClass;
    }
    
    /**
     * Returns the class given to link items
     * @return string
     */
    public function getItemClass()
    {
        return $this->itemClass;
    }
    
    /**
     * Sets a class to assign to all link items
     * @param string $className
     * @return $this
     */
    public function setItemClass($className)
    {
        if (Check::checkIfStringSet($className)) {
            $this->itemClass = trim($className);
        }
        return $this;
    }
    
    /**
     * Gets the class assigned to all link elements
     * @return string
     */
    public function getLinkClass()
    {
        return $this->linkClass;
    }
    
    /**
     * Sets the class to assign to all link elements
     * @param string $className
     * @return $this
     */
    public function setLinkClass($className)
    {
        if (Check::checkIfStringSet($className)) {
            $this->linkClass = trim($className);
        }
        return $this;
    }
    
    /**
     * Sets the class(es) for the HTML navigation item
     * @param string $classes This should be the class or lasses that you want to give to the navigation item
     * @return $this
     */
    public function setNavigationClass($classes)
    {
        if (Check::checkIfStringSet($classes)) {
            $this->navigationClass = trim($classes);
        }
        return $this;
    }
    
    /**
     * Returns the navigation class(es) for the HTML item
     * @return string If the string is not empty will return the classes assigned else will return false
     */
    public function getNavigationClass()
    {
        return $this->navigationClass;
    }
    
    /**
     * Sets the HTML ID for the navigation item
     * @param string $id This should be the ID that you want to give to the HTML navigation object
     * @return $this
     */
    public function setNavigationID($id)
    {
        if (is_string($id)) {
            $this->navigationID = trim($id);
        }
        return $this;
    }
    
    /**
     * Returns the navigation ID string
     * @return string|false If the ID is not empty will return the ID string else will return false
     */
    public function getNavigationID()
    {
        if (!empty($this->navigationID)) {
            return $this->navigationID;
        }
        return false;
    }
    
    /**
     * Sets the drop-down class to use on the UL elements of the navigation menu
     * @param string $classes This should be the classes that you want to give any sub-menu items on the UL elements
     * @return $this
     */
    public function setDropDownClass($classes)
    {
        if (is_string($classes)) {
            $this->dropdownClass = trim($classes);
        }
        return $this;
    }
    
    /**
     * Returns the drop-down class for the UL elements if it is not empty
     * @return string|false If the drop-down class is not empty will return the classes else will return false
     */
    public function getDropDownClass()
    {
        if (!empty($this->dropdownClass)) {
            return $this->dropdownClass;
        }
        return false;
    }
    
    /**
     * Sets the element to wrap link text with e.g span or div
     * @param string $element This should be the element type e.g. 'span'
     * @return $this
     */
    public function setLinkTextWrapElement($element)
    {
        if (Check::checkIfStringSet($element)) {
            $this->textWrap = $element;
        }
        return $this;
    }
    
    /**
     * Gets the link text wrap element if its set
     * @return string
     */
    public function getLinkTextWrapElement()
    {
        return $this->textWrap;
    }
    
    /**
     * Sets if link text should only be wrapped in the element for top level links only
     * @param boolean $boolean If you only want to wrap top level link text with the given element st to true else for all links set to false
     * @return $this
     */
    public function setLinkTextWrapTLOnly($boolean)
    {
        $this->onlyWrapTL = boolval($boolean);
        return $this;
    }
    
    /**
     * Gets the link text wrap only top level links only setting
     * @return boolean
     */
    public function getLinkTextWrapTLOnly()
    {
        return boolval($this->onlyWrapTL);
    }
    
    /**
     * Creates the current menu items which are selected from the navigation item hierarchy
     */
    protected function getCurrent()
    {
        $found = false;
        $navItem = $this->parseArray();
        foreach ($navItem as $item => $itemURL) {
            $this->current[$navItem->getDepth()]['text'] = $item;
            $this->current[$navItem->getDepth()]['link'] = $itemURL;
            if ($itemURL === $this->currentURL) {
                for ($depth = ($navItem->getDepth() + 1); $depth <= 5; $depth++) {
                    unset($this->current[$depth]);
                }
                $found = true;
                break;
            }
        }
        
        if ($found !== true) {
            $this->current = [];
        }
    }
    
    /**
     * Returns the current navigation structure
     * @return array
     */
    public function getCurrentItems()
    {
        return $this->current;
    }
    
    /**
     * Creates a navigation menu from any multi-dimensional array
     * @param int $levels This should be the number of levels which you wish to display (0 = All)
     * @param int $startLevel Should be the starting level of the navigation
     * @return string|false Returns the navigation as a string if it exists else if no menu exists returns false
     */
    public function createNavigation($levels = 0, $startLevel = 0)
    {
        $this->linkCount = 0;
        $this->sub = false;
        $this->currentLevel = false;
        $this->navItem = '<ul'.Check::checkIfSet($this->getNavigationID(), ' id="'.$this->getNavigationID().'"').Check::checkIfSet($this->getNavigationClass(), ' class="'.$this->getNavigationClass().'"').'>';
        $it = $this->parseArray();
        $items = 0;
        if (is_array($it) || is_object($it)) {
            foreach ($it as $text => $link) {
                if (isset($link) && !is_numeric($text)) {
                    $this->buildMenu($it, $text, $link, (intval($levels) === 0 || $startLevel === 0 ? intval($levels) : ($levels + 1)), intval($startLevel));
                }
                $items++;
            }
        }
        for ($i = $this->currentLevel; $i > 0; $i--) {
            $this->closeLevel();
        }
        if ($startLevel == 0) {
            $this->closeLevel();
        }
        return ($items === 1 || $this->linkCount > 1 ? $this->navItem : false);
    }
    
    /**
     * Builds the menu items
     * @param object $it This should be the object created withe parsing the array items
     * @param string $text This is the menu item text
     * @param string $link This is the menu item link
     * @param int|false $levels The maximum number of levels to show
     * @param int $start The level that you wish to start at when building the menu
     */
    protected function buildMenu($it, $text, $link, $levels, $start)
    {
        $current = Check::checkIfSet($this->checkLinkOffsetMatch($link, $it->getDepth()), ' '.$this->getActiveClass());
                
        if ($start === 0 || $this->checkLinkOffsetMatch($link, ($start - 1)) || $this->sub === true) {
            if ($start !== 0 && $this->checkLinkOffsetMatch($link, ($start - 1))) {
                $this->sub = true;
            }
            if ($levels === 0 || $it->getDepth() < $levels) {
                $this->buildItem($it, $text, $link, $start, $current);
            }
        }
    }
    
    /**
     * Checks to see if the next item is on the same level if so adds the close/open tags to the navigation item variable
     * @param object $it This should be the object created withe parsing the array items
     * @param int $start The start level of the menu
     * @param string $current This should be the current string
     */
    protected function nextItem($it, $start, $current)
    {
        if ($this->sub === true && ($start - 1) == $it->getDepth()) {
            $this->sub = false;
        } else {
            $this->navItem.= Check::greaterThanOrEqual($this->linkCount, 1, '</li>').'<li class="'.$this->getItemClass().$current.'">';
        }
    }
    
    /**
     * Creates an element to add to the navigation
     * @param object $it This should be the object created withe parsing the array items
     * @param string $text This is the menu item text
     * @param string $link This is the menu item link
     * @param int $start The level that you wish to start at when building the menu
     * @param string $current If this item is a current element will have the class information as part of the string
     */
    protected function buildItem($it, $text, $link, $start, $current)
    {
        if (is_numeric($this->currentLevel)) {
            if ($start != 0 && $this->currentLevel == 0) {
                $this->currentLevel = 1;
            }
            if ($this->currentLevel == $it->getDepth()) {
                $this->nextItem($it, $start, $current);
            } elseif ($this->currentLevel < $it->getDepth()) {
                for ($i = $this->currentLevel; $i < $it->getDepth(); $i++) {
                    $this->navItem.= '<ul'.Check::checkIfSet($this->getDropDownClass(), ' class="'.$this->getDropDownClass().'"').'><li class="'.$this->getItemClass().$current.'">';
                }
            } else {
                for ($i = $it->getDepth(); $i < $this->currentLevel; $i++) {
                    $this->closeLevel();
                }
                $this->nextItem($it, $start, $current);
            }
        } elseif ($start === 0 || ($this->sub === true && $it->getDepth() >= $start)) {
            $this->navItem.= '<li class="'.$this->getItemClass().$current.'">';
        }

        $this->currentLevel = $it->getDepth();
        if ($start === 0 || ($this->sub === true && $it->getDepth() >= $start)) {
            $this->navItem.= $this->createLinkItem($link, $text, $start, $it->getDepth());
        }
    }
    
    /**
     * Close the current level
     */
    protected function closeLevel()
    {
        $this->navItem.= '</li></ul>';
    }

    /**
     * Creates a new link/option item for use in a menu depending on drop-down variable settings
     * @param string $link This should be the URL of the link
     * @param string $text This should be the link/option text value
     * @return string
     */
    protected function createLinkItem($link, $text, $start, $depth)
    {
        if ($this->checkLinkOffsetMatch($link, 0) || $this->checkLinkOffsetMatch($link, 1) || $this->checkLinkOffsetMatch($link, 2) || $this->checkLinkOffsetMatch($link, 3)) {
            $current = (($start >= 1 && $this->checkLinkOffsetMatch($link, 0) && isset($this->current[1]['link'])) ? '' : ' '.$this->getActiveClass().'');
        } else {
            $current = '';
        }
        if ($this->checkLinkOffsetMatch($link, 0) && $link == '/') {
            $href = '';
        } else {
            $href = ' href="'.$link.'"';
        }
        $this->linkCount++;
        return '<a'.$href.' title="'.$text.'" class="'.$this->getLinkClass().$current.'">'.$this->textWrap($text, $depth).'</a>';
    }
    
    /**
     * If the link text needs to be wrapped with an element will be done here else will just return original value
     * @param string $text The text for the link element
     * @param int $depth The depth of the current link element
     * @return string Will return the link text string
     */
    protected function textWrap($text, $depth)
    {
        if (empty($this->getLinkTextWrapElement()) || ($depth >= 1 && $this->getLinkTextWrapTLOnly() === true)) {
            return $text;
        }
        return '<'.$this->getLinkTextWrapElement().'>'.$text.'</'.$this->getLinkTextWrapElement().'>';
    }
    
    /**
     * Checks to see if a link matches the link in one of the current arrays
     * @param string $link This should be the link you are checking for
     * @param int $offset The offset point in the array
     * @return boolean If it matches will return true else will return false
     */
    protected function checkLinkOffsetMatch($link, $offset)
    {
        if (isset($this->current[$offset]['link'])) {
            if ($link == $this->current[$offset]['link']) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Iterates through the list of menu items
     * @return object
     */
    protected function parseArray()
    {
        if (!is_object($this->nav) && is_array($this->getNavigationArray())) {
            $this->nav = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->getNavigationArray()));
        }
        return $this->nav;
    }
}
