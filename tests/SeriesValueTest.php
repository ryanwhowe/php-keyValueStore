<?php
/**
 * This file contains
 *
 * @author Ryan Howe
 * @since  2018-10-12
 */

namespace Test;

use ryanwhowe\KeyValueStore\Store\SeriesValue;

class SeriesValueTest extends DataTransaction {

    /**
     * @test
     * @throws \Exception
     */
    public function set()
    {
        $testGrouping = 'SeriesValueSet';
        $key = 'key1';
        $value = '';
        $seriesValue = SeriesValue::create($testGrouping, self::$connection);
        $testSet = array(
            'value1',
            'value2',
            'value3',
            'value4',
            'value5',
            'anotherValue'
        );
        foreach ($testSet as $item) {
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
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function getSet()
    {
        $testGrouping = 'SeriesValueGetSet';
        $key = 'key1';
        $expected = array();
        $seriesValue = SeriesValue::create($testGrouping, self::$connection);
        $testSet = array(
            'value1',
            'value2',
            'value3',
            'value4',
            'value5'
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
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function GetAllKeys()
    {
        $seriesValue = SeriesValue::create('SeriesValueGetAllKeys', self::$connection);
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
     * @throws \Exception
     */
    public function create()
    {
        $testGroupName = 'SeriesValueCreate';
        $seriesValue = SeriesValue::create($testGroupName, self::$connection);
        $resultGroupName = $seriesValue->getGrouping();
        $this->assertEquals($testGroupName, $resultGroupName);
        $this->assertInstanceOf('ryanwhowe\KeyValueStore\Store\SeriesValue', $seriesValue);
    }

    /**
     * @test
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

        $seriesValue = SeriesValue::create($testGroup, self::$connection);
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
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function get()
    {
        $testGrouping = 'SeriesValueGet';
        $key = 'key1';
        $value = '';
        $seriesValue = SeriesValue::create($testGrouping, self::$connection);
        $testSet = array(
            'value1',
            'value2',
            'value3',
            'value4',
            'value5',
            'otherValue'
        );
        foreach ($testSet as $item) {
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
     * @throws \Exception
     */
    public function getGrouping()
    {
        $testGroupName = 'SeriesValueGetGrouping';
        $seriesValue = SeriesValue::create($testGroupName, self::$connection);
        $resultGroupName = $seriesValue->getGrouping();
        $this->assertEquals($testGroupName, $resultGroupName);
    }

    /**
     * @test
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function delete()
    {
        $testGroup = 'SeriesValueDelete';
        $key = 'KeyValue';
        $values = array('value1', 'value2', 'value3', 'value3', 'value2', 'value2', 'value1');
        $seriesValue = SeriesValue::create($testGroup, self::$connection);
        foreach ($values as $value) {
            $seriesValue->set($key, $value);
        }
        $seriesValue->delete($key);
        $result = $seriesValue->get($key);
        $this->assertFalse($result);
    }
}
