<?php

namespace Nav;

use Nav\Operators\Check;

class Breadcrumb extends Navigation
{
    
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
     * Sets the separator for any breadcrumb items that aren't list elements
     * @param string $separator This should be the element you want as the separator for breadcrumb items;
     * @return $this
     */
    public function setBreadcrumbSeparator($separator)
    {
        if ($separator) {
            $this->separator = $separator;
        }
        return $this;
    }
    
    /**
     * The breadcrumb separator will be returned
     * @return string The separator will be returned
     */
    public function getBreadcrumbSeparator()
    {
        return $this->separator;
    }
    
    /**
     * Sets the main element to give to list style breadcrumb menus
     * @param string $element The main element you want to give to the breadcrumb
     * @return $this
     */
    public function setBreadcrumbElement($element)
    {
        if (Check::checkIfStringSet($element)) {
            $this->breadcrumbElement = trim($element);
        }
        return $this;
    }
    
    /**
     * Returns the breadcrumb element type
     * @return string false Returns the element to surround the breadcrumb items with (default is UL)
     */
    public function getBreadcrumbElement()
    {
        if (!empty($this->breadcrumbElement)) {
            return $this->breadcrumbElement;
        }
        return false;
    }
    
    /**
     * Creates a bread-crumb navigation from the $this->current array
     * @param false|array $additionalLinks Any additional link to add to the end of the breadcrumb item
     * @param boolean $list If your breadcrumb is in list format set to true else set to false
     * @param string $class The class to give to the main breadcrumb element
     * @param string $itemClass The class to give to each of the breadcrumb elements
     * @return string Returns the bread-crumb information as a string with all of the links included
     */
    public function createBreadcrumb($additionalLinks = false, $list = true, $class = 'breadcrumb', $itemClass = 'breadcrumb-item')
    {
        $breadcrumb = (($list === true && $this->getBreadcrumbElement() !== false) ? '<'.$this->getBreadcrumbElement().Check::checkIfEmpty($class, ' class="'.$class.'"').'>' : '');
        if ($this->checkLinkOffsetMatch('/', 0)) {
            $breadcrumb.= $this->displayBreadcrumbItem('<li class="'.$itemClass.' '.$this->getActiveClass().'">', '').'Home'.$this->displayBreadcrumbItem('<li>', '');
        } else {
            $breadcrumb.= $this->displayBreadcrumbItem('<li'.Check::checkIfEmpty($itemClass, ' class="'.$itemClass.'"').'>', '', $list).'<a href="/" title="Home"'.(!$this->isBreadcrumbList() && !empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>Home</a>'.$this->displayBreadcrumbItem('</li>', '', $list);
            $numlinks = (is_array($this->current) ? count($this->current) : 0);
            for ($i = 0; $i < $numlinks; $i++) {
                if ($i == ($numlinks - 1) && $additionalLinks === false) {
                    $breadcrumb.= Check::checkIfSet($list, '<'.$this->displayBreadcrumbItem('li', 'span').Check::checkIfEmpty($itemClass, ' class="'.$itemClass.' '.$this->getActiveClass().'"').'>', $this->getBreadcrumbSeparator()).$this->current[$i]['text'].Check::checkIfSet($list, '</'.$this->displayBreadcrumbItem('li', 'span').'>');
                } else {
                    $breadcrumb.= $this->displayBreadcrumbItem('<li'.Check::checkIfEmpty($itemClass, ' class="'.$itemClass.'"').'>', Check::checkIfSet($list, '', $this->getBreadcrumbSeparator()), $list).'<a href="'.$this->current[$i]['link'].'" title="'.$this->current[$i]['text'].'"'.(!$this->isBreadcrumbList() && !empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>'.$this->current[$i]['text'].'</a>'.$this->displayBreadcrumbItem('</li>', '', $list);
                }
            }
            $breadcrumb.= $this->createAdditionalBreadcrumbItems($additionalLinks, $list, $itemClass);
        }
        return $breadcrumb.(($list === true && $this->getBreadcrumbElement() !== false) ? '</'.$this->getBreadcrumbElement().'>' : '');
    }
    
    /**
     * Creates any additional breadcrumb elements
     * @param false|array $additionalLinks Any additional link to add to the end of the breadcrumb item
     * @param boolean $list If your breadcrumb is in list format set to true else set to false
     * @param string $itemClass The class to give to each of the breadcrumb elements
     * @return string
     */
    protected function createAdditionalBreadcrumbItems($additionalLinks = false, $list = true, $itemClass = 'breadcrumb-item')
    {
        $breadcrumb = '';
        if (is_array($additionalLinks)) {
            $numLinks = count($additionalLinks);
            foreach ($additionalLinks as $i => $item) {
                if ($i == ($numLinks - 1)) {
                    $breadcrumb.= ($list === true ? '<'.$this->displayBreadcrumbItem('li', 'span').Check::checkIfEmpty($itemClass, ' class="'.$itemClass.' '.$this->getActiveClass().'"').'>' : $this->getBreadcrumbSeparator()).$item['text'].($list === true ? '</'.$this->displayBreadcrumbItem('li', 'span').'>' : '');
                } else {
                    $breadcrumb.= $this->displayBreadcrumbItem('<li'.Check::checkIfEmpty($itemClass, ' class="'.$itemClass.'"').'>', ($list !== true ? $this->getBreadcrumbSeparator() : ''), $list).'<a href="'.$item['link'].'" title="'.$item['text'].'"'.(!$this->isBreadcrumbList() && !empty(trim($itemClass)) ? ' class="'.$itemClass.'"' : '').'>'.$item['text'].'</a>'.$this->displayBreadcrumbItem('</li>', '', $list);
                }
            }
        }
        return $breadcrumb;
    }
    
    /**
     * Checks to see if the item is a breadcrumb list and is list or not
     * @param mixed $option1 This should be the options to return if it is a list item
     * @param mixed $option2 This should be the options to return if it is NOT a list item
     * @param NULL|boolean $list If you also want to check that it is a list set to true else set to NULL to not check
     * @return mixed Will return the option based on it it is a list item or not
     */
    protected function displayBreadcrumbItem($option1, $option2, $list = null)
    {
        return ($list === null || $list === true) && $this->isBreadcrumbList() === true ? $option1 : $option2;
    }
    
    /**
     * Checks to see if breadcrumb element is a list style
     * @return boolean Will return true if element is either UL or OL else will return false
     */
    protected function isBreadcrumbList()
    {
        if (strtolower($this->breadcrumbElement) === 'ul' || strtolower($this->breadcrumbElement) === 'ol') {
            return true;
        }
        return false;
    }
}
