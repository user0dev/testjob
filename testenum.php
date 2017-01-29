<?php

require_once ("mysplenum.php");

class Days extends SplEnum {
    const Monday = "0";
    const Tuesday = "1";
    const __default = "0";
}

function testEnum(Days $day) {
    var_dump($day);
}

testEnum(new Days(Days::Monday));