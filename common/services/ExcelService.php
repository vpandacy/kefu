<?php
namespace common\services;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\web\UploadedFile;

class ExcelService extends BaseService
{
    /**
     * 获取文件的filename.
     * @param $file_key
     * @return false|array
     */
    public static function import($file_key)
    {
        $upload_file = UploadedFile::getInstanceByName($file_key);

        if(!$upload_file) {
            return self::_err('请指定上传的文件');
        }

        $suffix = substr($upload_file->name,strrpos($upload_file->name,'.'));

        if(!in_array($suffix,['.xls','.xlsx'])) {
            return self::_err('这不是一个excel文件格式,请上传正确的excel');
        }

        try{
            $reader = $suffix == '.xlsx' ? new Xlsx() : new Xls();

            $spread_sheet = $reader->load($upload_file->tempName);

            $data = $spread_sheet->getActiveSheet()->toArray();

        }catch (\Exception $e) {
            return self::_err($e->getMessage());
        }

        if(!$data) {
            return self::_err('读取文件失败,请检查文件信息');
        }

        return $data;
    }
}