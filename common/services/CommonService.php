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

    /**
     * 生成uuid.如果太复杂.传入浏览地址和IP,调用genUniqueName即可.
     * @return string
     * @throws \Exception
     */
    public static function genUUID()
    {
        $nodeBytes = random_bytes(6);
        $nodeMsb = substr($nodeBytes, 0, 3);
        $nodeLsb = substr($nodeBytes, 3);
        $nodeMsb = hex2bin(
            str_pad(
                dechex(hexdec(bin2hex($nodeMsb)) | 0x010000),
                6,
                '0',
                STR_PAD_LEFT
            )
        );

        $node = $nodeMsb . $nodeLsb;
        $node = str_pad(bin2hex($node), 12, '0', STR_PAD_LEFT);
        $clockSeq = random_int(0, 0x3fff);
        $seconds = strtotime('s');
        $microSeconds = microtime(true) * 10000 % 10000;
        $uuidTime = ($seconds * 10000000) + ($microSeconds * 10) + 0x01b21dd213814000;
        $uuidTime = [
            'low' => sprintf('%08x', $uuidTime & 0xffffffff),
            'mid' => sprintf('%04x', ($uuidTime >> 32) & 0xffff),
            'hi' => sprintf('%04x', ($uuidTime >> 48) & 0x0fff),
        ];
        $timeHi = hexdec($uuidTime['hi']) & 0x0fff;
        $timeHi |= 1 << 12;
        $clockSeqHi = $clockSeq & 0x3f;
        $clockSeqHi |= 0x80;
        $uuid = vsprintf(
            '%08s%04s%04s%02s%02s%012s',
            [
                $uuidTime['low'],
                $uuidTime['mid'],
                sprintf('%04x', $timeHi),
                sprintf('%02x', $clockSeqHi),
                sprintf('%02x', $clockSeq & 0xff),
                $node,
            ]
        );
        return $uuid;
    }

    /**
     * 判断是否为手机端.
     * @return bool
     */
    public static function isMobile()
    {
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }

        //此条摘自TPM智能切换模板引擎，适合TPM开发
        if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT']) {
            return true;
        }

        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
        }

        //判断手机发送的客户端标志,兼容性有待提高
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg',
                'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo',
                'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront',
                'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
                'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
            ];
            //从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }

        //协议法，因为有可能不准确，放到最后判断
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
                && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取终端类型
     * @param string $ua
     * @return int
     */
    public static function getSourceByUa($ua)
    {
        if(strpos($ua,'MicroMessenger') !== false) {
            return 3;
        }

        $clientkeywords = [
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg',
            'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo',
            'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront',
            'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
            'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        ];

        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($ua))) {
            return 2;
        }

        return 1;
    }

    /**
     * 获取IP
     * @return string
     */
    public static function getIP(){
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        return isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"]:'';
    }
}