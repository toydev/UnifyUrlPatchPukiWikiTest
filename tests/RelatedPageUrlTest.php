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
