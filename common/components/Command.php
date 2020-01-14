<?php
namespace common\components;

use yii\db\Command as DBCommand;
use yii\db\Exception;

class Command extends DBCommand
{
    /**
     * 修复执行的错误.
     * @return int
     * @throws Exception
     */
    public function execute()
    {
        try{
            return parent::execute();
        }catch (Exception $e) {
            if($e->getCode() != 2006 && $e->getCode() != 2013) {
                // 继续报错.
                throw $e;
            }
        }

        $this->db->close();
        $this->db->open();
        $this->pdoStatement = null;

        return parent::execute();
    }

    /**
     * 重新处理一下.
     * @param string $method
     * @param null $fetchMode
     * @return mixed
     * @throws Exception
     */
    protected function queryInternal($method, $fetchMode = null)
    {
        try{
            return parent::queryInternal($method, $fetchMode);
        }catch(Exception $e) {
            if($e->getCode() != 2006 && $e->getCode() != 2013) {
                // 继续报错.
                throw $e;
            }
        }

        // 关闭链接.
        $this->db->close();
        // 重新打开.
        $this->db->open();
        $this->pdoStatement = null;
        return parent::queryInternal($method, $fetchMode);
    }
}