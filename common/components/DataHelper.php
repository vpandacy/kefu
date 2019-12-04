<?php
namespace common\components;

use yii\db\ActiveRecord;

class DataHelper {
    /**
     * 根据某个字段 in  查询
     * @param array $data   原始数据
     * @param ActiveRecord $relate_model   关联的模型
     * @param string $id_column 主表的外键
     * @param string $pk_column 关联表的主键
     * @param array $name_columns 要获取的内容
     * @return array
     */
    public static function getDicByRelateID($data,$relate_model,$id_column,$pk_column,$name_columns = [])
    {
        $_ids = [];
        $_names = [];
        foreach($data as $_row)
        {
            $_ids[] = $_row[$id_column];
        }
        $rel_data = $relate_model::findAll([$pk_column => array_unique($_ids)]);

        foreach($rel_data as $_rel)
        {
            $map_item = [];
            if($name_columns && is_array($name_columns)){
                foreach($name_columns as $name_column){
                    $map_item[$name_column] = $_rel->$name_column;
                }
            } else {
				$map_item = $_rel;	//不传查询字段返回所有字段
			}
            $_names[$_rel->$pk_column] = $map_item;
        }

        return $_names;
    }

    /**
     * 转义字符串.
     * @param $value
     * @return string
     */
    public static function encode($value)
    {
        return htmlspecialchars($value);
    }

    /**
     * 数组进行转义.
     * @param $data
     * @param array $ignore
     * @return array
     */
    public static function encodeArray($data, $ignore = [])
    {
        if(!is_array($data)) {
            return $data;
        }

        foreach($data as $key => $val) {
            if(in_array($key, $ignore)) {
                continue;
            }

            if(is_array($val)) {
                $val = self::encodeArray($val);
            }

            if(is_string($val)) {
                $val = self::encode($val);
            }

            $data[$key] = $val;
        }

        return $data;
    }
}