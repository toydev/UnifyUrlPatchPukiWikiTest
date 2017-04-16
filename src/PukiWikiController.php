<?php
namespace PukiWikiTestUtils;

use \Net_URL2;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class PukiWikiController
{
    function __construct($web_driver_url, $capabilities, $pkwk_home_url)
    {
        $this->driver = RemoteWebDriver::create(
            $web_driver_url,
            $capabilities,
            5000);
        $this->pkwk_home_url = new Net_URL2($pkwk_home_url);
    }

    function __destruct() {
        $this->close();
    }

    function read($page_name) {
        $this->driver->get($this->pkwk_home_url->resolve('index.php?' . $page_name));
        $this->driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::id('body')
            )
        );
    }

    function close() {
        if (isset($this->driver))
        {
            $this->driver->close();
        }
        $this->driver = NULL;
    }
}
