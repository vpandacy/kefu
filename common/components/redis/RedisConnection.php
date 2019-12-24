<?php
namespace common\components\redis;

/**
 *  host port 等参数都与Redis扩展一致。
 */
class RedisConnection extends \yii\base\Component
{
    const EVENT_AFTER_OPEN = 'afterOpen';

    public $host = 'localhost';
    public $port = 6379;
    public $timeout =  0;
    public $database = 0;
    public $password = 0;
    public $unixSocket;
    public $retry_interval;
    public $prefix = 'REDIS_CACHE_YYY_';

    private $_redisconn_instance = null;

    public function __sleep()
    {
        $this->close();
        return array_keys(get_object_vars($this));
    }

    /**
     * Returns a value indicating whether the DB connection is established.
     * @return boolean whether the DB connection is established
     */
    public function getIsActive()
    {
        return $this->_redisconn_instance !== null;
    }

    /**
     * Establishes a DB connection.
     * It does nothing if a DB connection has already been established.
     * @throws \Exception if connection fails
     */
    public function open()
    {
        if ($this->_redisconn_instance !== null) {
            return;
        }
        $this->_redisconn_instance = new \Redis();
        $success = false;
        if($this->unixSocket)
        {
            $success = $this->_redisconn_instance->connect($this->unixSocket);
        }
        else
        {
            $success = $this->_redisconn_instance->connect($this->host,$this->port,$this->timeout,null,$this->retry_interval);
        }
        if($success)
        {
            if( $this->password ){
                $this->_redisconn_instance->auth($this->password);
            }
            $this->select($this->database);
            $this->initConnection();
        }
        else
        {
             \Yii::error("Failed to open redis DB connection ", __CLASS__);
             throw new \Exception('Failed to open redis DB connection ', '', -1);
        }
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     */
    public function close()
    {
        if ($this->_redisconn_instance !== null) {
            $this->_redisconn_instance->close();
            $this->_redisconn_instance = null;
        }
    }

    /**
     * Initializes the DB connection.
     * This method is invoked right after the DB connection is established.
     * The default implementation triggers an [[EVENT_AFTER_OPEN]] event.
     */
    protected function initConnection()
    {
        $this->trigger(self::EVENT_AFTER_OPEN);
        $this->setOption(\Redis::OPT_PREFIX, $this->prefix);
    }

    /**
     * Returns the name of the DB driver for the current [[dsn]].
     * @return string name of the DB driver
     */
    public function getDriverName()
    {
        return 'redis';
    }

    /**
     * 删除redis的键.redis大于4.0.0,可以使用unlink
     * https://github.com/phpredis/phpredis/#class-redis
     * @param $key
     * @return mixed
     */
    public function delete($key)
    {
        if(method_exists($this->_redisconn_instance,'del')) {
            return $this->_redisconn_instance->del($key);
        }

        if(method_exists($this->_redisconn_instance,'unlink')) {
            return $this->_redisconn_instance->unlink($key);
        }

        if(method_exists($this->_redisconn_instance,'delete')) {
            return $this->_redisconn_instance->delete($key);
        }

        return true;
    }

    /**
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        $this->open();
        //TODO 可能要禁止使用者调用connect之类的方法。
        if (is_callable([$this->_redisconn_instance,$name],true)) {
            return call_user_func_array([$this->_redisconn_instance,$name],$params);
        } else {
            return parent::__call($name, $params);
        }
    }
}