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

    public function menuUrlProvider() {
        return [
            ["FrontPage", "トップ", "index.php?FrontPage"],
            ["FrontPage", "編集", "index.php?cmd=edit&page=FrontPage"],
            ["FrontPage", "差分", "index.php?cmd=diff&page=FrontPage"],
            ["FrontPage", "バックアップ", "index.php?cmd=backup&page=FrontPage"],
            ["FrontPage", "添付", "index.php?plugin=attach&pcmd=upload&page=FrontPage"],
            ["FrontPage", "リロード", "index.php"],
            ["FrontPage", "新規", "index.php?plugin=newpage&refer=FrontPage"],
            ["FrontPage", "一覧", "index.php?cmd=list"],
            ["FrontPage", "単語検索", "index.php?cmd=search"],
            ["FrontPage", "最終更新", "index.php?RecentChanges"],
            ["FrontPage", "ヘルプ", "index.php?Help"],
            ["階層1/日本語ページ", "編集", "index.php?cmd=edit&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "差分", "index.php?cmd=diff&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "バックアップ", "index.php?cmd=backup&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "添付", "index.php?plugin=attach&pcmd=upload&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "リロード", "index.php?%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "新規", "index.php?plugin=newpage&refer=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider menuUrlProvider
     */
    public function testMenuUrl($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function freezeMenuUrlProvider() {
        return [
            ["FreezeTestPage", "凍結", "index.php?cmd=freeze&page=FreezeTestPage"],
            ["階層1/日本語ページ", "凍結", "index.php?cmd=freeze&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider freezeMenuUrlProvider
     */
    public function testFreezeUrl($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function unfreezeMenuUrlProvider() {
        return [
            ["UnfreezeTestPage", "凍結解除", "index.php?cmd=unfreeze&page=UnfreezeTestPage"],
            ["階層1/日本語ページ", "凍結解除", "index.php?cmd=unfreeze&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider unfreezeMenuUrlProvider
     */
    public function testUnfreezeUrl($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->freezePage($pageName);
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function filelistMenuUrlProvider() {
        return [
            ["FrontPage", "ファイル名一覧", "index.php?cmd=filelist"],
        ];
    }

    /**
     * @dataProvider filelistMenuUrlProvider
     */
    public function testFilelist($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->getAndWait($this->pkwkController->getUrl("index.php?cmd=list"));
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function loginMenuUrlProvider() {
        return [
            ["FrontPage", "ログイン", "index.php?plugin=loginform&pcmd=login&page=FrontPage"],
            ["階層1/日本語ページ", "ログイン", "index.php?plugin=loginform&pcmd=login&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider loginMenuUrlProvider
     */
    public function testLoginUrl($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->logout();
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }

    public function logoutMenuUrlProvider() {
        return [
            ["FrontPage", "ログアウト", "index.php?plugin=loginform&pcmd=logout&page=FrontPage"],
            ["階層1/日本語ページ", "ログアウト", "index.php?plugin=loginform&pcmd=logout&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider logoutMenuUrlProvider
     */
    public function testLogoutUrl($pageName, $targetLinkText, $expectedUrl) {
        global $PKWK_TESTUSERNAME;
        global $PKWK_TESTUSERPASS;

        $this->pkwkController->login($PKWK_TESTUSERNAME, $PKWK_TESTUSERPASS);
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }
}
