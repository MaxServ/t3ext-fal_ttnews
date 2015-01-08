<?php

$GLOBALS['T3_VAR']['ext']['fal_ttnews']['setup'] = unserialize($_EXTCONF);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', '

includeLibs.imageMarkerFunc=EXT:fal_ttnews/imageMarkerFunc.php
plugin.tt_news.imageMarkerFunc = user_imageMarkerFunc

includeLibs.displayFileLinks = EXT:fal_ttnews/displayFileLinks.php
plugin.tt_news.itemMarkerArrayFunc = user_displayFileLinks

	', 43);
