<?php


    namespace Eli\Monitor;


    class Error
    {
        public $error = '';

        public $error_file = '';

        public $error_level = 0;

        public $error_code  = 0;

        public $error_file_line = '';

        public $last_error_info = []; // 捕捉最后错误的信息

        const ERROR_TYPE_LEVEL = [];//错误信息等级

        public function __construct(){
            $this->last_error_info = error_get_last();
            $this->_init();
        }

        /**
         * 初始化信息
         */
        protected function _init(){
            $this->setError();
            $this->setErrorFile();
            $this->setErrorLevel();
            $this->setErrorFileLine();
        }

        /**
         * 获取错误信息
         */
        public function setError(){
            $this->error = $this->last_error_info? $this->last_error_info['message']:'';

        }

        /**
         * 获取错误级别
         */
        public function setErrorLevel(){
            $this->error_level = $this->last_error_info? $this->last_error_info['type']:'';
        }

        /**
         * 获取错误信息所在在文件
         */
        public function setErrorFile(){
            $this->error_file = $this->last_error_info? $this->last_error_info['file']:'';
        }

        /**
         * 获取错误信息所在在文件行
         */
        public function setErrorFileLine(){
            $this->error_file_line = $this->last_error_info? $this->last_error_info['line']:'';
        }


        /**
         * 清除错误
         */
        public static function clearError(){
            error_clear_last();
        }
    }