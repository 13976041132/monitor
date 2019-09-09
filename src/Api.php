<?php

    /**
     * @author:Eli
     * description:api信息类
     */
    namespace Eli\Monitor;

    class Api{

        public $is_cli = FALSE;//是否是命令行执行

        public $server = [];

        public $route  = null;//接口路由

        public $dir    = null;//文件目录

        public $argv   = [];//命令行请求参数

        public $post   = [];//http:post参数

        public $get    = [];//http:get参数

        public $request_method = '';

        public $request_time = 0;//请求时间

        public $domain_name = null;//访问的域名的名字

        public $use_memory = 0;

        public function __construct()
        {
            $this->server = $_SERVER;
            $this->is_cli = PHP_SAPI == 'cli' ? true : false;
            $this->post = $this->is_cli?$_POST:[];
            $this->get  = $this->is_cli?$_GET:[];
            $this->domain_name = $this->is_cli?'':$this->server['HTTP_HOST'];
            $this->request_method =  $this->is_cli?'':$this->server['REQUEST_METHOD'];//请求方法
            $this->init();
        }

        public function init(){
           $this->setArgv();
           $this->uriToRoute();
           $this->getUseMemory();
        }

        /**
         * 设置访问uri
         */
        public function uriToRoute(){
            $this->route = $this->is_cli?$this->server['PHP_SELF']:$this->server['DOCUMENT_URI'];

        }

        /**
         * 获取参数
         */
        public function setArgv(){
            if($this->is_cli){
                $this->argv = $this->server['argv']??[];
                unset($this->argv[0]);
            }else{
                $this->argv = $this->get+$this->post;
            }
        }

        /**
         * 获取接口使用的内存
         */
        public function getUseMemory(){
            $this->use_memory = memory_get_usage();
        }
    }