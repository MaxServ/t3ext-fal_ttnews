<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Chritian Jürges  <christian.juerges@xwave.ch>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * FAL Support
 *
 * @param    array $paramArray : $markerArray and $config of the current news
 *    item in an array
 * @param    [type]        $conf: ...
 *
 * @return    array        the processed markerArray
 */
function user_imageMarkerFunc($paramArray, $conf) {

	$markerArray = $paramArray[0];
	$lConf = $paramArray[1];
	$pObj = &$conf['parentObj']; // make a reference to the parent-object
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
		if (!$confArr_ttnews['l10n_mode_imageExclude']) {
			if ($row['tx_damnews_dam_images']) {
				$uid = $row['_LOCALIZED_UID'];
			}
		}

	}

	$cc = 0;
	$shift = FALSE;

	// get FAL data
	$fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\FileRepository');
	$fileObjects = $fileRepository->findByRelation('tt_news', 'tx_falttnews_fal_images', $uid);
	// remove first img from the image array in single view if the TSvar firstImageIsPreview is set
	if (((count($fileObjects) > 1 && $pObj->config['firstImageIsPreview']) || (count($fileObjects) >= 1 && $pObj->config['forceFirstImageIsPreview'])) && $pObj->theCode == 'SINGLE') {
		array_shift($fileObjects);
		// array_shift($damRows);
		array_shift($imgsCaptions);
		array_shift($imgsAltTexts);
		array_shift($imgsTitleTexts);
		$shift = TRUE;
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
					$lConf['image.']['altText'] = $reference['alternative'];
					$lConf['image.']['titleText'] = $reference['title'];
					$caption = $reference['description'];
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

		$theImgCode .= $pObj->local_cObj->wrap($pObj->local_cObj->IMAGE($lConf['image.']) . $pObj->local_cObj->stdWrap($caption, $lConf['caption_stdWrap.']), $lConf['imageWrapIfAny_' . $cc]);
		$cc++;
	}

	// fill marker
	$markerArray['###NEWS_IMAGE###'] = '';
	if ($cc) {
		$markerArray['###NEWS_IMAGE###'] = $pObj->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
	} // noImage_stdWrap
	else {
		$markerArray['###NEWS_IMAGE###'] = $pObj->local_cObj->stdWrap($markerArray['###NEWS_IMAGE###'], $lConf['image.']['noImage_stdWrap.']);
	}


	return $markerArray;
}

