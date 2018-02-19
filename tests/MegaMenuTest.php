<?php
namespace Nav\Tests;

use Nav\MegaMenu;

class MegaMenuTest extends NavigationTest{
    
    /**
     * @covers \Nav\Navigation::__construct
     * @covers \Nav\Navigation::setNavigationArray
     * @covers \Nav\Navigation::setCurrentURL
     */
    public function setUp() {
        $this->navigation = new MegaMenu($this->simpleNav, '/');
    }
    
    public function tearDown() {
        $this->navigation = null;
    }
    
    /**
     * @covers \Nav\MegaMenu::setDropDownElement
     * @covers \Nav\MegaMenu::getDropDownElement
     */
    public function testSetDropDownElement(){
        $this->assertEquals('<span class="caret"></span>', $this->navigation->getDropDownElement());
        $this->markTestIncomplete();
    }
    
    /**
     * @covers \Nav\MegaMenu::createNavigation
     * @covers \Nav\Navigation::getNavigationClass
     * @covers \Nav\Navigation::getNavigationID
     * @covers \Nav\Navigation::getActiveClass
     * @covers \Nav\MegaMenu::getLinkItem
     * @covers \Nav\MegaMenu::checkIfCurrentLink
     * @covers \Nav\MegaMenu::getDropDownElement
     * @covers \Nav\MegaMenu::buildSubMenu
     * @covers \Nav\MegaMenu::getMenuItems
     * @covers \Nav\Navigation::parseArray
     * @covers \Nav\MegaMenu::linkArray
     * @covers \Nav\MegaMenu::flattenArray
     * @covers \Nav\MegaMenu::sliceArray
     */
    public function testBuildNavigation(){
        $this->markTestIncomplete();

    }
}
