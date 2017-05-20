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

    public function frontPageMenuProvider() {
        return [
            ["トップ", "index.php?FrontPage"],
            ["編集", "index.php?cmd=edit&page=FrontPage"],
            ["差分", "index.php?cmd=diff&page=FrontPage"],
            ["バックアップ", "index.php?cmd=backup&page=FrontPage"],
            ["添付", "index.php?plugin=attach&pcmd=upload&page=FrontPage"],
            ["リロード", "index.php"],
            ["新規", "index.php?plugin=newpage&refer=FrontPage"],
        ];
    }

    /**
     * @dataProvider frontPageMenuProvider
     */
    public function testFrontPageMenu($targetLinkText, $expectedUrl) {
        $this->pkwk_controller->readPage("FrontPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl($expectedUrl),
            $this->pkwk_controller->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }


    public function testFreezeUrl() {
        $this->pkwk_controller->createPage("FreezeTestPage", "テスト");
        $this->pkwk_controller->readPage("FreezeTestPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?cmd=freeze&page=FreezeTestPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("凍結"))->getAttribute("href"));
    }

    public function testReload() {
        $this->pkwk_controller->createPage("ReloadTestPage", "テスト");
        $this->pkwk_controller->readPage("ReloadTestPage");
        $this->assertEquals(
            $this->pkwk_controller->getUrl('index.php?ReloadTestPage'),
            $this->pkwk_controller->findElement(WebDriverBy::linkText("リロード"))->getAttribute("href"));         
    }
}
