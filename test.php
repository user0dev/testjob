<?php

$url = "http://127.0.0.1/svn/testjob";
// $filesDir = "/tmp/php/test";

// if (!file_exists($filesDir)) {
//     mkdir($filesDir);
// }

// // svn_export($url, $filesDir);
// var_dump(svn_ls($url, SVN_REVISION_HEAD, true));
// //var_dump(svn_cat("http://127.0.0.1/svn/testjob/test.php"));


// // echo svn_client_version();
// $zip = new ZipArchive;
// $res = $zip->open('test2.zip', ZipArchive::CREATE);
// if ($res === TRUE) {
//     $zip->addEmptyDir("test");
//     $zip->addFromString('test/test.php', svn_cat("http://127.0.0.1/svn/testjob/test.php"));
//     $zip->close();
//     echo 'ok';
// } else {
//     echo 'failed';
// }

// $test = '/^\s*$/';
// var_dump($test);
// exit;

function svnArrayIsRight($elem) {
    if (!isset($elem['type']) || !isset($elem['name'])) {
        return false;
    }
    if ($elem['type'] != 'dir' && $elem['type'] != 'file') {
        return false;
    }
    return preg_match('/^\\s*$/', $elem['name']) != 1;
}

// function svnUrlIsRight($url) {
//     return is_string($url) && preg_match('/^http://[\w\d.\/@:]+/i', $url) == 1;
// }

// function zipPathIsRight($zip) {
//     return is_string($zip) && file_exists(dirname($zip));
// }

function getPathLevel($path) {
    $path = str_replace('//', '/', $path);
    if ($path[0] == '/') {
        $path = substr($path, 0, 1);
    }
    if ($path[strlen($path) - 1]) {
        $path = substr($path, -1, 1);
    }
    return substr_count($path, '/');
}

// function compareSvnPath($val1, $val2) {
//     $l1 = getPathLevel($val1['name']);
//     $l2 = getPathLevel($val2['name']);
//     if ($l1 < $l2) {
//         return -1;
//     } elseif ($l1 > $l2) {
//         return 1;
//     } elseif ($val1['type'] == 'dir' && $val2['type'] != 'dir') {
//         return -1;
//     } elseif ($val1['type'] != 'dir' && $val2['type'] == 'dir') {
//         return 1;
//     } else {
//         return strcasecmp($val1['name'], $val2['name']);
//     }
// }


function svnToZip($url, $zipPath)
{
    $list = svn_ls($url, SVN_REVISION_HEAD, true);
    if (!is_array($list) || count($list) == 0) {
        return false;
    }
    $list = array_filter($list, 'svnArrayIsRight');
    //var_dump($list);
    // foreach($list as $obj) {
    //     echo $obj['type'] . ' - ' . $obj['name'] . PHP_EOL;
    // }
    // return;
    //var_dump($dirs);
    $dirs = [];
    foreach ($list as $obj) {
        if ($obj['type'] == 'dir') {
            $dirs[] = $obj['name'];
        }
    }
    $zip = new ZipArchive;
    if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
        return false;
    }
    foreach($dirs as $name) {
        $zip->addEmptyDir($name);
    }
    foreach ($list as $obj) {
        if ($obj['type'] == 'file') {
            $file = svn_cat($url . '/' . $obj['name']);
            if ($file !== false) {
                $zip->addFromString($obj['name'], $file);
            }
        }
    }
    $zip->close();
}

svnToZip($url, "testjob.zip");