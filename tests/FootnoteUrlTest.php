<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class FootnoteUrlTest extends TestCase
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

    public function footnoteUrlProvider() {
        return [
            ["FrontPage", "index.php?FrontPage"],
            ["階層1/日本語ページ", "index.php?%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8"],
        ];
    }

    /**
     * @dataProvider footnoteUrlProvider
     */
    public function testFootnoteUrl($pageName, $expectedUrl) {
        global $PKWK_FOOTNOTE_TEST_ENABLED;

        if ($PKWK_FOOTNOTE_TEST_ENABLED) {
            $this->pkwkController->createPage($pageName, "BODY((FOOTNOTE))");
            $this->pkwkController->readPage($pageName);
            $this->assertEquals(
                strval($this->pkwkController->getUrl($expectedUrl)) . "#notefoot_1",
                $this->pkwkController->findElement(WebDriverBy::id("notetext_1"))->getAttribute("href"));
            $this->assertEquals(
                strval($this->pkwkController->getUrl($expectedUrl)) . "#notetext_1",
                $this->pkwkController->findElement(WebDriverBy::id("notefoot_1"))->getAttribute("href"));
        }
    }
}
