<?php
namespace Nav\Tests;

use PHPUnit\Framework\TestCase;
use Nav\Navigation;

class NavigationTest extends TestCase
{
    
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
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::setNavigationArray
     * @covers Nav\Navigation::setCurrentURL
     */
    public function setUp() : void
    {
        $this->navigation = new Navigation($this->simpleNav, '/');
    }
    
    public function tearDown() : void
    {
        $this->navigation = null;
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setNavigationArray
     * @covers Nav\Navigation::getNavigationArray
     */
    public function testSetNavigationArray()
    {
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationArray(false));
        $this->assertArrayHasKey('Home', $this->navigation->getNavigationArray());
        
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationArray(array('Custom Link' => '/link-10', 'Hello World' => '/hello-world')));
        $customNavArray = $this->navigation->getNavigationArray();
        $this->assertArrayHasKey('Custom Link', $customNavArray);
        $this->assertArrayNotHasKey('Home', $customNavArray);
        $this->assertEquals(2, count($customNavArray));
        
        $this->navigation->setNavigationArray($this->simpleNav);
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::setNavigationArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::getCurrentURL
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::parseArray
     */
    public function testSetCurrentURL()
    {
        // Test its returning the correct string first of all
        $this->assertEquals('/', $this->navigation->getCurrentURL());
        
        // Check when insertaning a varaible that isn't a string
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setCurrentURL(false));
        $this->assertEquals('/', $this->navigation->getCurrentURL());
        
        // Test when inserting a correct value
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setCurrentURL('/sample'));
        $this->assertEquals('/sample', $this->navigation->getCurrentURL());
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setNavigationArray
     * @covers Nav\Navigation::setActiveClass
     * @covers Nav\Navigation::getActiveClass
     * @covers Nav\Operators\Check::checkIfStringSet
     */
    public function testSetActiveClass()
    {
        $this->assertEquals('active', $this->navigation->getActiveClass());
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setActiveClass('current custom_ac'));
        $this->assertStringNotContainsString('active', $this->navigation->getActiveClass());
        $this->assertEquals('current custom_ac', $this->navigation->getActiveClass());
        
        // Try to set an empty value
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setActiveClass(''));
        $this->assertEquals('current custom_ac', $this->navigation->getActiveClass());
        
        // Try setting in as a non-string variable
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setActiveClass(-1));
        $this->assertEquals('current custom_ac', $this->navigation->getActiveClass());
        
        $this->navigation->setActiveClass('active');
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setNavigationArray
     * @covers Nav\Navigation::setNavigationClass
     * @covers Nav\Navigation::getNavigationClass
     * @covers Nav\Operators\Check::checkIfStringSet
     */
    public function testSetNavigationClass()
    {
        $this->assertEquals('nav navbar-nav', $this->navigation->getNavigationClass());
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationClass('my_navigation_class'));
        $this->assertStringNotContainsString('nav navbar-nav', $this->navigation->getNavigationClass());
        $this->assertEquals('my_navigation_class', $this->navigation->getNavigationClass());
        
        // Try to set an empty value
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationClass(''));
        $this->assertEquals('my_navigation_class', $this->navigation->getNavigationClass());
        
        // Try setting in as a non-string variable
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationClass(-1));
        $this->assertEquals('my_navigation_class', $this->navigation->getNavigationClass());
        
        $this->navigation->setNavigationClass('nav navbar-nav');
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::getNavigationID
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setNavigationID
     * @covers Nav\Navigation::setNavigationArray
     */
    public function testSetNavigationID()
    {
        $this->assertFalse($this->navigation->getNavigationID());
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationID('my_unique_id'));
        $this->assertNotFalse($this->navigation->getNavigationID());
        $this->assertEquals('my_unique_id', $this->navigation->getNavigationID());
        
        // Try to set an empty value
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationID(''));
        $this->assertEquals('', $this->navigation->getNavigationID());
        
        // Try setting in as a non-string variable
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setNavigationID(-1));
        $this->assertEquals('', $this->navigation->getNavigationID());
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::getDropDownClass
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setDropDownClass
     * @covers Nav\Navigation::setNavigationArray
     */
    public function testSetDropDownClass()
    {
        $this->assertFalse($this->navigation->getDropDownClass());
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setDropDownClass('dropdown dd-menu'));
        $this->assertNotFalse($this->navigation->getDropDownClass());
        $this->assertEquals('dropdown dd-menu', $this->navigation->getDropDownClass());
        
        // Try to set an empty value
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setDropDownClass(''));
        $this->assertEquals('', $this->navigation->getDropDownClass());
        
        // Try setting in as a non-string variable
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setDropDownClass(-1));
        $this->assertEquals('', $this->navigation->getDropDownClass());
    }
    
    /**
     * @covers Nav\Navigation::__construct
     * @covers Nav\Navigation::getBreadcrumbSeparator
     * @covers Nav\Navigation::getCurrent
     * @covers Nav\Navigation::getNavigationArray
     * @covers Nav\Navigation::parseArray
     * @covers Nav\Navigation::setBreadcrumbSeparator
     * @covers Nav\Navigation::setCurrentURL
     * @covers Nav\Navigation::setNavigationArray
     */
    public function testSetBreadcrumbSeparator()
    {
        $this->assertEquals(' &gt; ', $this->navigation->getBreadcrumbSeparator());
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setBreadcrumbSeparator(' / '));
        $this->assertEquals(' / ', $this->navigation->getBreadcrumbSeparator());
        
        // Try setting in as a non-string variable
        $this->assertObjectHasAttribute('currentURL', $this->navigation->setBreadcrumbSeparator(false));
        $this->assertEquals(' / ', $this->navigation->getBreadcrumbSeparator());
        
        $this->navigation->setBreadcrumbSeparator(' &gt; ');
    }


    /**
     * @covers Nav\Navigation::
     */
    public function getCurrentItems()
    {
        $this->markTestIncomplete();
    }
    
    /**
     * @covers Nav\Navigation::
     */
    public function testCreateSimpleNavigation()
    {
        $this->markTestIncomplete();
    }
    
    /**
     * @covers Nav\Navigation::
     */
    public function testCreateMultiLevelNavigation()
    {
        $this->markTestIncomplete();
    }
    
    /**
     * @covers Nav\Navigation::
     */
    public function testCreateBreadcrumbNav()
    {
        $this->markTestIncomplete();
    }
}
