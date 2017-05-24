<?php

/**
 * For the full copyright and license information, refer to the accompanying LICENSE file.
 *
 * @copyright 2017 derksen mediaopt GmbH
 */


/**
 *  setup the module
 *
 * @author derksen mediaopt GmbH
 * @version ${VERSION}, ${REVISION}
 */
class mo_ingenico__events
{
    /**
     * bootstrap statement, needs to be added to functions.pgp
     */
    const MO_INGENICO__BOOSTRAP_STATEMENT = 'require_once __DIR__ . \'/mo/mo_ingenico/bootstrap.php\'; // This line was automatically generated.';

    /**
     * perform installation/activation tasks
     */
    public static function onActivate()
    {
        try {
            self::checkAndAddBootstrapInclusion();
            self::updateDatabase();
        } catch (\Exception $exception) {
            oxRegistry::get('oxUtilsView')->addErrorToDisplay($exception, false);
        }
    }

    /**
     * perform deactivation tasks
     */
    public static function onDeactivate()
    {
        //@todo check what tasks are needed here
    }

    /**
     * check functions.php and add module bootstrap if not exists
     */
    private static function checkAndAddBootstrapInclusion()
    {
        $lines = file_exists(self::getFunctionFileNameWithPath()) ? file(
            self::getFunctionFileNameWithPath(),
            FILE_IGNORE_NEW_LINES
        ) : ['<?php'];
        $bootstrapLoader = self::MO_INGENICO__BOOSTRAP_STATEMENT;

        if (in_array($bootstrapLoader, $lines, true) !== false) {
            return;
        }

        $lineOfClosingTag = array_search('?>', $lines, true);
        if ($lineOfClosingTag !== false) {
            $lines = array_slice($lines, 0, $lineOfClosingTag);
        }
        $lines[] = $bootstrapLoader;

        file_put_contents(self::getFunctionFileNameWithPath(), implode(PHP_EOL, $lines));
    }

    /**
     * @return string
     */
    private static function getFunctionFileNameWithPath()
    {
        return __DIR__ . '/../../../functions.php';
    }

    /**
     * update tables and add new tables
     *
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function updateDatabase()
    {
        self::mo_ingenico__installPaymentLogTable();
        self::mo_ingenico__addIngenicoStatusColumnInOrderTable();
        self::mo_ingenico__installOrderReservationTable();
        self::mo_ingenico__addPaymentPageDynamicTitleSnippet();
        self::mo_ingenico__installAliasTable();

        /** @var oxDbMetaDataHandler $oDbHandler */
        $oDbHandler = oxNew('oxDbMetaDataHandler');
        $oDbHandler->updateViews();
    }

    /**
     * Install new tables, update fields and clear cache
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__installAliasTable()
    {
        $config = oxRegistry::getConfig();
        $dbName = $config->getConfigParam('dbName');

        if (!self::tableExists($dbName, 'mo_ingenico__alias')) {
            $charset = ' TYPE=MyISAM';
            if ($config->isUtf()) {
                $charset = ' ENGINE=MyISAM DEFAULT CHARSET=utf8';
            }

            $query = mo_ingenico__sql::getAliasTableCreateSql() . $charset;
            self::mo_ingenico__executeSql($query);
        }
    }

    /**
     * installs and updates payment log table
     *
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__installPaymentLogTable()
    {
        $config = oxRegistry::getConfig();
        $dbName = $config->getConfigParam('dbName');

        if (!self::tableExists($dbName, 'mo_ingenico__payment_logs')) {
            $charset = ' TYPE=MyISAM';
            if ($config->isUtf()) {
                $charset = ' ENGINE=MyISAM DEFAULT CHARSET=utf8';
            }

            $query = mo_ingenico__sql::getLogTableCreateSql() . $charset;
            self::mo_ingenico__executeSql($query);
        } else {
            self::mo_ingenico__update_payment_log_table();
        }
    }

    /**
     * Update Payment Log Table
     *
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__update_payment_log_table()
    {
        $dbName = oxRegistry::getConfig()->getConfigParam('dbName');

        $query = "SELECT DATA_TYPE FROM information_schema.columns WHERE table_schema = ? AND table_name = ? AND column_name = ?";
        $result = oxDb::getDb()->getOne($query, array($dbName, 'mo_ingenico__payment_logs', 'PAYID'));
        if ($result !== 'varchar') {
            self::mo_ingenico__executeSql("ALTER TABLE  `mo_ingenico__payment_logs` CHANGE"
                . "  `PAYID`  `PAYID` VARCHAR( 255 ) NOT NULL DEFAULT  '0';");
        }
    }

    /**
     * add order reservations table
     *
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__installOrderReservationTable()
    {
        $dbName = oxRegistry::getConfig()->getConfigParam('dbName');

        if (!self::tableExists($dbName,'mo_ingenico__order_number_reservations')) {
            $installSql = 'CREATE TABLE `mo_ingenico__order_number_reservations` (
        `OXID` CHAR( 32 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ,
        UNIQUE (`OXID`)
        ) ENGINE = MYISAM ;';
            self::mo_ingenico__executeSql($installSql);
        }
    }

    /**
     * add ingenico status field
     *
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__addIngenicoStatusColumnInOrderTable()
    {
        $result = oxDb::getDb()->execute("SHOW COLUMNS FROM `oxorder` LIKE '%mo_ingenico__status%'");
        if ($result->RecordCount() === 0) {
            $sql = 'ALTER TABLE `oxorder` ADD `mo_ingenico__status` TINYINT(3) NULL';
            self::mo_ingenico__executeSql($sql);
        }
    }

    /**
     * execute sql statement
     *
     * @param $sql
     * @throws \oxConnectionException
     * @throws \Exception
     */
    private static function mo_ingenico__executeSql($sql)
    {
        $result = oxDb::getDb()->execute($sql);
        if ($result === false) {
            throw new \Exception('DB Statement could not be executed: ' . $sql);
        }
    }

    /**
     * check if table already exists
     *
     * @param string $dbName
     * @param string $tableName
     * @return bool
     * @throws \oxConnectionException
     */
    private static function tableExists($dbName, $tableName)
    {
        $sQuery = "SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ?";
        $result = oxDb::getDb()->execute($sQuery, array($dbName, $tableName));
        return $result->RecordCount() !== 0;
    }

    /**
     * check if content snippet exists
     *
     * @param string $id
     * @return mixed bool|oxcontent
     */
    private static function contentSnippetExists($id)
    {
        $oxContent = oxNew('oxcontent');
        return $oxContent->load($id);
    }

    /**
     * create csm snippet for dynamic payment tpl title
     */
    private static function mo_ingenico__addPaymentPageDynamicTitleSnippet()
    {
        $oxContent = oxNew('oxcontent');
        $params['oxcontents__oxsnippet'] = 1;
        $params['oxcontents__oxtype'] = 0;
        $params['oxcontents__oxactive'] = 1;
        $params['oxcontents__oxtitle'] = 'Titel Zahlungsseite für statisches Template';
        $params['oxcontents__oxcontent'] = '';
        $params['oxcontents__oxloadid'] = 'mo_ingenico_tplTitle';
        $params['oxcontents__oxid'] = 'mo_ingenico_tplTitle';
        $oxContent->assign($params);
        $oxContent->setLanguage(0);
        $oxContent->save();

        $oxContent->assign(array(
            'oxcontents__oxcontent' => '',
            'oxcontents__oxtitle' => 'Title payment page for static template',
        ));

        // apply new language
        $oxContent->setLanguage(1);
        $oxContent->save();
    }

}
