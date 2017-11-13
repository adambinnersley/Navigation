<?php
namespace Nav;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Navigation{
    protected $navigation = array();
    protected $current;
    public $currentURL;
    
    protected $nav;
    protected $navItem;
    protected $sub = false;
    protected $currentLevel = false;

    public $activeClass = 'active';
    
    /**
     * Gets the navigation items and sets the current menu hierarchy
     * @param array $navArray
     * @param string $currentUrl This should be the URL of the current page
     */
    public function __construct($navArray, $currentUrl){
        $this->setNavigationArray($navArray);
        $this->setCurrentURL($currentUrl);
    }
    
    /**
     * Sets the navigation array menu to the array that should be parsed for the menu
     * @param array $array This should be the array of items you wish to make into a menu
     * @return $this
     */
    public function setNavigationArray($array){
        if(is_array($array)){
            $this->navigation = $array;
        }
        return $this;
    }
    
    /**
     * Returns the navigation array
     * @return array Will return a blank array if not set else will return the menu array
     */
    public function getNavigationArray(){
        return $this->navigation;
    }
    
    /**
     * Set the current URL so that the active menu items can be added 
     * @param string $url This should be the URL of the page you want to set as the current page so active items can be retrieved
     * @return $this
     */
    public function setCurrentURL($url){
        $this->currentURL = strtolower($url);
        $this->getCurrent();
        return $this;
    }
    
    /**
     * Gets the URL that is set as the current item
     * @return string This should be the URL set as the current location
     */
    public function getCurrentURL(){
        return $this->currentURL;
    }
    
    /**
     * Sets the class that is assigned to active menu and breadcrumb items
     * @param string $className This should be the class value you want to add to active menu items
     * @return $this
     */
    public function setActiveClass($className){
        $this->activeClass = $className;
        return $this;
    }
    
    /**
     * Returns the class that is given to active menu and breadcrumb items
     * @return string
     */
    public function getActiveClass(){
        return $this->activeClass;
    }
    
    /**
     * Creates the current menu items which are selected from the navigation item hierarchy
     */
    public function getCurrent(){
        $found = false;
        $navItem = $this->parseArray();
        foreach($navItem as $item => $itemURL){
            $this->current[$navItem->getDepth()]['text'] = $item;
            $this->current[$navItem->getDepth()]['link'] = $itemURL;
            if($itemURL === $this->currentURL){
                for($depth = ($navItem->getDepth() + 1); $depth <= 5; $depth++){
                    unset($this->current[$depth]);
                }
                $found = true;
                break;
            }
        }
        
        if($found != true){
            unset($this->current);
            $this->current[0]['text'] = 'Home';
            $this->current[0]['link'] = '/';
        }
    }
    
    /**
     * Creates a breadcrumb navigation from the $this->current array
     * @return string 
     */
    public function createBreadcrumb($class = 'breadcrumb'){
        if($this->current[0]['text'] == 'Home'){
            $breadcrumb = '<li class="'.$this->getActiveClass().'">Home</li>';
        }
        else{
            $breadcrumb = '<li><a href="/" title="Home">Home</a></li>';
            $numlinks = count($this->current);
            for($i = 0; $i < $numlinks; $i++){
                if($i == ($numlinks - 1)){$breadcrumb.= '<li class="'.$this->getActiveClass().'">'.$this->current[$i]['text'].'</li>';}
                else{$breadcrumb.= '<li><a href="'.$this->current[$i]['link'].'" title="'.$this->current[$i]['text'].'">'.$this->current[$i]['text'].'</a></li>';}
            }
        }
        return '<ul class="'.$class.'">'.$breadcrumb.'</ul>';
    }
    
