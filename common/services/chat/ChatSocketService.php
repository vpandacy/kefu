<?php
/**
 * Class ChatSocketService
 * Author: Vincent
 * WeChat: apanly
 * CreateTime: 2019/12/14 3:48 PM
 */

namespace common\services\chat;


use common\services\BaseService;

class ChatSocketService
{
    private  $client = null;
    public function __construct( $url ) {
        try {
            $client = stream_socket_client( $url );
            stream_set_timeout( $client,2 );
            $this->client = $client;
        }catch (\Exception $e){

        }
    }

    public function send( $content ){
        try {
            fwrite($this->client, $content . "\n");
            $ret = fgets($this->client, 10);
            return $ret;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

    public function close(){
        try{
            fclose( $this->client );
            $this->client = null;
        }catch (\Exception $e){

        }
    }
}