<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class MenuUrlTest extends TestCase
{
    protected $pkwkController;

    protected function setUp()
    {
        global $SELENIUM_SERVER_URL;
        global $SELENIUM_CAPABILITIES;
        global $PKWK_HOME_URL;
        global $PKWK_ADMINPASS;
        
        $this->pkwkController = new PukiWikiController(
            $SELENIUM_SERVER_URL,
            $SELENIUM_CAPABILITIES,
            $PKWK_HOME_URL,
            $PKWK_ADMINPASS);
    }

    protected function tearDown() {
        $this->pkwkController->close();
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
            ["一覧", "index.php?cmd=list"],
            ["単語検索", "index.php?cmd=search"],
            ["最終更新", "index.php?RecentChanges"],
            ["ヘルプ", "index.php?Help"],
        ];
    }

    /**
     * @dataProvider frontPageMenuProvider
     */
    public function testFrontPageMenu($targetLinkText, $expectedUrl) {
        $this->pkwkController->readPage("FrontPage");
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function freezeMenuProvider() {
        return [
            ["FreezeTestPage", "凍結", "index.php?cmd=freeze&page=FreezeTestPage",]
        ];
    }

    /**
     * @dataProvider freezeMenuProvider
     */
    public function testFreeze($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function unfreezeMenuProvider() {
        return [
            ["UnfreezeTestPage", "凍結解除", "index.php?cmd=unfreeze&page=UnfreezeTestPage",]
        ];
    }

    /**
     * @dataProvider unfreezeMenuProvider
     */
    public function testUnfreeze($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->freezePage($pageName);
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }
}
