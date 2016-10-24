<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "fal_ttnews".
 *
 * Auto generated 12-01-2016 10:58
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
  'title' => 'FAL News',
  'description' => 'Adds FAL support to tt_news image and media fields',
  'category' => 'fe',
  'version' => '0.0.4',
  'state' => 'beta',
  'uploadfolder' => false,
  'createDirs' => '',
  'clearcacheonload' => false,
  'author' => 'Christian Jürges, Jakob Berlin, jokumer',
  'author_email' => 'christian.juerges@xwave.ch, j.berlin@ewerk.com',
  'author_company' => 'xWave GmbH, EWERK IT GmbH',
  'constraints' => 
  array (
    'depends' => 
    array (
      'tt_news' => '',
      'typo3' => '7.6.0-7.6.99',
    ),
    'conflicts' => 
    array (
    ),
    'suggests' => 
    array (
    ),
  ),
  '_md5_values_when_last_written' => '',
);

