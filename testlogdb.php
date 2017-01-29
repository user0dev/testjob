<?php

require_once("logdb.php");

$logDB = new LogDB();
$logDB->addEntry("test.zip", new LogDBStatus(LogDBStatus::ERROR),
                [new UrlStatus("test.ru", new LogDBStatus(LogDBStatus::ERROR))]);