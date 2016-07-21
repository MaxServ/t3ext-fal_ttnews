<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$tc = array(
    'tx_falttnews_fal_images' => array(
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('tx_falttnews_fal_images')
    ),
);

//use tt_news l10n_mode_imageExclude settings
$confArr_ttnews = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']);
$tc['tx_falttnews_fal_images']['l10n_mode'] = ($confArr_ttnews['l10n_mode_imageExclude'] ? 'exclude' : 'mergeIfNotBlank');

$tc['tx_falttnews_fal_images']['exclude'] = 1;
$tc['tx_falttnews_fal_images']['label'] = 'LLL:EXT:fal_ttnews/locallang_db.xml:tt_news.tx_falttnews_fal_images';

$tempSetup = $GLOBALS['T3_VAR']['ext']['fal_ttnews']['setup'];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_news', $tc, 1);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_description;;;;1-1-1',
    '', 'after:imagecaption');

if ($tempSetup['media_add_ref']) {
    if ($tempSetup['media_add_orig_field']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_images', '0',
            'after:image');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_images', '1',
            'after:image');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_images', '2',
            'after:image');
    } else {
        $GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = str_replace('image;', ' tx_falttnews_fal_images;',
            $GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
        $GLOBALS['TCA']['tt_news']['types']['1']['showitem'] = str_replace('image;', ' tx_falttnews_fal_images;',
            $GLOBALS['TCA']['tt_news']['types']['1']['showitem']);
        $GLOBALS['TCA']['tt_news']['types']['2']['showitem'] = str_replace('image;', ' tx_falttnews_fal_images;',
            $GLOBALS['TCA']['tt_news']['types']['2']['showitem']);
    }
}

$tc_el = array(
    'tx_falttnews_fal_media' => array(
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('tx_falttnews_fal_media')
    )
);

$tc_el['tx_falttnews_fal_media']['l10n_mode'] = 'mergeIfNotBlank';
$tc_el['tx_falttnews_fal_media']['exclude'] = 1;
$tc_el['tx_falttnews_fal_media']['label'] = 'LLL:EXT:fal_ttnews/locallang_db.xml:tt_news.tx_falttnews_fal_media';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns("tt_news", $tc_el, 1);

if ($tempSetup['media_add_ref']) {
    if ($tempSetup['media_add_orig_field']) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_media', '0',
            'after:news_files');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_media', '1',
            'after:news_files');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_news', 'tx_falttnews_fal_media', '2',
            'after:news_files');
    } else {
        $GLOBALS['TCA']['tt_news']['types']['0']['showitem'] = str_replace('news_files;', ' tx_falttnews_fal_media;',
            $GLOBALS['TCA']['tt_news']['types']['0']['showitem']);
        $GLOBALS['TCA']['tt_news']['types']['1']['showitem'] = str_replace('news_files;', ' tx_falttnews_fal_media;',
            $GLOBALS['TCA']['tt_news']['types']['1']['showitem']);
        $GLOBALS['TCA']['tt_news']['types']['2']['showitem'] = str_replace('news_files;', ' tx_falttnews_fal_media;',
            $GLOBALS['TCA']['tt_news']['types']['2']['showitem']);
    }
}
