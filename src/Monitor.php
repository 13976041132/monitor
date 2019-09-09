<?php

    //监控类
    namespace Eli\Monitor;

    class monitor{

        public $api = null;  //api类

        public $error = null;  //错误信息类

        public $protocol_class = null;

        public $transport_protocol = 'udp';//传输方式

        public $config = [];

        public function __construct($transport_protocol=null)
        {
            $this->api = new Api();
            $this->error = new Error();
            $transport_protocol and $this->setTransportProtocol($transport_protocol);
        }

        /**
         * 记录前实例化操作对象
         * @return bool|Core\Protocol
         */
        public function beforeRecord(){
            $protocol_class = __NAMESPACE__.'\Core\\'.ucfirst($this->transport_protocol);
            if(!class_exists($protocol_class,true)){
                return false;
            }
            $this->protocol_class = new $protocol_class('127.0.0.1',55656);
            return $this->protocol_class;
        }

        /**
         * 异常记录
         */
        public function record(){
             $this->beforeRecord()->record($this->api,$this->error);
        }

        public function setTransportProtocol($transport_protocol){
            $this->transport_protocol = strtolower($transport_protocol);
        }

        /**
         * 检查配置
         */
        public function checkConfig(){

        }

        /**
         * 异常上限通知
         */
        public function notify(){

        }

        /**
         * 异常上限设置
         */
        public function setMaxLimit(){

        }

        /**
         * 通知频率
         */
        public function notifyRate(){

        }

        /**
         * 系统异常累计通知/还是单一接口异常累计通知
         */
        public function accumulativeType(){

        }

        /**
         * 服务接口调用
         */
        public function invokingApi(){

        }

        /**
         * curl请求
         */
        public function curl(){

        }




    }

