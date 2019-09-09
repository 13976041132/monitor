<?php


    namespace Eli\Monitor\Core;

    use Eli\Monitor\Api;
    use Eli\Monitor\Error;

    class Udp extends Protocol {

        public $transfer_protocol = 'udp';

        public function __construct($host,$port)
        {
            parent::__construct($host,$port);

        }

        /**
         * 上报信息之前操作
         */
        protected function _beforeRecord(){

        }
        /**
         * 信息上报
         * @param Api $api
         * @param Error $error
         * @return mixed|void
         */
        public function record(Api $api,Error $error)
        {
            if(false===$this->onConnect()){
                return false;
            }
            $cost_time = microtime(true)-$api->request_time;//接口请求花费时间

            $pack_data = $this->encode($api->route,json_encode($api->argv),$cost_time,$error->error);
            if(true === $this->send($pack_data)){
                echo '发送成功';
            }

        }

        /**
         * 客户端连接
         * @return bool
         */
        public function onConnect() :bool
        {
            if($this->onConnect!==self::ENABLE){
                $this->socket = stream_socket_client($this->socket_address);
                if(!$this->socket){
                    $this->onConnect = self::DISABLE;
                    return false;
                }
                $this->onConnect = self::ENABLE;
                $this->onClose = self::DISABLE;
            }
            return true;
        }

        /**
         * 发送数据
         * @param $send_data
         * @return bool|mixed
         */
        public function send($send_data) :bool
        {
            if(is_resource($this->socket)){
                return stream_socket_sendto($this->socket,$send_data) == strlen($send_data);
            }
            return false;
        }

    }
