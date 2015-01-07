<?php

$GLOBALS['T3_VAR']['ext']['fal_ttnews']['setup'] = unserialize($_EXTCONF);

t3lib_extMgm::addTypoScript($_EXTKEY, 'setup', '

includeLibs.imageMarkerFunc=EXT:fal_ttnews/imageMarkerFunc.php
plugin.tt_news.imageMarkerFunc = user_imageMarkerFunc

includeLibs.displayFileLinks = EXT:fal_ttnews/displayFileLinks.php
plugin.tt_news.itemMarkerArrayFunc = user_displayFileLinks

	', 43);
