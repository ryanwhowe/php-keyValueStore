<?php
/**
 * This file contains the definition for the DataTransaction class
 *
 * @author Ryan Howe
 * @since  2018-10-12
 */

namespace Test\KeyValue;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use RyanWHowe\KeyValueStore\Manager;

abstract class DataTransaction extends TestCase {
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected static $connection;

    /**
     * @var array The local SQLite memory database connection configuration array
     */
    protected static $databaseConfig = array(
        'dbname' => ':memory:',
        'host'   => 'localhost',
        'driver' => 'pdo_sqlite',
    );

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$connection = DriverManager::getConnection(self::$databaseConfig, new Configuration());
    }

    /**
     * This test provider is for ensuring consisten test
     *
     * @return array
     */
    public function groupingTestProvider()
    {
        return array(
            /* input Name, expected Name, should match expected */
            array('GroupName', 'GroupName', true),
            array('GroupName1', 'GroupName1', true),
            array('Group Name', 'Group_Name', true),
            array('G r o u p N a m e ', 'G_r_o_u_p_N_a_m_e', true),
            array(' GroupName', 'GroupName', true),
            array(' GroupName ', 'GroupName', true),
            array('GroupName 12', 'GroupName_12', true),
            array(' G r o u p N a m e 1 2 ', 'G_r_o_u_p_N_a_m_e_1_2', true),
            array('GroupName', 'GroupName', true),

            array(' GroupName', ' GroupName', false),
            array('GroupName1 ', 'GroupName1 ', false),
            array('Group Name', 'Group Name', false),
            array('G r o u p N a m e ', 'G r o u p N a m e ', false),
            array(' GroupName', ' GroupName', false),
            array(' GroupName ', ' GroupName ', false),
            array('GroupName 12', 'GroupName 12', false),
            array(' G r o u p N a m e 1 2 ', ' G r o u p N a m e 1 2 ', false),
            array('G r o u p N a m e ', 'G r o u p N a m e ', false),
        );
    }

    /**
     * This data set is intended to be ingested through the various value setters and getters, therefor the expected
     * output is not provided in this data, the expected output needs to be generated for the different testing
     * conditions since setting through a Single vs Series vs DistinctSeries will have different outcomes
     *
     * @return array
     */
    public function setGetDataProvider()
    {
        return array(

            array(
                'key'    => 'Key1',
                'values' => array('Value1')
            ),

            array(
                'key'    => 'Key2',
                'values' => array('Value1', 'Value2', 'Value3', 'Value2', 'Value1')
            ),

            array(
                'key'    => 'Key3',
                'values' => array('Value1', 'Value2', 'Value3', 'Value4', 'Value5')
            ),

            array(
                'key'    => 'Key4',
                'values' => array('Value3', 'Value3', 'Value3', 'Value1', 'Value3')
            ),

        );
    }

    /**
     * This data set is intended to be ingested through the various setters and getters, therefor the expected
     * output is not provided in this data, the expected output needs to be generated for the different testing
     * conditions since setting through a Single vs Series vs DistinctSeries will have different outcomes.  This
     * extends the setGetDataProvider in that multiple key value pares are tested as input and for the output.
     *
     * @return array
     */
    public function multiKeyDataProvider()
    {
        return array(
            /* Test 1 */
            array('test' => array(
                array(
                    'key'    => 'Key1',
                    'values' => array('Value1')
                ),

                array(
                    'key'    => 'Key2',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value2', 'Value1')
                ),

                array(
                    'key'    => 'Key3',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value4', 'Value5')
                ),

                array(
                    'key'    => 'Key4',
                    'values' => array('Value4', 'Value4', 'Value4', 'Value4', 'Value4')
                ),
            )
            ),
            /* Test 2 */
            array('test' => array(
                array(
                    'key'    => 'Key1',
                    'values' => array('Value1')
                ),

                array(
                    'key'    => 'Key2',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value2', 'Value1')
                ),

                array(
                    'key'    => 'Key3',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value4', 'Value5')
                ),

                array(
                    'key'    => 'Key4',
                    'values' => array('Value4', 'Value4', 'Value4', 'Value4', 'Value4')
                ),
            )
            )
        );
    }

    /**
     * @return array
     */
    public function nonUniqueKeyDataProvider()
    {
        return array(
            /* Test 1 */
            array('test' => array(
                array(
                    'key'    => 'Key1',
                    'values' => array('Value1')
                ),

                array(
                    'key'    => 'KEy1',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value2', 'Value1')
                ),

                array(
                    'key'    => 'KeY1',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value4', 'Value5')
                ),

                array(
                    'key'    => 'KEY1',
                    'values' => array('Value4', 'Value4', 'Value4', 'Value4', 'Value4')
                ),
            )
            ),
            /* Test 2 */
            array('test' => array(
                array(
                    'key'    => 'KeyValue',
                    'values' => array('Value1')
                ),

                array(
                    'key'    => 'Keyvalue',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value2', 'Value1')
                ),

                array(
                    'key'    => 'keyvalue',
                    'values' => array('Value1', 'Value2', 'Value3', 'Value4', 'Value5')
                ),

                array(
                    'key'    => 'KEYVALUE',
                    'values' => array('Value4', 'Value4', 'Value4', 'Value4', 'Value4')
                ),
            )
            )
        );
    }

    /**
     * This is the setUp method, this will create the testing database connected to a local SQLite instanced in
     * memory, there should be no residual tables or test data to clean up from this class.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function setUp()
    {
        Manager::create(self::$connection)->createTable();
    }

    /**
     * This is the teardown method for the testing class.  All cleanup operations should be performed here.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function tearDown()
    {
        Manager::create(self::$connection)->dropTable();
    }
}