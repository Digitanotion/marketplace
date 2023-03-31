<?php
include_once(__DIR__ . "\../../vendor/autoload.php");
USE Tmps\secure\Secu__;
USE Tmps\Db;
$secureIt=new Secu__();
$dbb=new Db();
echo $dbb;
echo $secureIt;
?>