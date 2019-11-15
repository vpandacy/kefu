<?php

namespace common\components\helper;
use common\services\BaseService;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ExcelHelper extends BaseService {

    public static function readExcel($path, $header_mapping = [],$extend = "xlsx")
    {
        try {
            $extend = strtolower( $extend );
            switch ($extend ){
                case "xls":
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    break;
                case "csv":
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                    $reader->setInputEncoding("GBK");//这一行不加 无法读取出来中文字符串
                    break;
                default:
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    break;
            }

            $spreadsheet = $reader->load($path);
            $reader->setReadDataOnly(true);//设置为只读
            $sheet = $spreadsheet->getSheet(0);

            //获取行数与列数,注意列数需要转换
            $highestRowNum = $sheet->getHighestDataRow();
            $highestColumn = $sheet->getHighestDataColumn();
            $highestColumnNum = Coordinate::columnIndexFromString($highestColumn);
            $usefullColumnNum = $highestColumnNum;
//取得字段，这里测试表格中的第一行为数据的字段，因此先取出用来作后面数组的键名
            $filed = array();
            for ($i = 1; $i < $highestColumnNum + 1; $i++) {
                $cellName = Coordinate::stringFromColumnIndex($i) . '1';
                $cellVal = $sheet->getCell($cellName)->getValue();//取得列内容
                if (!$cellVal) {
                    break;
                }
                $usefullColumnNum = $i;
                $filed [$i] = $cellVal;
            }
            //开始取出数据并存入数组
            $data = [];
            for ($i = 2; $i <= $highestRowNum; $i++) {//ignore row 1
                $row = [];
                for ($j = 1; $j < $usefullColumnNum + 1; $j++) {
                    if (!isset($header_mapping[$filed[$j]])) {
                        continue;
                    }
                    $cellName = Coordinate::stringFromColumnIndex($j) . $i;
                    $cellVal = $sheet->getCell($cellName)->getValue();
                    if ($cellVal instanceof RichText) { //富文本转换字符串
                        $cellVal = $cellVal->__toString();
                    }

                    $fd = $header_mapping[$filed[$j]];
                    $row[$fd] = trim($cellVal);
                }

                if (array_filter($row)) {//整行为空数据就过滤掉
                    $data [] = $row;
                }
            }
            return $data;

        } catch (\Exception $e) {
            return self::_err( $e->getMessage() );
        }
    }
}