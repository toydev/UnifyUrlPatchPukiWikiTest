<?php
use PHPUnit\Framework\TestCase;
use PukiWikiTestUtils\PukiWikiController;

class LinkTest extends TestCase
{
    protected $pkwk_ctonroller;

    protected function setUp()
    {
        global $SELENIUM_SERVER_URL;
        global $SELENIUM_CAPABILITIES;
        global $PKWK_HOME_URL;
        
        $this->pkwk_ctonroller = new PukiWikiController(
            $SELENIUM_SERVER_URL,
            $SELENIUM_CAPABILITIES,
            $PKWK_HOME_URL);
    }

    protected function tearDown() {
        $this->pkwk_ctonroller->close();
    }    

    public function testUrl()
    {
    }
}
