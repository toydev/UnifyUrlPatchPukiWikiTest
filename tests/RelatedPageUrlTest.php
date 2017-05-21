<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class RelatedPageUrlTest extends TestCase
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

    public function backlinkUrlProvider() {
        return [
            ["FrontPage", "index.php?plugin=related&page=FrontPage"],
            // このリンクには rawurlencode が使われているため / が %2F になっている
            ["階層1/日本語ページ", "index.php?plugin=related&page=%E9%9A%8E%E5%B1%A41%2F%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider backlinkUrlProvider
     */
    public function testBacklinkUrl($pagename, $expectedUrl) {
        $this->pkwkController->createPage($pagename, "BODY");
        $this->pkwkController->readPage($pagename);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::xpath(
                "//h1[@class='title']/a"
            ))->getAttribute("href"));
    }

    public function relatedPageUrlProvider() {
        return [
            [
                "RelatedTest1",
                "RelatedTest2",
                "index.php?RelatedTest2",
            ],
            [
                "関連/テスト1",
                "関連/テスト2",
                "index.php?%E9%96%A2%E9%80%A3/%E3%83%86%E3%82%B9%E3%83%882",
            ],
        ];
    }

    /**
     * @dataProvider relatedPageUrlProvider
     */
    public function testRelatedPageUrl($pagename1, $pagename2, $expectedUrl) {
        $this->pkwkController->createPage($pagename1, "#related");
        $this->pkwkController->createPage($pagename2, "[[$pagename1]]");
        $this->pkwkController->readPage($pagename1);
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::xpath(
                "//div[@id='body']//a[text()='$pagename2']"
            ))->getAttribute("href"));
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::xpath(
                "//div[@id='related']//a[text()='$pagename2']"
            ))->getAttribute("href"));
    }
}
