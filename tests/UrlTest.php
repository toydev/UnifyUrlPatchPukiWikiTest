<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class UrlTest extends TestCase
{
    protected $pkwk_controller;

    protected function setUp()
    {
        global $SELENIUM_SERVER_URL;
        global $SELENIUM_CAPABILITIES;
        global $PKWK_HOME_URL;
        
        $this->pkwk_controller = new PukiWikiController(
            $SELENIUM_SERVER_URL,
            $SELENIUM_CAPABILITIES,
            $PKWK_HOME_URL);
    }

    protected function tearDown() {
        $this->pkwk_controller->close();
    }    

    public function testTopUrlInMenu()
    {
        $this->pkwk_controller->read("FrontPage");
        $element = $this->pkwk_controller->getDriver()->findElement(WebDriverBy::linkText("トップ"));
        $this->assertEquals($this->pkwk_controller->getUrl("FrontPage"), $element->getAttribute("href"));
    }
}
