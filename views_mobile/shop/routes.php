<?php

require_once __DIR__.'/router.php';

// get('/gaijinmall/views/shop', '300');
get('/views_mobile/shop/$user', 'page');


any('/404','300');

// echo __DIR__;