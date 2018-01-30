<?php

namespace Nav;

class MegaMenu extends Navigation{
    public $megamenu;
    
    /**
     *
     * @var string
     */
    public $dropdownElement = '<span class="caret"></span>';
    
    /**
     * 
     * @param string $element
     * @return $this
     */
    public function setDropDownElement($element){
        if(!empty(trim(strip_tags($element, '<span><i><div>')))){
            $this->dropdownElement = trim(strip_tags($element, '<span><i><div>'));
        }
        return $this;
    }
    
    /**
     * 
     * @return string|false
     */
    public function getDropDownElement(){
        if(!empty($this->dropdownElement)){
            return $this->dropdownElement;
        }
        return false;
    }
    
    public function createNavigation($levels = 0, $start = 0, $span = false, $ddclass = 'dropdown-menu') {
        $this->getMenuItems();
        unset($levels);
        unset($start);
        $nav = '<ul'.($this->getNavigationClass() !== false ? ' class="'.$this->getNavigationClass().'"' : '').($this->getNavigationID() !== false ? ' id="'.$this->getNavigationClass().'"' : '').'>';
        foreach($this->megamenu as $text => $link) {
            $itemlink = $this->getLinkItem($link);
            if($itemlink['link'] == $this->current[0]['link'] || $itemlink['link'] == $this->current[1]['link']) {$current = ' class="'.$this->getActiveClass().'"';}
            else {$current = '';}
            $nav.= '<li'.($itemlink['num'] >= 1 ? ' class="'.(($itemlink['link'] == $this->current[0]['link'] || $itemlink['link'] == $this->current[1]['link']) ? $this->getActiveClass().' ' : '').($itemlink['num'] > 10 ? 'mega-dropdown dropdown' : 'dropdown').'"' : $current).'>'.($span ? '<span>' : '').'<a href="'.$itemlink['link'].'" title="'.$text.'"'.$current.($itemlink['num'] >= 1 ? ' data-toggle="dropdown"' : '').'>'.$text.($itemlink['num'] >= 1 && $this->getDropDownElement() !== false ? $this->getDropDownElement() : '').($span ? '</span>' : '').'</a>';
            if($itemlink['num'] >= 1) {
                $nav.= $this->buildSubMenu($link, $itemlink['num'], $ddclass);
            }
            $nav.= '</li>';
        }
        return $nav.'</ul>';
    }
    
    protected function getMenuItems() {
        $it = $this->parseArray();
        $currentitem = array();
        foreach($it as $text => $link) {
            if($it->getDepth() == 1) {
                if(!is_array($this->megamenu[$currentitem[0]])) {
                    $this->megamenu[$currentitem[0]] = array(0 => $this->megamenu[$currentitem[0]]);
                }
                $this->megamenu[$currentitem[0]][$text] = $link;
                $currentitem[$it->getDepth()] = $text;
            }
            elseif($it->getDepth() >= 2) {
                if(!is_array($this->megamenu[$currentitem[0]][$currentitem[1]])) {
                    $this->megamenu[$currentitem[0]][$currentitem[1]] = array(0 => $this->megamenu[$currentitem[0]][$currentitem[1]]);
                }
                $this->megamenu[$currentitem[0]][$currentitem[1]][$text] = $link;
            }
            else {
                $this->megamenu[$text] = $link;
                $currentitem[$it->getDepth()] = $text;
            }
        }
    }
    
    protected function buildSubMenu($linkarray, $numitems, $ddclass = 'dropdown-menu') {
        unset($linkarray[0]);
        if($numitems > 10) {$class = $ddclass.' mega-dropdown-menu row';}else {$class = $ddclass;}
        $menu = '<ul class="'.$class.'">';
        if(count($linkarray) === count($linkarray, COUNT_RECURSIVE)) {
            $menu.= $this->linkArray($linkarray);
        }
        else {
            $columns = $this->flattenArray($linkarray);
            $numColumns = count($columns);
            foreach($columns as $column) {
                $menu.= $this->buildColumn($column, $numColumns);
            }
        }
        return $menu.'</ul>';
    }
    
    protected function buildColumn($column, $numColumns){
        $menu = '<li class="col-sm-'.(12 / $numColumns).'"><ul>';
        foreach($column as $text => $link) {
            if(($link['link'] == $this->current[1]['link'] || $link['link'] == $this->current[2]['link'] || $link['link'] == $this->current[3]['link']) && $link['level'] != 0) {$current = ' class="'.$this->getActiveClass().'"';}
            else {$current = '';}
            $menu.= '<li'.$current.'><a href="'.$link['link'].'" title="'.$text.'"'.($link['level'] == 0 ? ' class="dropdown-header"' : '').'>'.$text.'</a></li>';
        }
        $menu.= '</ul></li>';
        return $menu;
    }


    private function linkArray($array) {
        if(is_array($array)) {
            foreach($array as $text => $link) {
                if($link == $this->current[1]['link'] || $link == $this->current[2]['link'] || $link == $this->current[3]['link']) {
                    $current = ' class="'.$this->getActiveClass().'"';
                }
                else {$current = '';}
                $menu.= '<li'.$current.'><a href="'.$link.'" title="'.$text.'"'.$current.'>'.$text.'</a></li>';
            }
        }
        return $menu;
    }
    
    private function flattenArray($array, $level = 0) {
        $links = array();
        foreach($array as $text => $link) {
            if(is_array($link)) {$linkitem = $link[0];unset($link[0]);}else {$linkitem = $link;}
            $links[$text]['link'] = $linkitem;
            $links[$text]['level'] = $level;
            if(is_array($link)) {$links = array_merge($links, $this->flattenArray($link, ($level + 1)));}
        }
        if($level == 0) {return $this->sliceArray($links);}
        else {return $links;}
    }
    
    protected function sliceArray($links) {
        if(count($links) <= 20) {$rows = 3;}
        elseif(count($links) <= 34) {$rows = 4;}
        else {$rows = 6;}
        $items = (ceil(count($links) / $rows) + 1);
        for($i = 0; $i < $rows; $i++) {
            $linkarray[] = array_slice($links, ($items * $i), $items);
        }
        return $linkarray;
    }
    
    protected function getLinkItem($link) {
        if(is_array($link)) {
            $item['link'] = $link[0];
            $item['num'] = (count($link, COUNT_RECURSIVE) - 1);
        }
        else {
            $item['link'] = $link;
            $item['num'] = 0;
        }
        return $item;
    }
}