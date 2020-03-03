<?php
namespace common\services\office;

use \common\services\BaseService;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ExcelService extends BaseService{
    public static function export($head,  $body, $fileName = "导出结果.xlsx",$format = "excel",$is_save = false ){
        try{
            $spreadsheet = new Spreadsheet();
            $spreadsheet->setActiveSheetIndex( 0 );
            $sheet = $spreadsheet->getActiveSheet();

            $key = ord("A");
            foreach($head as $v){
                $colum = chr($key);
                $sheet ->setCellValue($colum.'1', $v);
                $sheet->getColumnDimension($colum)->setWidth(15);
                $key += 1;
            }
            $key = ord("A");
            $col_index = 2;
            foreach($body as $list){
                foreach ($list as $ret){
                    $colum = chr($key);
                    $sheet->setCellValueExplicit($colum.$col_index, ''.$ret,DataType::TYPE_STRING);
                    $key += 1;
                }
                $col_index += 1;
                $key = ord("A");
            }

            switch ($format){
                case "csv":
                    $writer = new Csv($spreadsheet);
                    break;
                default:
                    $writer = new Xlsx($spreadsheet);
                    break;
            }

            if( $is_save ){
                $writer->save("/tmp/{$fileName}");
            }else{
                switch ($format){
                    case "csv":
                        header('Content-Type: application/vnd.ms-excel');
                        break;
                    default:
                        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                        break;
                }

                header("Content-Disposition: attachment; filename=\"$fileName\"");
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            }

        }catch (\Exception $e){
            return self::_err( $e->getMessage() );
        }
        return \Yii::$app->end();
    }


    public static function exportCSV( $head,  $body,$file_name ){
        header("Content-type:text/csv;charset=utf-8");
        header('Content-Disposition: attachment;filename="'.$file_name);
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        //判断是否定义头标题

        foreach ($head as $key => $value) {
            $head[ $key ] = iconv("UTF-8", "GB2312//IGNORE", $value);
        }
        fputcsv($fp, $head);//该函数返回写入字符串的长度。若出错，则返回 false。。

        foreach ($body as $key => $value) {

            foreach ($value as $k => $v) {
                $value[$k] = iconv("UTF-8", "GB2312//IGNORE", $v);
            }
            fputcsv($fp, $value);//该函数返回写入字符串的长度。若出错，则返回 false。。
        }
        exit(0);
    }


    public static function readFile( $path, $excel_field_mapping = [],$extend = "xlsx" ){
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
                $usefullColumnNum = $i;
                $filed [$i] = $cellVal;
            }
            /**
             * 由于汉字描述是数组，需要进行标记
             */
            $chinese_mapping = [];
            $chinese_values = array_values( $excel_field_mapping );
            $chinese_keys = array_keys($excel_field_mapping );
            foreach ($chinese_values as $_field_key => $_field_val ){
                if( !is_array( $_field_val ) ){
                    $_field_val = [ $_field_val ];
                }

                foreach ( $_field_val as $_chinese_val ){
                    $chinese_mapping[ $_chinese_val  ] = $_field_key;
                }
            }
            //开始取出数据并存入数组
            $data = [];
            for ($i = 2; $i <= $highestRowNum; $i++) {//ignore row 1
                $row = [];
                for ($j = 1; $j < $usefullColumnNum + 1; $j++) {
                    if ( !isset( $filed[$j] ) || !isset($chinese_mapping[ $filed[$j] ] )) {
                        continue;
                    }
                    $cellName = Coordinate::stringFromColumnIndex($j) . $i;
                    $cellVal = $sheet->getCell($cellName)->getValue();
                    if ($cellVal instanceof RichText) { //富文本转换字符串
                        $cellVal = $cellVal->__toString();
                    }

                    $fd = $chinese_keys[ $chinese_mapping[$filed[$j]] ];
                    $row[$fd] = trim($cellVal);
                }
                if (array_filter($row)) {//整行为空数据就过滤掉
                    $data [] = $row;
                }
            }
            return $data;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
