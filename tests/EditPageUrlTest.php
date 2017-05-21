<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class EditPageUrlTest extends TestCase
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

    public function helpProvider() {
        return [
            ["FrontPage", "テキスト整形のルールを表示する", "index.php?cmd=edit&help=true&page=FrontPage"],
            // このリンクには rawurlencode が使われているため / が %2F になっている
            ["階層1/日本語ページ", "テキスト整形のルールを表示する", "index.php?cmd=edit&help=true&page=%E9%9A%8E%E5%B1%A41%2F%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider helpProvider
     */
    public function testHelp($pagename, $targetLinkText, $expectedUrl) {
        $this->pkwkController->createPage($pagename, "BODY");
        $this->pkwkController->getAndWait($this->pkwkController->getUrl(
            'index.php?cmd=edit&page=') . $this->pkwkController->encodeUrl($pagename));
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::linkText($targetLinkText))->getAttribute("href"));
    }
}
