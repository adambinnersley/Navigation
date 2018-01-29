<?php
namespace Nav\Tests;

use PHPUnit\Framework\TestCase;
use Nav\Navigation;

class NavigationTest extends TestCase{
    
    protected $navigation;
    
    protected $simpleNav = array(
        'Home' => '/',
        'Link Text' => '/link-2',
        'Sample' => '/sample',
        'Another Page' => '/yet-another-link',
        'Google' => 'https://www.google.co.uk',
        'Final Link' => '/final-page'
    );
    protected $multilevelNav = array();
        
    public function setUp() {
        $this->navigation = new Navigation($this->simpleNav, '/');
    }
    
    public function tearDown() {
        $this->navigation = null;
    }
    
    public function testSetNavigationArray() {
        $this->markTestIncomplete();
    }
    
    public function testGetNavigationArray() {
        $this->markTestIncomplete();
    }
    
    public function testSetCurrentURL() {
        $this->markTestIncomplete();
    }
    
    public function testGetCurentURL() {
        $this->markTestIncomplete();
    }
    
    public function testSetActiveClass() {
        $this->markTestIncomplete();
    }
    
    public function testGetActiveClass() {
        $this->markTestIncomplete();
    }
    
    public function testSetNavigationClass() {
        $this->markTestIncomplete();
    }
    
    public function testGetNavigationClass() {
        $this->markTestIncomplete();
    }
    
    public function testSetNavigationID() {
        $this->markTestIncomplete();
    }
    
    public function testGetNavigationID() {
        $this->markTestIncomplete();
    }
    
    public function testSetDropDownClass() {
        $this->markTestIncomplete();
    }
    
    public function testGetDropDownClass() {
        $this->markTestIncomplete();
    }
    
    public function testSetBreadcrumbSeparator() {
        $this->markTestIncomplete();
    }
    
    public function testGetBreadcrumbSeparator() {
        $this->markTestIncomplete();
    }
    
    public function getCurrentItems() {
        $this->markTestIncomplete();
    }
    
    public function testCreateSimpleNavigation() {
        $this->markTestIncomplete();
    }
    
    public function testCreateMultiLevelNavigation() {
        $this->markTestIncomplete();
    }
    
    public function testCreateBreadcrumbNav() {
        $this->markTestIncomplete();
    }
}
