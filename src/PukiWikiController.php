<?php
namespace PukiWikiTestUtils;

use \Net_URL2;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

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

    function getDriver() {
        return $this->driver;
    }

    function getUrl($path) {
        return $this->pkwk_home_url->resolve($path);
    }

    function getPageUrl($page_name) {
        return $this->getUrl('index.php?' . $this->encodeUrl($page_name));
    }

    function readPage($page_name) {
        $this->getAndWait($this->getPageUrl($page_name));
        return preg_match("/^$page_name - /", $this->driver->getTitle());
    }

    function createPage($page_name, $content, $recreate = false) {
        if ($recreate) {
            $this->deletePage($page_name);
        }

        # 編集ページを開く
        $this->getAndWait($this->getUrl(
            'index.php?cmd=edit&page=' . $this->encodeUrl($page_name)));

        # 内容を入れて、ページの更新ボタンを押す
        $this->driver->findElement(WebDriverBy::name("msg"))
            ->clear()->sendKeys($content);
        $this->driver->findElement(WebDriverBy::name("write"))->click();
        $this->wait();
    }

    function deletePage($page_name) {
        $this->createPage($page_name, "");
    }

    function findElement($locator) {
        return $this->driver->findElement($locator);
    }

    function findElements($locator) {
        return $this->driver->findElements($locator);
    }

    function close() {
        if (isset($this->driver))
        {
            $this->driver->close();
        }
        $this->driver = NULL;
    }

    function getAndWait($url) {
        $this->driver->get($url);
        $this->wait();
    }

    function wait() {
        $this->driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::id('body')
            )
        );
    }

    function encodeUrl($page_name)
    {
        return preg_replace_callback('|[^/:]+|', array($this, 'encodeUrlCallback'), $page_name);
    }

    function encodeUrlCallback($matches)
    {
        return rawurlencode($matches[0]);
    }
}
