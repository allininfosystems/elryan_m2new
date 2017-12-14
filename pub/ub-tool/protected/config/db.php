<?php
return array(
    'components'=>array(
        //database of Magento1
        'db1' => array(
            'connectionString' => 'mysql:host=localhost;port=3306;dbname=elryan35_mag_test',
            'emulatePrepare' => true,
            'username' => 'elryan35_test',
            'password' => 'm2$_*+g$VPA(',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'class' => 'CDbConnection'
        ),
        //database of Magento 2 (we use this database for this tool too)
        'db' => array(
            'connectionString' => 'mysql:host=localhost;port=3306;dbname=elryan35_m2new',
            'emulatePrepare' => true,
            'username' => 'elryan35_m2new',
            'password' => 'icPRo#w6PC{u',
            'charset' => 'utf8',
            'tablePrefix' => '',
            'class' => 'CDbConnection'
        ),
    )
);
