<?php

$GLOBALS['T3_VAR']['ext']['fal_ttnews']['setup'] = unserialize($_EXTCONF);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY, 'setup', '

plugin.tt_news.imageMarkerFunc = Maxserv\FalTtnews\FileUtility->imageMarkerFunc

plugin.tt_news.itemMarkerArrayFunc = Maxserv\FalTtnews\FileUtility->displayFileLinks
	', 43);
