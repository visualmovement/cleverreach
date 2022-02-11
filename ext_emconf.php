<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'CleverReach',
    'description' => 'Finishers and validators for EXT:form and Powermail',
    'category' => 'misc',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Supseven',
    'author_email' => 'office@supseven.at',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.999',
            'php' => '7.0.0-8.1.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
