<?php
namespace Nav;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Navigation{
    /**
     * This should be the array that you wish to build the menu from
     * @var array 
     */
    protected $navigation = array();
    
    /**
     * The current items as an array to allow the class to build the breadcrumb
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
     * The class assigned to current menu and breadcrumb items
     * @var string
     */
    public $activeClass = 'active';
    
    /**
     * The class assigned to the menu item
     * @var string
     */
    public $navigationClass = 'nav navbar-nav';
    
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
     *This is the separator for any breadcrumb items that aren't in the list format 
     * @var string 
     */
    public $separator = ' &gt; ';
    
    /**
     * The type of element that the breadcrumb is normally UL or OL
     * @var string 
     */
    public $breadcrumbElement = 'ul';
    
    /**
     * Gets the navigation items and sets the current menu hierarchy
     * @param array $navArray
     * @param string $currentUrl This should be the URL of the current page
     */
    public function __construct($navArray, $currentUrl) {
        $this->setNavigationArray($navArray);
        $this->setCurrentURL($currentUrl);
    }
    
    /**
     * Sets the navigation array menu to the array that should be parsed for the menu
     * @param array $array This should be the array of items you wish to make into a menu
     * @return $this
     */
    public function setNavigationArray($array) {
        if(is_array($array)) {
            $this->navigation = $array;
        }
        return $this;
    }
    
    /**
     * Returns the navigation array
     * @return array Will return a blank array if not set else will return the menu array
     */
    public function getNavigationArray() {
        return $this->navigation;
    }
    
    /**
     * Set the current URL so that the active menu items can be added 
     * @param string $url This should be the URL of the page you want to set as the current page so active items can be retrieved
     * @return $this
     */
    public function setCurrentURL($url) {
        if(is_string($url) && in_array($url, $this->getNavigationArray())) {
            $this->currentURL = strtolower($url);
            $this->getCurrent();
        }
        return $this;
    }
    
    /**
     * Gets the URL that is set as the current item
     * @return string This should be the URL set as the current location
     */
    public function getCurrentURL() {
        return $this->currentURL;
    }
    
    /**
     * Sets the class(es) that is assigned to active menu and breadcrumb items
     * @param string $className This should be the class value you want to add to active menu items
     * @return $this
     */
    public function setActiveClass($className) {
        if(!empty(trim($className)) && is_string($className)) {
            $this->activeClass = trim($className);
        }
        return $this;
    }
    
    /**
     * Returns the class(es) that is given to active menu and breadcrumb items
     * @return string The classes for active items is returned
     */
    public function getActiveClass() {
        return $this->activeClass;
    }
    
    /**
     * Sets the class(es) for the HTML navigation item
     * @param string $classes This should be the class or lasses that you want to give to the navigation item
     * @return $this
     */
    public function setNavigationClass($classes) {
        if(!empty(trim($classes)) && is_string($classes)) {
            $this->navigationClass = trim($classes);
        }
        return $this;
    }
    
    /**
     * Returns the navigation class(es) for the HTML item
     * @return string If the string is not empty will return the classes assigned else will return false
     */
    public function getNavigationClass() {
        return $this->navigationClass;
    }
    
    /**
     * Sets the HTML ID for the navigation item
     * @param string $id This should be the ID that you want to give to the HTML navigation object
     * @return $this
     */
    public function setNavigationID($id) {
        if(is_string($id)) {
            $this->navigationID = trim($id);
        }
        return $this;
    }
    
    /**
     * Returns the navigation ID string
     * @return string|false If the ID is not empty will return the ID string else will return false
     */
    public function getNavigationID() {
        if(!empty($this->navigationID)) {
            return $this->navigationID;
        }
        return false;
    }
    
    /**
     * Sets the drop-down class to use on the UL elements of the navigation menu
     * @param string $classes This should be the classes that you want to give any sub-menu items on the UL elements
     * @return $this
     */
    public function setDropDownClass($classes) {
        if(is_string($classes)) {
            $this->dropdownClass = trim($classes);
        }
        return $this;
    }
    
    /**
     * Returns the drop-down class for the UL elements if it is not empty
     * @return string|false If the drop-down class is not empty will return the classes else will return false
     */
    public function getDropDownClass() {
        if(!empty($this->dropdownClass)) {
            return $this->dropdownClass;
        }
        return false;
    }
    
    /**
     * Sets the separator for any breadcrumb items that aren't list elements
     * @param string $separator This should be the element you want as the separator for breadcrumb items;
     * @return $this
     */
    public function setBreadcrumbSeparator($separator) {
        if($separator) {
            $this->separator = $separator;
        }
        return $this;
    }
    
