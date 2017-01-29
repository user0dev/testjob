<?php

require_once (__DIR__ . '/const_and_types.php');

class LogDB {
    protected $db;

    public function __construct($dbName = "logdb.db") {
        $this->db = new SQLite3($dbName);
        $min = min(LogDBStatus::getConstList());
        $max = max(LogDBStatus::getConstList());
        $this->db->querySingle("CREATE TABLE IF NOT EXISTS backup (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, status INTEGER NOT NULL CHECK(status >= $min AND status <= $max), file_path TEXT NOT NULL, message TEXT NOT NULL DEFAULT '')");

        $this->db->querySingle("CREATE TABLE IF NOT EXISTS repo (id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, bakup_id INTEGER NOT NULL, url TEXT NOT NULL, status INTEGER NOT NULL CHECK(status >= $min AND status <= $max), message TEXT NOT NULL DEFAULT '')");
    }
    public function addEntry($fileName, LogDBStatus $status, $arrUrlList) {
        //pathinfo($fileName);
        if (!is_string($fileName) || !trim($fileName)) {
            throw new InvalidArgumentException("\$fineName must be string. Input was '$fileName'");
        }
        if (!is_array($arrUrlList) || !$arrUrlList) {
            throw new InvalidArgumentException("\$arrUrlList must be array");
        }
        $queryBackup = "INSERT INTO backup (file_path, status) VALUES ('$fileName', $status)";
        //var_dump($queryBackup);
        $this->db->querySingle($queryBackup);
        $bakupId = $this->db->lastInsertRowID();
        $repoValues = "";
        foreach ($arrUrlList as $elem) {
            if (!($elem instanceof UrlStatus)) {
                throw new InvalidArgumentException("\$arrUrlList must contain object 'UrlStatus'");
            }
            if ($repoValues != "") {
                $repoValue .= ", ";
            }
            $repoValues .= "($bakupId, '$elem->url', $elem->status)";
        }
        $queryRepo = "INSERT INTO repo (bakup_id, url, status) VALUES $repoValues";
        //var_dump($queryRepo);
        $this->db->querySingle($queryRepo);

    }
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}

