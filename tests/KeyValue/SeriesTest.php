<?php
/**
 * This file contains
 *
 * @author Ryan Howe
 * @since  2018-10-12
 */

namespace Test\KeyValue;

use RyanWHowe\KeyValueStore\KeyValue\Series;

class SeriesTest extends DataTransaction {

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::get
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::getSeriesCreateDate
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @dataProvider setGetDataProvider
     * @param string $key
     * @param array $values
     * @throws \Exception
     */
    public function set($key, $values)
    {
        $testGrouping = 'SeriesValueGet';
        $value = '';
        $seriesValue = Series::create($testGrouping, self::$connection);
        foreach ($values as $item) {
            $seriesValue->set($key, $item);
            $value = $item; //the expected output is the last value that was set in the series
        }
        $result = $seriesValue->get($key);
        unset($result['last_update']);
        unset($result['value_created']);
        $this->assertEquals(array('grouping' => $testGrouping, 'key' => $key, 'value' => $value), $result);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::getSet
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function getSet()
    {
        $testGrouping = 'SeriesValueGetSet';
        $key = 'key1';
        $expected = array();
        $seriesValue = Series::create($testGrouping, self::$connection);
        $testSet = array(
            'value1',
            'value2',
            'value3',
            'value2',
            'value1'
        );
        foreach ($testSet as $item) {
            $seriesValue->set($key, $item);
            $expected[] = array('grouping' => $testGrouping, 'key' => $key, 'value' => $item);
        }
        $result = $seriesValue->getSet($key);
        foreach ($result as &$item) {
            unset($item['last_update']);
            unset($item['value_created']);
        }

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getAllKeys
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function GetAllKeys()
    {
        $seriesValue = Series::create('SeriesValueGetAllKeys', self::$connection);
        $seriesValue->set('key1', 'value1');
        $seriesValue->set('key1', 'value2');
        $seriesValue->set('key2', 'value2');
        $seriesValue->set('key2', 'value3');
        $seriesValue->set('key3', 'value3');
        $seriesValue->set('key3', 'value4');
        $seriesValue->set('key4', 'value4');
        $seriesValue->set('key4', 'value5');
        $seriesValue->set('key5', 'value5');
        $seriesValue->set('key5', 'value6');
        $expected = array('key1', 'key2', 'key3', 'key4', 'key5');
        $result = $seriesValue->getAllKeys();
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @throws \Exception
     */
    public function create()
    {
        $testGroupName = 'SeriesValueCreate';
        $seriesValue = Series::create($testGroupName, self::$connection);
        $resultGroupName = $seriesValue->getGrouping();
        $this->assertEquals($testGroupName, $resultGroupName);
        $this->assertInstanceOf('RyanWHowe\KeyValueStore\KeyValue\Series', $seriesValue);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGroupingSet
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function getGroupingSet()
    {
        $testGroup = 'SeriesValueGetGroupingSet';

        $testData = array(
            array('grouping' => $testGroup, 'key' => 'key1', 'value' => 'value1'),
            array('grouping' => $testGroup, 'key' => 'key1', 'value' => 'value2'),
            array('grouping' => $testGroup, 'key' => 'key2', 'value' => 'value2'),
            array('grouping' => $testGroup, 'key' => 'key2', 'value' => 'value3'),
            array('grouping' => $testGroup, 'key' => 'key3', 'value' => 'value3'),
            array('grouping' => $testGroup, 'key' => 'key3', 'value' => 'value4'),
            array('grouping' => $testGroup, 'key' => 'key4', 'value' => 'value4'),
            array('grouping' => $testGroup, 'key' => 'key4', 'value' => 'value5'),
            array('grouping' => $testGroup, 'key' => 'key5', 'value' => 'value5'),
            array('grouping' => $testGroup, 'key' => 'key5', 'value' => 'value5'),
            array('grouping' => $testGroup, 'key' => 'key6', 'value' => 'value6'),
            array('grouping' => $testGroup, 'key' => 'key6', 'value' => 'value7'),
        );

        $seriesValue = Series::create($testGroup, self::$connection);
        $lastSet = array();
        foreach ($testData as $item) {
            $seriesValue->set($item['key'], $item['value']);
            $lastSet[$item['key']] = $item['value'];
        }

        // the last set value for each key is the expected output
        $expected = array();
        foreach ($lastSet as $key => $value) {
            $expected[] = array(
                'grouping' => $testGroup,
                'key'      => $key,
                'value'    => $value
            );
        }

        $result = $seriesValue->getGroupingSet();

        foreach ($result as &$item) {
            // We are removing the last_update, this is a timestamp and is not testable
            unset($item['last_update']);
        }

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::get
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::getSeriesCreateDate
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @dataProvider setGetDataProvider
     * @param string $key
     * @param array $values
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function get($key, $values)
    {
        $testGrouping = 'SeriesValueGet';
        $value = '';
        $seriesValue = Series::create($testGrouping, self::$connection);
        foreach ($values as $item) {
            $seriesValue->set($key, $item);
            $value = $item; //the expected output is the last value that was set in the series
        }
        $result = $seriesValue->get($key);
        unset($result['last_update']);
        unset($result['value_created']);
        $this->assertEquals(array('grouping' => $testGrouping, 'key' => $key, 'value' => $value), $result);
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @dataProvider groupingTestProvider
     * @throws \Exception
     */
    public function GetGrouping($testGroup, $expectedGroup, $expectedResult)
    {
        $singleValue = Series::create($testGroup, self::$connection);
        $resultGroup = $singleValue->getGrouping();
        if ($expectedResult) {
            $this->assertEquals($expectedGroup, $resultGroup);
        } else {
            $this->assertNotEquals($expectedGroup, $resultGroup);
        }
    }

    /**
     * @test
     * @covers \RyanWHowe\KeyValueStore\Manager::__construct
     * @covers \RyanWHowe\KeyValueStore\Manager::create
     * @covers \RyanWHowe\KeyValueStore\Manager::createTable
     * @covers \RyanWHowe\KeyValueStore\Manager::dropTable
     * @covers \RyanWHowe\KeyValueStore\KeyValue::__construct
     * @covers \RyanWHowe\KeyValueStore\KeyValue::create
     * @covers \RyanWHowe\KeyValueStore\KeyValue::delete
     * @covers \RyanWHowe\KeyValueStore\KeyValue::formatGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::getGrouping
     * @covers \RyanWHowe\KeyValueStore\KeyValue::insert
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Multi::get
     * @covers \RyanWHowe\KeyValueStore\KeyValue\Series::set
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function delete()
    {
        $testGroup = 'SeriesValueDelete';
        $key = 'KeyValue';
        $values = array('value1', 'value2', 'value3', 'value3', 'value2', 'value2', 'value1');
        $seriesValue = Series::create($testGroup, self::$connection);
        foreach ($values as $value) {
            $seriesValue->set($key, $value);
        }
        $seriesValue->delete($key);
        $result = $seriesValue->get($key);
        $this->assertFalse($result);
    }
}
