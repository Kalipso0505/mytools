<?php

namespace BackEndHelperBundle\Helper;

class MysqlQueryHelper
{
    /**
     * @param string $tableName        Name of table the insert is meant for
     * @param array  $data             array containing one or more associative arrays, where the keys are the column
     *                                 names and the values the ones to insert
     * @param bool   $withInsertIgnore adds mysql IGNORE to the insert, if true
     *
     * @return string SQL ready for insert
     */
    public static function generateInsertsFromArray($tableName, array $data, $withInsertIgnore = false)
    {
        $sql = '';

        if (!empty($data[0])) {
            $sKeys = '';
            foreach ($data[0] as $key => $value) {
                $sKeys .= '`' . $key . '`,';
            }

            $insertIgnore = '';
            if ($withInsertIgnore) {
                $insertIgnore = 'IGNORE ';
            }

            // @Todo this inserts should be split into multiple inserts, if there are more then 1000 rows in the array
            $sql = 'INSERT ' . $insertIgnore . 'INTO ' . $tableName . ' (' . rtrim($sKeys, ',') . ') VALUES ';
            foreach ($data as $row) {
                $sValues = '';
                foreach ($row as $value) {
                    if(empty($value)) {
                        $sValues .= "NULL ,";
                    } else {
                        $sValues .= "'" . $value . "',";
                    }
                }
                $sql .= '(' . rtrim($sValues, ',') . '),';
            }
            $sql = rtrim($sql, ',') . ';';
        }

        return $sql;
    }

    public static function generateUpdatesFromArray($tableName, array $data)
    {
        $sql = "";

        if (!empty($data[0])) {
            assert(
                count($data[0]) > 1,
                'the data row needs at least two columns: 1st is where criteria, n+1 are columns to update'
            );

            $sqlTemplate = 'UPDATE ' . $tableName . ' SET ';
            foreach ($data as $row) {
                $where = 'WHERE ' . key($row) . '="' . current($row) . '";';
                $setChain = '';
                while (next($row)) {
                    $setChain .= key($row) . '="' . current($row) . '",';
                }
                $sql .= $sqlTemplate . rtrim($setChain, ',') . ' ' . $where . PHP_EOL;
            }
        }

        return $sql;

    }

    public static function generateDeletesFromArray($tableName, array $data)
    {
        $sql = "";

        if (!empty($data[0])) {
            assert(
                count($data[0]) > 1,
                'the data row needs at least two columns: 1st is where criteria, n+1 are columns to update'
            );

            $sqlTemplate = 'DELETE FROM ' . $tableName . ' WHERE ' . key($data[0]) . ' in ';
            $where = '';
            foreach ($data as $row) {
                $where .= current($row) . ',';
            }
            $sql .= $sqlTemplate . '(' . rtrim($where, ',') . ');' . PHP_EOL;
        }
        
        return $sql;
    }
}