    /**
     * The breadcrumb separator will be returned
     * @return string The separator will be returned
     */
    public function getBreadcrumbSeparator() {
        return $this->separator;
    }
    
    /**
     * Sets the main element to give to list style breadcrumb menus
     * @param string $element The main element you want to give to the breadcrumb
     * @return $this
     */
    public function setBreadcrumbElement($element) {
        if(!empty(trim($element)) && is_string($element)) {
            $this->breadcrumbElement = trim($element);
        }
        return $this;
    }
    
    /**
     * Returns the breadcrumb element type
     * @return string false Returns the element to surround the breadcrumb items with (default is UL)
     */
    public function getBreadcrumbElement() {
        if(!empty($this->breadcrumbElement)) {
            return $this->breadcrumbElement;
        }
        return false;
    }
    
    /**
     * Creates the current menu items which are selected from the navigation item hierarchy
     */
    protected function getCurrent() {
        $found = false;
        $navItem = $this->parseArray();
        foreach($navItem as $item => $itemURL) {
            $this->current[$navItem->getDepth()]['text'] = $item;
            $this->current[$navItem->getDepth()]['link'] = $itemURL;
            if($itemURL === $this->currentURL) {
                for($depth = ($navItem->getDepth() + 1); $depth <= 5; $depth++) {
                    unset($this->current[$depth]);
                }
                $found = true;
                break;
            }
        }
        
        if($found !== true) {
            unset($this->current);
            $this->current[0]['text'] = key($this->navigation[0]);
            $this->current[0]['link'] = $this->navigation[0];
        }
    }
    
    /**
     * Returns the current navigation structure
     * @return array
     */
    public function getCurrentItems() {
        return $this->current;
    }
    
    /**
     * Creates a navigation menu from any multi-dimensional array
     * @param int $levels This should be the number of levels which you wish to display (0 = All)
     * @param int $startLevel Should be the starting level of the navigation
     * @return string|false Returns the navigation as a string if it exists else if no menu exists returns false
     */
    public function createNavigation($levels = 0, $startLevel = 0) {
        $this->navItem = '<ul'.($this->getNavigationID() ? ' id="'.$this->getNavigationID().'"' : '').($this->getNavigationClass() ? ' class="'.$this->getNavigationClass().'"' : '').'>';
        $it = $this->parseArray();
        $items = 0;
        foreach($it as $text => $link) {
            if(isset($link) && !is_numeric($text)) {
                $this->buildMenu($it, $text, $link, (intval($levels) === 0 || $startLevel === 0 ? intval($levels) : ($levels + 1)), intval($startLevel));
            }
            $items++;
        }
        
        for($i = $this->currentLevel; $i > 0; $i--) {
            $this->closeLevel();
        }
        if($startLevel == 0) {$this->closeLevel();}
        return ($items === 1 || $this->linkCount > 1 ? $this->navItem : false);
    }
    
