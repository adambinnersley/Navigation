<?php
namespace Nav;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class Navigation{
    public $navigation;
    public $current;
    public $currentURL;
    
    /**
     * Gets the navigation items and sets the current menu hierarchy
     * @param string $url This should be the URL of the current page
     * @param array $product
     * @param array $areas
     * @return void
     */
    public function __construct($navArray, $currentUrl){
        $this->setCurrentURL($currentUrl);
        $this->getCurrent();
    }
    
    public function setNavigationArray($array){
        if(is_array($array)){
            $this->navigation = $array;
        }
        return $this;
    }
    
    public function getNvaigationArray(){
        return $this->navigation;
    }
    
    /**
     * 
     * @param string $url
     */
    public function setCurrentURL($url){
        $this->currentURL = strtolower($url);
        $this->getCurrent();
        return $this;
    }
    
    public function getCurrentURL(){
        return $this->currentURL;
    }
    
    /**
     * Creates the current menu items which are selected from the navigation item hierarchy
     * @return void
     */
    public function getCurrent(){
        $found = false;
        $navItem = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->navigation));
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
     * Creates a new link/option item for use in a menu depending on drop-down variable settings
     * @param string $link This should be the URL of the link
     * @param string $text This should be the link/option text value
     * @return string
     */
    protected function createLinkItem($link, $text, $start){
        if($link == $this->current[0]['link'] || $link == $this->current[1]['link'] || $link == $this->current[2]['link'] || $link == $this->current[3]['link']){
            if($start >= 1 && $link == $this->current[0]['link'] && isset($this->current[1]['link'])){
                $current = '';
            }
            else{
                $current = ' class="active"';
            }
        }
        else{$current = '';}
        if($this->current[0]['link'] == $link && $link == '/'){$href = '';}else{$href = ' href="'.$link.'"';}
        return '<a'.$href.' title="'.$text.'"'.$current.'>'.$text.'</a>';
    }
    
    /**
     * Creates a navigation menu from any multi-dimensional array
     * @param int $levels This should be the number of levels which you wish to display (0 = All)
     * @param int $start Should be the starting level of the navigation
     * @return string Returns the navigation as a string
     */
    public function createNavigation($levels = 0, $start = 0, $class = 'nav navbar-nav', $dropdownClass = ''){
        $currentlevel = false;
        $sub = false;
        $numlink = 0;
        
        $nav = '<ul class="'.$class.'">';
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->navigation));
        foreach($it as $text => $link){
            if(isset($link) && !is_numeric($text)){
                if($link === $this->current[$it->getDepth()]['link']){$current = ' class="active"';}else{$current = '';}
                
                if($start === 0 || $link === $this->current[($start - 1)]['link'] || $sub == true){
                    if($start !== 0 && $link === $this->current[($start - 1)]['link']){$sub = true;}
                    if($levels === 0 || $it->getDepth() < $levels){
                        if(is_numeric($currentlevel)){
                            if($start != 0 && $currentlevel == 0){$currentlevel = 1;}
                            if($currentlevel == $it->getDepth()){
                                if($sub == true && ($start - 1) == $it->getDepth()){$sub = false;}else{$nav.= '</li><li'.$current.'>';}
                            }
                            elseif($currentlevel < $it->getDepth()){
                                for($i = $currentlevel; $i < $it->getDepth(); $i++){$nav.= '<ul class="'.$dropdownClass.'"><li'.$current.'>';}
                            }
                            else{
                                for($i = $it->getDepth(); $i < $currentlevel; $i++){$nav.= '</li></ul>';}
                                if(($sub == true && ($start - 1) == $it->getDepth())){$sub = false;}else{$nav.= '</li><li'.$current.'>';}
                            }
                        }
                        else{$nav.= '<li'.$current.'>';}

                        $currentlevel = $it->getDepth();
                        if($start === 0 || $sub == true){$nav.= $this->createLinkItem($link, $text, $start); $numlink++;}
                    }
                }
            }
        }
        
        for($i = $currentlevel; $i > 0; $i--){
            $nav.= '</li></ul>';
        }
        if($start == 0){$nav.= '</li></ul>';}
        
        if($numlink > 1){
            return $nav;
        }
        return false;
    }
    
    /**
     * Creates a breadcrumb navigation from the $this->current array
     * @return string 
     */
    public function createBreadcrumb(){
        if($this->current[0]['text'] == 'Home'){
            $breadcrumb = '<li class="active">Home</li>';
        }
        else{
            $breadcrumb = '<li><a href="/" title="Home">Home</a></li>';
            $numlinks = count($this->current);
            for($i = 0; $i < $numlinks; $i++){
                if($i == ($numlinks - 1)){$breadcrumb.= '<li class="active">'.$this->current[$i]['text'].'</li>';}
                else{$breadcrumb.= '<li><a href="'.$this->current[$i]['link'].'" title="'.$this->current[$i]['text'].'">'.$this->current[$i]['text'].'</a></li>';}
            }
        }
        return $breadcrumb;
    }
}
