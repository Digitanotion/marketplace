<?php
$GLOBALS['globalVari']="Welcome Variable";
$a="Welcome A";
function setGlobalVariable(){
    $GLOBALS['globalVari']="Welcome Variable";
    global $a;
}
?>