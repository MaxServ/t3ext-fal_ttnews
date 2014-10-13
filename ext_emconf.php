<?php

########################################################################
# Extension Manager/Repository config file for ext "fal_ttnews".
#
# Auto generated 11-10-2012 13:03
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF['fal_ttnews'] = array(
	'title' => 'FAL tt_news Connector',
	'description' => 'Adds FAL support to tt_news image and media fields',
	'category' => 'fe',
	'shy' => 0,
	'version' => '0.0.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => 'bottom',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Michiel Roos',
	'author_email' => 'michiel@maxserv.nl',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
			'php' => '5.3.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'_md5_values_when_last_written' => '',
);
