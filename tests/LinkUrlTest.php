<?php
use Facebook\WebDriver\WebDriverBy;
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class LinkUrlTest extends TestCase
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

    public function interwikiUrlProvider() {
        return [
            ["unknown:FrontPage", "index.php?%5B%5Bunknown:FrontPage%5D%5D"],
            ["unknown:階層1/日本語ページ", "index.php?%5B%5Bunknown:%E9%9A%8E%E5%B1%A41/%E6%97%A5%E6%9C%AC%E8%AA%9E%E3%83%9A%E3%83%BC%E3%82%B8%5D%5D"],
        ];
    }

    /**
     * @dataProvider interwikiUrlProvider
     */
    public function testInterwikiUrl($pagename, $expectedUrl) {
        $this->pkwkController->createPage("FrontPage", "[[$pagename]]");
        $this->pkwkController->readPage("FrontPage");
        $this->assertEquals(
            strval($this->pkwkController->getUrl($expectedUrl)),
            $this->pkwkController->findElement(WebDriverBy::xpath(
                "//div[@id='body']//a[@title='$pagename']"
            ))->getAttribute("href"));
    }
}
