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
    }

    function createNewPage($page_name, $content, $refer_page_name = NULL) {
        # 新規ページを開く
        if (isset($refer_page_name)) {
            $this->getAndWait($this->getUrl(
                'index.php?plugin=newpage&refer=' . $this->encodeUrl($refer_page_name)));
        } else {
            $this->getAndWait($this->getUrl('index.php?plugin=newpage'));
        }

        try {
            # ページ名を入れて、編集ボタンを押す
            $this->driver->findElement(WebDriverBy::name("page"))->sendKeys($page_name);
            $this->driver->findElement(WebDriverBy::tagName("form"))->submit();
            $this->wait();

            # 内容を入れて、ページの更新ボタンを押す
            $this->driver->findElement(WebDriverBy::name("msg"))->sendKeys($content);
            $this->driver->findElement(WebDriverBy::name("write"))->click();
            $this->wait();

            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
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
