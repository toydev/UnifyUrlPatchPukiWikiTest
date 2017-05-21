<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class ToolbarUrlTest extends TestCase
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
            ["FrontPage", "//img[@title='トップ']/parent::node()", "index.php?FrontPage"],
            ["FrontPage", "//img[@title='編集']/parent::node()", "index.php?cmd=edit&page=FrontPage"],
            ["FrontPage", "//img[@title='差分']/parent::node()", "index.php?cmd=diff&page=FrontPage"],
            ["FrontPage", "//img[@title='バックアップ']/parent::node()", "index.php?cmd=backup&page=FrontPage"],
            ["FrontPage", "//img[@title='添付']/parent::node()", "index.php?plugin=attach&pcmd=upload&page=FrontPage"],
            ["FrontPage", "//img[@title='複製']/parent::node()", "index.php?plugin=template&refer=FrontPage"],
            ["FrontPage", "//img[@title='名前変更']/parent::node()", "index.php?plugin=rename&refer=FrontPage"],
            ["FrontPage", "//img[@title='リロード']/parent::node()", "index.php"],
            ["FrontPage", "//img[@title='新規']/parent::node()", "index.php?plugin=newpage&refer=FrontPage"],
            ["FrontPage", "//img[@title='一覧']/parent::node()", "index.php?cmd=list"],
            ["FrontPage", "//img[@title='単語検索']/parent::node()", "index.php?cmd=search"],
            ["FrontPage", "//img[@title='最終更新']/parent::node()", "index.php?RecentChanges"],
            ["FrontPage", "//img[@title='ヘルプ']/parent::node()", "index.php?Help"],
            ["FrontPage", "//img[@title='最終更新のRSS']/parent::node()", "index.php?cmd=rss&ver=1.0"],
            ["階層1/日本語ページ", "//img[@title='編集']/parent::node()", "index.php?cmd=edit&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='差分']/parent::node()", "index.php?cmd=diff&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='バックアップ']/parent::node()", "index.php?cmd=backup&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='添付']/parent::node()", "index.php?plugin=attach&pcmd=upload&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='複製']/parent::node()", "index.php?plugin=template&refer=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='名前変更']/parent::node()", "index.php?plugin=rename&refer=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='リロード']/parent::node()", "index.php?%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
            ["階層1/日本語ページ", "//img[@title='新規']/parent::node()", "index.php?plugin=newpage&refer=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider frontPageMenuProvider
     */
    public function testFrontPageMenu($pageName, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pageName, "BODY");
        $this->pkwkController->readPage($pageName);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::xpath($targetLinkText))->getAttribute("href"));
    }

    public function freezeMenuProvider() {
        return [
            ["FreezeTestPage", "//img[@title='凍結']/parent::node()", "index.php?cmd=freeze&page=FreezeTestPage"],
            ["階層1/日本語ページ", "//img[@title='凍結']/parent::node()", "index.php?cmd=freeze&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
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
            $this->pkwkController->findElement(WebDriverBy::xpath($targetLinkText))->getAttribute("href"));
    }

    public function unfreezeMenuProvider() {
        return [
            ["UnfreezeTestPage", "//img[@title='凍結解除']/parent::node()", "index.php?cmd=unfreeze&page=UnfreezeTestPage"],
            ["階層1/日本語ページ", "//img[@title='凍結解除']/parent::node()", "index.php?cmd=unfreeze&page=%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
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
            $this->pkwkController->findElement(WebDriverBy::xpath($targetLinkText))->getAttribute("href"));
    }
}
