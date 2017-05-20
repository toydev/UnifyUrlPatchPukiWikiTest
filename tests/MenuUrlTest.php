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

    public function testDiffUrl() {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=diff&page=FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("差分"))->getAttribute("href"));
    }

    public function testBackup() {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=backup&page=FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("バックアップ"))->getAttribute("href"));
    }

    public function testFileUpload() {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?plugin=attach&pcmd=upload&page=FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("添付"))->getAttribute("href"));         
    }

    public function testReload() {
        $this->pkwk_controller->createPage("ReloadTestPage", "テスト");
        $this->pkwk_controller->readPage("ReloadTestPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?ReloadTestPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("リロード"))->getAttribute("href"));         
    }

    public function testReloadFrontPage() {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("リロード"))->getAttribute("href"));         
    }

    public function testNew() {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?plugin=newpage&refer=FrontPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("新規"))->getAttribute("href"));         
    }
}
