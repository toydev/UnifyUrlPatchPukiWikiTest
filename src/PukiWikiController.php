<?php
namespace PukiWikiTestUtils;

use \Net_URL2;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class PukiWikiController
{
    function __construct($webDriverUrl, $capabilities, $pkwkHomeUrl, $pkwkAdminpass) {
        $this->driver = RemoteWebDriver::create(
            $webDriverUrl,
            $capabilities,
            5000);
        $this->pkwkHomeUrl = new Net_URL2($pkwkHomeUrl);
        $this->pkwkAdminpass = $pkwkAdminpass;
    }

    function __destruct() {
        $this->close();
    }

    function getDriver() {
        return $this->driver;
    }

    function getUrl($path) {
        return $this->pkwkHomeUrl->resolve($path);
    }

    function getPageUrl($pagename) {
        return $this->getUrl('index.php?' . $this->encodeUrl($pagename));
    }

    # ページを読む。
    function readPage($pagename) {
        $this->getAndWait($this->getPageUrl($pagename));
    }

    # ページを作る。
    function createPage($pagename, $content, $force = true) {
        # 編集ページを開く
        $this->getAndWait($this->getUrl(
            'index.php?cmd=edit&page=' . $this->encodeUrl($pagename)));

        # 凍結解除がある場合は先に解除する
        if ($force) {
            try {
                $unfreeze = $this->findElement(WebDriverBy::linkText("凍結解除"));
                $unfreeze->click();
                $this->wait();

                $this->driver->findElement(WebDriverBy::name("pass"))
                    ->clear()->sendKeys($this->pkwkAdminpass);
                $this->driver->findElement(WebDriverBy::name("ok"))->click();
                $this->wait();
            } catch (NoSuchElementException $e) {
                return false;
            }
        }

        # 内容を入れてページの更新ボタンを押す
        try {
            $this->driver->findElement(WebDriverBy::name("msg"))
                ->clear()->sendKeys($content);
            $this->driver->findElement(WebDriverBy::name("write"))->click();
            $this->wait();
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    function deletePage($pagename) {
        $this->createPage($pagename, "");
    }

    function freezePage($pagename) {
        $this->getAndWait($this->getUrl(
            'index.php?cmd=freeze&page=' . $this->encodeUrl($pagename)));
        try {
            $this->driver->findElement(WebDriverBy::name("pass"))
                ->clear()->sendKeys($this->pkwkAdminpass);
            $this->driver->findElement(WebDriverBy::name("ok"))->click();
            $this->wait();

            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
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

    function encodeUrl($pagename)
    {
        return preg_replace_callback('|[^/:]+|', array($this, 'encodeUrlCallback'), $pagename);
    }

    function encodeUrlCallback($matches)
    {
        return rawurlencode($matches[0]);
    }
}
