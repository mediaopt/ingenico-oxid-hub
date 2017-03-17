<?php
/**
 * For the full copyright and license information, refer to the accompanying LICENSE file.
 *
 * @copyright 2017 derksen mediaopt GmbH
 */

/**
 * provide install sql statements
 *
 * @author derksen mediaopt GmbH
 * @version ${VERSION}, ${REVISION}
 */
class mo_ogone__sql
{
    /**
     * get sql statement to create log table
     *
     * @return string
     */
    public static function getLogTableCreateSql()
    {
        return "CREATE TABLE `mo_ogone__payment_logs` (
            `OXID` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
            `id` int NOT NULL auto_increment,
        
            `orderID` varchar(30) NOT NULL default '',
            `transID` varchar(64) NOT NULL default '',
            `amount` double NOT NULL default '0',
            `currency` varchar(3) NOT NULL default '',
            `language` varchar(5) NOT NULL default '',
            `PM` varchar(25) NOT NULL default '',
            `BRAND` varchar(25) NOT NULL default '',
            `CARDNO` varchar(21) NOT NULL default '',
            `Alias` varchar(50) NOT NULL default '',
            `CN` varchar(35) NOT NULL default '',
            `ED` varchar(7) NOT NULL default '',
            `ACCEPTANCE` varchar(15) NOT NULL default '',
            `STATUS` int(2) NOT NULL default '0',
            `TRXDATE` varchar(32) NOT NULL default '',
            `NCERROR` int(8) NOT NULL default '0',
            `NCERRORPLUS` varchar(255) NOT NULL default '',
            `NCSTATUS` varchar(4) NOT NULL default '',
            `CVCCHECK` varchar(2) NOT NULL default '',
            `AAVCHECK` varchar(2) NOT NULL default '',
            `ECI` int(1) NOT NULL default '0',
            `VC` varchar(3) NOT NULL default '',
            `SCORING` varchar(4) NOT NULL default '',
            `SCO_CATEGORY` varchar(1) NOT NULL default '',
            `PAYID` varchar(255) NOT NULL default '0',
            `PAYIDSUB` int(3) NOT NULL default '0',
            `SHASIGN` varchar(40) NOT NULL default '',
            `SESSION_ID` varchar(255) NOT NULL default '',
            `IP` varchar(32) NOT NULL default '',
            `IPCTY` varchar(2) NOT NULL default '',
            `CCCTY` varchar(2) NOT NULL default '',
        
            `BILLFNAME` VARCHAR(255) NOT NULL default '',
            `BILLLNAME` VARCHAR(255) NOT NULL default '',
            `date` timestamp NOT NULL,
            
            PRIMARY KEY (id)
          )";
    }

    /**
     * get create statement for alias table
     *
     * @return string
     */
    public static function getAliasTableCreateSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `mo_paysolution__card` (
            `OXID` CHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ,
            `MASKED_NO` VARCHAR( 16 ) NOT NULL ,
            `HOLDER` VARCHAR( 255 ) NOT NULL ,
            `EXP_DATE` DATE NOT NULL ,
            `PSEUDO_NO` VARCHAR( 32 ) NOT NULL ,
            `CREATED_AT` DATETIME NOT NULL ,
            `IS_DEFAULT` TINYINT( 1 ) NOT NULL ,
            `OXUSERID` CHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ,
            PRIMARY KEY ( `OXID` ) ,
            INDEX ( `OXUSERID` )
            ) ENGINE = MYISAM ;';
    }

}
