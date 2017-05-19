<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class MenuUrlTest extends TestCase
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

    public function testTopUrl()
    {
        $this->pkwk_controller->readPage("FrontPage");
        $element = $this->pkwk_controller->getDriver()->findElement(WebDriverBy::linkText("トップ"));
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?FrontPage'),
            $element->getAttribute("href"));
    }

    public function testEditUrl()
    {
        $this->pkwk_controller->readPage("FrontPage");
        $element = $this->pkwk_controller->getDriver()->findElement(WebDriverBy::linkText("編集"));
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=edit&page=FrontPage'),
            $element->getAttribute("href"));
    }
}
