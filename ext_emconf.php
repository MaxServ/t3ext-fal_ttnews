<?php

########################################################################
# Extension Manager/Repository config file for ext "fal_ttnews".
#
# Auto generated 12-04-2012 13:16
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
        'title' => 'FAL News',
        'description' => 'Adds FAL support to tt_news image and media fields',
        'category' => 'fe',
        'shy' => 0,
        'version' => '0.0.1',
        'dependencies' => '',
        'conflicts' => '',
        'priority' => 'bottom',
        'loadOrder' => '',
        'module' => '',
        'state' => 'beta',
        'uploadfolder' => 0,
        'createDirs' => '',
        'modify_tables' => '',
        'clearcacheonload' => 0,
        'lockType' => '',
        'author' => 'Christian Jürges',
        'author_email' => 'christian.juerges@xwave.ch',
        'author_company' => 'xWave GmbH',
        'CGLcompliance' => '',
        'CGLcompliance_note' => '',
        'constraints' => array(
                'depends' => array(
                        'tt_news' => '',
                        'php' => '5.0.0-0.0.0',
                        'typo3' => '6.2.0-6.2.99',
                ),
                'conflicts' => array(),
                'suggests' => array(),
        ),
        '_md5_values_when_last_written' => ''
);

