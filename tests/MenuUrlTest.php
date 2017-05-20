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
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("トップ"))->getAttribute("href"));
    }

    public function testEditUrl()
    {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=edit&page=FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("編集"))->getAttribute("href"));
    }

    public function testFreezeUrl() {
        $this->pkwk_controller->createPage("FreezeTestPage", "テスト");
        $this->pkwk_controller->readPage("FreezeTestPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=freeze&page=FreezeTestPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("凍結"))->getAttribute("href"));
    }
}
