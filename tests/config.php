<?php
use Facebook\WebDriver\Remote\DesiredCapabilities;

// Selenium Standalone Server URL
$SELENIUM_SERVER_URL = 'http://localhost:4444/wd/hub';

// Target Capabilities
$SELENIUM_CAPABILITIES = DesiredCapabilities::chrome();

// Target PukiWiki Home URL
$PKWK_HOME_URL = "http://192.168.33.10/PukiWiki-1.5.1/";

// Target PukiWiki Admin Password
$PKWK_ADMINPASS = "pass";

// Testuser
$PKWK_TESTUSERNAME = "testuser";
$PKWK_TESTUSERPASS = "pass";
