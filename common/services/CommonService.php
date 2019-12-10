<?php
namespace common\services;

class CommonService extends BaseService
{
    /**
     * 生成唯一字符串.
     * @param string $str
     * @return string
     */
    public static function genUniqueName($str = '')
    {
        $input = time() . uniqid() . $str;

        $base32 = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
            'y', 'z', '0', '1', '2', '3', '4', '5'
        ];

        $hex = md5($input);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = [];

        for ($i = 0; $i < $subHexLen; $i++) {
            //把加密字符按照8位一组16进制与0x3FFFFFFF(30位1)进行位与运算
            $subHex = substr($hex, $i * 8, 8);
            $int = 0x3FFFFFFF & (bin2hex($subHex));
            $out = '';

            for ($j = 0; $j < 2; $j++) {
                //把得到的值与0x0000001F进行位与运算，取得字符数组chars索引
                $val = 0x0000001F & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }

            $output[] = $out;
        }

        return implode('',$output);
    }


    /**
     * 检查密码强度和是否函数特殊字符.
     * @param string $pass
     * @param int $len
     * @return bool
     */
    public static function checkPassLevel($pass, $len = 6)
    {
        return true;
        $source = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&-+={}[]<>?/';

        $strs = str_split($source);

        $pass = str_split($pass);

        // 包含其他字符
        if(array_diff($pass,$strs)){
            return self::_err('您输入的密码包含了非法字符,特殊字符包含:!@#$%&-+={}[]<>?/');
        }

        if(count($pass) < $len) {
            return self::_err('您的密码长度不够,最小长度为:' . $len);
        }

        $data = [
            'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789',
            '!@#$%&-+={}[]<>?/'
        ];

        $count = 0;

        while($str = array_shift($data)){
            $str = str_split($str);
            if(array_intersect($str,$pass)){
                $count++;
            }
        }

        if($count <  3){
            return self::_err('您输入的密码强度不够,请至少包含字母,数字及特殊字符.');
        }

        return true;
    }

    /**
     * 检测链接是否是SSL连接
     * @return bool
     */
    public static function is_SSL()
    {
        if (!isset($_SERVER['HTTPS'])) {
            return false;
        }
        if ($_SERVER['HTTPS'] === 1) {  //Apache
            return true;
        } else {
            if ($_SERVER['HTTPS'] === 'on') { //IIS
                return true;
            } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
                return true;
            }
        }
        return false;
    }
}