<?php
namespace Maxserv\FalTtnews;

/**
 * This file is part of the TYPO3 extension fal_ttnews.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FileUtility
 * See more here: http://wiki.typo3.org/File_Abstraction_Layer
 */
class FileUtility implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * DisplayFileLinks
     *
     * @param    array $markerArray : array filled with markers from the getItemMarkerArray function in tt_news class. see: EXT:tt_news/pi/class.tx_ttnews.php
     * @param    [type]        $conf: ...
     * @return    array        the changed markerArray
     * @author (c) 2015 Christian Jürges <christian.juerges@xwave.ch>
     */
    public function displayFileLinks($markerArray, $conf)
    {
        $pObj = &$conf['parentObj']; // make a reference to the parent-object
        $row = $pObj->local_cObj->data;
        $markerArray['###FILE_LINK###'] = '';
        $markerArray['###TEXT_FILES###'] = '';

        //load TS config for newsFiles from tt_news
        $conf_newsFiles = $pObj->conf['newsFiles.'];
        //Important: unset path
        $conf_newsFiles['path'] = '';

        $local_cObj = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);

        //workspaces
        if (isset($row['_ORIG_uid']) && ($row['_ORIG_uid'] > 0)) {
            // draft workspace
            $uid = $row['_ORIG_uid'];
        } else {
            // live workspace
            $uid = $row['uid'];
        }
        // Check for translation ?

        $fileRepository = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        $fileObjects = $fileRepository->findByRelation('tt_news', 'tx_falttnews_fal_media', $uid);

        if (is_array($fileObjects)) {
            $files_stdWrap = GeneralUtility::trimExplode('|', $pObj->conf['newsFiles_stdWrap.']['wrap']);
            $filelinks = '';
            foreach ($fileObjects as $key => $file) {
                $local_cObj->start($file->getOriginalFile()->getProperties());
                $filelinks .= $local_cObj->filelink($file->getPublicUrl(), $conf_newsFiles);
            }

            if ($filelinks) {
                $markerArray['###FILE_LINK###'] = $filelinks . $files_stdWrap[1];
                $markerArray['###TEXT_FILES###'] = $files_stdWrap[0] . $pObj->local_cObj->stdWrap($pObj->pi_getLL('textFiles'),
                        $pObj->conf['newsFilesHeader_stdWrap.']);
            }
        }
        return $markerArray;
    }

    /**
     * ImageMarkerFunc
     *
     * @param    array $paramArray : $markerArray and $config of the current news item in an array
     * @param    [type]        $conf: ...
     * @return    array        the processed markerArray
     * @author (c) 2015 Christian Jürges <christian.juerges@xwave.ch>
     */
    function imageMarkerFunc($paramArray, $conf)
    {
        $markerArray = $paramArray[0];
        $lConf = $paramArray[1];
        // make a reference to the parent-object
        $pObj = &$conf['parentObj'];
        $row = $pObj->local_cObj->data;

        $mode = (int)$GLOBALS['TSFE']->tmpl->setup['plugin.']['fal_ttnews.']['mode'];

        $imageNum = isset($lConf['imageCount']) ? $lConf['imageCount'] : 1;
        $imageNum = \TYPO3\CMS\Core\Utility\MathUtility::forceIntegerInRange($imageNum, 0, 100);
        $theImgCode = '';

        $imgsCaptions = explode(chr(10), $row['imagecaption']);
        $imgsAltTexts = explode(chr(10), $row['imagealttext']);
        $imgsTitleTexts = explode(chr(10), $row['imagetitletext']);

        // to get correct DAM files, set uid

        // workspaces
        if (isset($row['_ORIG_uid']) && ($row['_ORIG_uid'] > 0)) {
            // draft workspace
            $uid = $row['_ORIG_uid'];
        } else {
            // live workspace
            $uid = $row['uid'];
        }
        // translations - i10n mode
        if ($row['_LOCALIZED_UID']) {
            //i10n mode = exclude   -> do nothing

            //i10n mode = mergeIfNotBlank
            $confArr_ttnews = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']);
            if (!$confArr_ttnews['l10n_mode_imageExclude'] && $row['tx_damnews_dam_images']) {
                $uid = $row['_LOCALIZED_UID'];
            }
        }

        $cc = 0;
        $shift = false;

        // get FAL data
        $fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
        $fileObjects = $fileRepository->findByRelation('tt_news', 'tx_falttnews_fal_images', $uid);
        // remove first img from the image array in single view if the TSvar firstImageIsPreview is set
        if (((count($fileObjects) > 1 && $pObj->config['firstImageIsPreview']) || (count($fileObjects) >= 1 && $pObj->config['forceFirstImageIsPreview'])) && $pObj->theCode == 'SINGLE') {
            array_shift($fileObjects);
            array_shift($imgsCaptions);
            array_shift($imgsAltTexts);
            array_shift($imgsTitleTexts);
            $shift = true;
        }
        // get img array parts for single view pages
        if ($pObj->piVars[$pObj->pObj['singleViewPointerName']]) {
            $spage = $pObj->piVars[$pObj->config['singleViewPointerName']];
            $astart = $imageNum * $spage;
            $fileObjects = array_slice($fileObjects, $astart, $imageNum);
            $imgsCaptions = array_slice($imgsCaptions, $astart, $imageNum);
            $imgsAltTexts = array_slice($imgsAltTexts, $astart, $imageNum);
            $imgsTitleTexts = array_slice($imgsTitleTexts, $astart, $imageNum);
        }

        /**
         * @var TYPO3\CMS\Core\Resource\FileReference $val
         */
        while (list($key, $val) = each($fileObjects)) {
            if ($cc == $imageNum) {
                break;
            }
            if ($val) {
                $reference = $val->getReferenceProperties();
                //set Caption, Alt-text and Title Tag
                switch ($mode) {
                    //take data form tt_news record
                    case 0:
                        $lConf['image.']['altText'] = $imgsAltTexts[$cc];
                        $lConf['image.']['titleText'] = $imgsTitleTexts[$cc];
                        $caption = $imgsCaptions[$cc];
                        break;
                    //if fields are empty in news record, take data from FAL fields
                    case 1:
                        if ($imgsAltTexts[$cc]) {
                            $lConf['image.']['altText'] = $imgsAltTexts[$cc];
                        } else {
                            $lConf['image.']['altText'] = $reference['alternative'];
                        }
                        if ($imgsTitleTexts[$cc]) {
                            $lConf['image.']['titleText'] = $imgsTitleTexts[$cc];
                        } else {
                            $lConf['image.']['titleText'] = $reference['title'];
                        }
                        if ($imgsCaptions[$cc]) {
                            $caption = $imgsCaptions[$cc];
                        } else {
                            $caption = $reference['description'];
                        }
                        break;
                    //take data from FAL fields
                    case 2:
                        $lConf['image.']['altText'] = $reference['alternative'] ? $reference['alternative'] : $val->getOriginalFile()->getProperty('alternative');
                        $lConf['image.']['titleText'] = $reference['title'] ? $reference['title'] : $val->getOriginalFile()->getProperty('title');
                        $caption = $reference['description'] ? $reference['description'] : $val->getOriginalFile()->getProperty('caption');
                        break;
                }
                $lConf['image.']['file'] = $val;
            }
            $pObj->local_cObj->setCurrentVal($val);

            // enables correct use of extension perfectlightbox
            if ($shift) {
                $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $cc + 1;
            } else {
                $GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $cc;
            }

            $theImgCode .= $pObj->local_cObj->wrap($pObj->local_cObj->IMAGE($lConf['image.']) . $pObj->local_cObj->stdWrap($caption,
                    $lConf['caption_stdWrap.']), $lConf['imageWrapIfAny_' . $cc]);
            $cc++;
        }

        // fill marker
        $markerArray['###NEWS_IMAGE###'] = '';
        if ($cc) {
            $markerArray['###NEWS_IMAGE###'] = $pObj->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
        } // noImage_stdWrap
        else {
            $markerArray['###NEWS_IMAGE###'] = $pObj->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'],
                $lConf['image.']['noImage_stdWrap.']);
        }

        return $markerArray;
    }
}