    /**
     * Creates a navigation menu from any multi-dimensional array
     * @param string $class This should be the class name(s) you want to give to the menu item
     * @param string $dropdownClass The class name(s) that you want to give to any sub-menu items (ul items)
     * @param int $levels This should be the number of levels which you wish to display (0 = All)
     * @param int $startLevel Should be the starting level of the navigation
     * @return string Returns the navigation as a string
     */
    public function createNavigation($class = 'nav navbar-nav', $dropdownClass = '', $levels = 0, $startLevel = 0){
        $this->navItem = '<ul class="'.$class.'">';
        $it = $this->parseArray();
        foreach($it as $text => $link){
            if(isset($link) && !is_numeric($text)){
                $this->buildMenu($it, $link, $levels, $startLevel, $dropdownClass);
            }
        }
        
        for($i = $this->currentLevel; $i > 0; $i--){
            $this->closeLevel();
        }
        if($startLevel == 0){$this->closeLevel();}
        return $this->navItem;
    }
    
    /**
     * Builds the menu items 
     * @param object $it This should be the object created withe parsing the array items
     * @param string $text This is the menu item text
     * @param string $link This is the menu item link
     * @param int|false $levels The maximum number of levels to show
     * @param int $start The level that you wish to start at when building the menu
     * @param string $dropdownClass The class that should be assigned to any drop-down elements
     */
    protected function buildMenu($it, $text, $link, $levels, $start, $dropdownClass){
        $current = ($link === $this->current[$it->getDepth()]['link'] ? ' class="'.$this->getActiveClass().'"' : '');
                
        if($start === 0 || $link === $this->current[($start - 1)]['link'] || $this->sub == true){
            if($start !== 0 && $link === $this->current[($start - 1)]['link']){$this->sub = true;}
            if($levels === 0 || $it->getDepth() < $levels){
                if(is_numeric($this->currentLevel)){
                    if($start != 0 && $this->currentLevel == 0){$this->currentLevel = 1;}
                    if($this->currentLevel == $it->getDepth()){
                        $this->nextItem($it, $start, $current);
                    }
                    elseif($this->currentLevel < $it->getDepth()){
                        for($i = $this->currentLevel; $i < $it->getDepth(); $i++){$this->navItem.= '<ul class="'.$dropdownClass.'"><li'.$current.'>';}
                    }
                    else{
                        for($i = $it->getDepth(); $i < $this->currentLevel; $i++){$this->closeLevel();}
                        $this->nextItem($it, $start, $current);
                    }
                }
                else{$this->navItem.= '<li'.$current.'>';}

                $this->currentLevel = $it->getDepth();
                if($start === 0 || $this->sub == true){$this->navItem.= $this->createLinkItem($link, $text, $start);}
            }
        }
    }
    
    /**
     * Checks to see if the next item is on the same level if so adds the close/open tags to the navigation item variable
     * @param object $it This should be the object created withe parsing the array items
     * @param int $start The start level of the menu
     * @param string $current This should be the current string 
     */
    protected function nextItem($it, $start, $current){
        if($this->sub == true && ($start - 1) == $it->getDepth()){$this->sub = false;}else{$this->navItem.= '</li><li'.$current.'>';}
    }
    
    /**
     * Close the current level
     */
    protected function closeLevel(){
        $this->navItem.= '</li></ul>';
    }

    /**
     * Creates a new link/option item for use in a menu depending on drop-down variable settings
     * @param string $link This should be the URL of the link
     * @param string $text This should be the link/option text value
     * @return string
     */
    protected function createLinkItem($link, $text, $start){
        if($link == $this->current[0]['link'] || $link == $this->current[1]['link'] || $link == $this->current[2]['link'] || $link == $this->current[3]['link']){
            $current = (($start >= 1 && $link == $this->current[0]['link'] && isset($this->current[1]['link'])) ? '' : ' class="'.$this->getActiveClass().'"');
        }
        else{$current = '';}
        if($this->current[0]['link'] == $link && $link == '/'){$href = '';}else{$href = ' href="'.$link.'"';}
        return '<a'.$href.' title="'.$text.'"'.$current.'>'.$text.'</a>';
    }
    
    /**
     * Iterates through the list of menu items
     * @return object 
     */
    protected function parseArray(){
        if(!is_object($this->nav)){
            $this->nav = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->getNavigationArray()));
        }
        return $this->nav;
    }
}
