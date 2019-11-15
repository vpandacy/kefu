<?php

namespace common\components\helper;


class ModelHelper
{
    /**
     * 根据某个字段 in  查询
     */
    public static function getDicByRelateID($data, $relate_model, $id_columns, $pk_column, $name_columns = [])
    {
        $_ids = [];
        $_names = [];
        foreach ($data as $_row) {
            if( is_array( $id_columns ) ){
                foreach ( $id_columns as $_column ){
                    $_ids[] = $_row[$_column];
                }
            } else{
                $_ids[] = $_row[$id_columns];
            }
        }
        $_ids = array_unique( $_ids );
        $rel_data = $relate_model::findAll([$pk_column => array_unique($_ids)]);
        foreach ($rel_data as $_rel) {
            $map_item = [];
            if ($name_columns && is_array($name_columns)) {
                foreach ($name_columns as $name_column) {
                    $map_item[$name_column] = $_rel->$name_column;
                }
            }
            $_names[$_rel->$pk_column] = $map_item;
        }
        return $_names;
    }

    public static function getPkDict($model_class, $pk_column, $name_column, $condition = [])
    {
        $return = [];
        $db_records = $model_class::find()->where($condition)->all();
        foreach ($db_records as $_tmp) {
            $return[$_tmp->$pk_column] = $_tmp->$name_column;
        }
        return $return;
    }

    /**
     * Author: Vincent
     * @param $model_class
     * @param $pk_column
     * @param $name_columns
     * @param array $condition
     */
    public static function getDictByPK( $model_class, $pk_column, $name_columns, $condition = [] ){
        $return = [];
        if( !is_array( $name_columns ) ){
            $name_columns = [ $name_columns ];
        }
        $db_records = $model_class::find()->where($condition)->all();
        foreach ($db_records as $_tmp) {
            $map_item = [];
            if ($name_columns && is_array($name_columns)) {
                foreach ($name_columns as $name_column) {
                    $map_item[$name_column] = $_tmp->$name_column;
                }
            }
            $return[$_tmp->$pk_column] = $map_item;
        }
        return $return;
    }

    public static function geneSearchConditions($model_class,$params = []){

        if(!$params){
            return $model_class;
        }
        foreach($params as $_key => $_value){
            //kela 修改2019-09-21\
            if(is_numeric($_key)){
                //键值是数字则是用 操作符格式
                $model_class->andFilterWhere($_value);
            }else{
                //键值是字符串是 哈希格式
                $model_class->andFilterWhere([$_key => $_value]);
            }
        }
        return $model_class;
    }
}