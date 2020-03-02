<?php
namespace common\services\worker;

use common\services\BaseService;

class WorkerService extends BaseService
{
    public static $connectTimeout = 3;

    public static function getGatewayClientByRegister($register, $secret_key = '')
    {
        $register_addresses = (array)$register;
        foreach ($register_addresses as $register_address) {
            set_error_handler(function(){});
            $client = stream_socket_client('tcp://' . $register_address, $err_no, $err_msg, static::$connectTimeout);
            restore_error_handler();
            if ($client) {
                break;
            }
        }

        if (!$client) {
            return self::_err('Can not connect to tcp://' . $register_address . ' ' . $err_msg);
        }

        fwrite($client, '{"event":"worker_connect","secret_key":"' . $secret_key . '"}' . "\n");
        stream_set_timeout($client, 5);
        $ret = fgets($client, 655350);

        if (!$ret || !$data = json_decode(trim($ret), true)) {
            return self::_err('getAllGatewayAddressesFromRegister fail. tcp://' .
                $register_address . ' return ' . var_export($ret, true));
        }

        return $data['addresses'];
    }
}