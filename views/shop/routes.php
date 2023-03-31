<?php

require_once __DIR__.'/router.php';

// get('views/shop/', 'page');
get('views/shop/$user', 'page');





any('/404','300');

// echo __DIR__;