    /**
     * Creates a bread-crumb navigation from the $this->current array
     * @return string Returns the bread-crumb information as a string with all of the links included
     */
    public function createBreadcrumb($list = true, $class = 'breadcrumb', $itemClass = 'breadcrumb-item') {
        $breadcrumb = (($list === true && $this->getBreadcrumbElement() !== false) ? '<'.$this->getBreadcrumbElement().(!empty(trim($class)) ? ' class="'.$class.'"' : '').'>' : '');
        if($this->current[0]['link'] == '/') {
            $breadcrumb.= 'Home';
        }
        else{
            $breadcrumb.= (($list === true && $this->isBreadcrumbList() !== false) ? '<li'.(!empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>' : '').'<a href="/" title="Home"'.(!$this->isBreadcrumbList() && !empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>Home</a>'.($list === true && $this->isBreadcrumbList() ? '</li>' : '');
            $numlinks = count($this->current);
            for($i = 0; $i < $numlinks; $i++) {
                if($i == ($numlinks - 1)) {$breadcrumb.= ($list === true ? '<'.($this->isBreadcrumbList() ? 'li' : 'span').(!empty(trim($itemClass)) ? ' class="'.$itemClass.' '.$this->getActiveClass().'"' : '').'>' : $this->getBreadcrumbSeparator()).$this->current[$i]['text'].($list === true ? '</'.($this->isBreadcrumbList() ? 'li' : 'span').'>' : '');}
                else{$breadcrumb.= ($list === true && $this->isBreadcrumbList() ? '<li'.(!empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>' : ($list !== true ? $this->getBreadcrumbSeparator() : '')).'<a href="'.$this->current[$i]['link'].'" title="'.$this->current[$i]['text'].'"'.(!$this->isBreadcrumbList() && !empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>'.$this->current[$i]['text'].'</a>'.($list === true && $this->isBreadcrumbList() ? '</li>' : '');}
            }
        }
        return $breadcrumb.(($list === true && $this->getBreadcrumbElement() !== false) ? '</'.$this->getBreadcrumbElement().'>' : '');
    }
    
    /**
     * Builds the menu items 
     * @param object $it This should be the object created withe parsing the array items
     * @param string $text This is the menu item text
     * @param string $link This is the menu item link
     * @param int|false $levels The maximum number of levels to show
     * @param int $start The level that you wish to start at when building the menu
     */
    protected function buildMenu($it, $text, $link, $levels, $start) {
        $current = ($link === $this->current[$it->getDepth()]['link'] ? ' class="'.$this->getActiveClass().'"' : '');
                
        if($start === 0 || $link === $this->current[($start - 1)]['link'] || $this->sub === true) {
            if($start !== 0 && $link === $this->current[($start - 1)]['link']) {$this->sub = true;}
            if($levels === 0 || $it->getDepth() < $levels) {
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
    protected function nextItem($it, $start, $current) {
        if($this->sub === true && ($start - 1) == $it->getDepth()) {$this->sub = false;}
        else{$this->navItem.= ($this->linkCount >= 1 ? '</li>' : '').'<li'.$current.'>';}
    }
    
    /**
     * Creates an element to add to the navigation
     * @param object $it This should be the object created withe parsing the array items
     * @param string $text This is the menu item text
     * @param string $link This is the menu item link
     * @param int $start The level that you wish to start at when building the menu
     * @param string $current If this item is a current element will have the class information as part of the string
     */
    protected function buildItem($it, $text, $link, $start, $current) {
        if(is_numeric($this->currentLevel)) {
            if($start != 0 && $this->currentLevel == 0) {$this->currentLevel = 1;}
            if($this->currentLevel == $it->getDepth()) {
                $this->nextItem($it, $start, $current);
            }
            elseif($this->currentLevel < $it->getDepth()) {
                for($i = $this->currentLevel; $i < $it->getDepth(); $i++) {$this->navItem.= '<ul'.($this->getDropDownClass() ? ' class="'.$this->getDropDownClass().'"' : '').'><li'.$current.'>';}
            }
            else{
                for($i = $it->getDepth(); $i < $this->currentLevel; $i++) {$this->closeLevel();}
                $this->nextItem($it, $start, $current);
            }
        }
        elseif($start === 0 || ($this->sub === true && $it->getDepth() >= $start)) {
            $this->navItem.= '<li'.$current.'>';
        }

        $this->currentLevel = $it->getDepth();
        if($start === 0 || ($this->sub === true && $it->getDepth() >= $start)) {$this->navItem.= $this->createLinkItem($link, $text, $start);}
    }
    
    /**
     * Close the current level
     */
    protected function closeLevel() {
        $this->navItem.= '</li></ul>';
    }

    /**
     * Creates a new link/option item for use in a menu depending on drop-down variable settings
     * @param string $link This should be the URL of the link
     * @param string $text This should be the link/option text value
     * @return string
     */
    protected function createLinkItem($link, $text, $start) {
        if($link == $this->current[0]['link'] || $link == $this->current[1]['link'] || $link == $this->current[2]['link'] || $link == $this->current[3]['link']) {
            $current = (($start >= 1 && $link == $this->current[0]['link'] && isset($this->current[1]['link'])) ? '' : ' class="'.$this->getActiveClass().'"');
        }
        else{$current = '';}
        if($this->current[0]['link'] == $link && $link == '/') {$href = '';}else{$href = ' href="'.$link.'"';}
        $this->linkCount++;
        return '<a'.$href.' title="'.$text.'"'.$current.'>'.$text.'</a>';
    }
    
    /**
     * Iterates through the list of menu items
     * @return object 
     */
    protected function parseArray() {
        if(!is_object($this->nav)) {
            $this->nav = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->getNavigationArray()));
        }
        return $this->nav;
    }
    
    /**
     * Checks to see if breadcrumb element is a list style
     * @return boolean Will return true if element is either UL or OL else will return false
     */
    protected function isBreadcrumbList() {
        if(strtolower($this->breadcrumbElement) === 'ul' || strtolower($this->breadcrumbElement) === 'ol') {return true;}
        return false;
    }
}
