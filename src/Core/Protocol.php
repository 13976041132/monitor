<?php
    namespace Eli\Monitor\Core;

    use Eli\Monitor\Api;
    use Eli\Monitor\Error;

    abstract  class Protocol {

        const ENABLE  = 1;

        const DISABLE = 0;

        const PACK_MAX_SIZE  = 65507;

        const MAX_CHAR_VALUE = 255;

        public $port   = 20001;

        public $host   = '127.0.0.1';

        public $onConnect = null; //socket 连接时

        public $onClose   = null;  //socket 关闭时

        public $socket    = null;//socket

        public $transfer_protocol = 'tcp';

        public $socket_address = null;


        public function __construct($host=null,$port=null)
        {
            $this->host = $host?$host:$this->host;
            $this->port = $port?$port:$this->port;
            $this->socket_address = $this->transfer_protocol.'://'.$this->host.':'.$this->port;
        }

        /**
         * 信息上报
         * @param Api $api
         * @param Error $error
         * @return mixed
         */
        abstract public function record(Api $api,Error $error);


        /**
         * 客户端字件套连接
         */
         abstract public function onConnect();

        /**
         * @param $send_data
         * @return mixed
         */
         abstract public function send($send_data);

        /**
         * 关闭
         */
         public function onClose(){

         }


        public function encode(...$pack_data){
            $request_route = $this->cutRouteLength($pack_data[0]);
            $error_message = $this->cutErrorMessageLength($pack_data[3]);
            return pack('CNdN',strlen($request_route),strlen($pack_data[1]),$pack_data[2],$error_message)
                .$request_route.$pack_data[1].$pack_data[2].$error_message;
        }

        /**
         * pack解码
         * @param $binary_data
         */
        public function decode($binary_data){
            $decode_data_arr = unpack('Crequest_route/Nargv/dcost_time/Nerror_message',$binary_data);
            $error_message = substr($binary_data,-$decode_data_arr['error_message']);
            $cost_time = $decode_data_arr['cost_time'];
            $argv = substr($binary_data,-$decode_data_arr['error_message']-strlen($cost_time)-$decode_data_arr['argv'],$decode_data_arr['argv']);
        }

        /**
         * 截取路由长度
         * @param string $route
         * @return bool|strings
         */
        public function cutRouteLength(string $route) :string{
            return self::MAX_CHAR_VALUE<strlen($route)?substr($route,0,self::MAX_CHAR_VALUE):$route;
        }

        /**
         * 截取错误信息
         * @param string $error_message
         * @return bool|strings
         */
        public function cutErrorMessageLength(string $error_message) :string{
            return self::PACK_MAX_SIZE<strlen($error_message)?substr($error_message,0,self::PACK_MAX_SIZE):$error_message;
        }



    }
