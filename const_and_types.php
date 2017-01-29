<?php

if (!extension_loaded("SPL_Types")) {
    require_once ("mysplenum.php");
}

// const LOGDB_STATUS_SUCCESS = 2;
// const LOGDB_STATUS_FAILED = 0;
// const LOGDB_STATUS_PARTIALLY = 1;

//const LOGDB_STATUS = ['error' => 0, 'partially' => 1, 'success' => 2];

class LogDBStatus extends SplEnum {
    public const __default = LogDBStatus::ERROR;
    public const ERROR = "0";
    public const SUCCESS = "2";
    public const PARTIALLY = "1";
}

class UrlStatus {
    public $url;
    public $status;
    public function __construct($url, LogDBStatus $status) {
        if (!is_string($url)) {
            throw new InvalidArgumentException("\$url must be string. Input was $url");
        }
        $this->url = $url;
        $this->status = $status;
    }
}

