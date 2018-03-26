<?php

return [
    'adminEmail' => 'admin@example.com',
    'my_salt' => 'coolest_salt_ever_555',
    'dollar' => 58,
    'euro' => 68,
    'additional_url_part' => '',///dev/events2/html'
    'sw_frommail' => 'mg701@yandex.ru',
    'sw_pass' => 'mg701Yandex@.NET<3LK%J3S:dK_34+-',
    'sw_tomail' => 'ricky_11@mail.ru',
    'sw_host' => 'smtp.yandex.ru',
    'sw_port' => 587,//465,
    'sw_enc' => 'tls',//'ssl',
    'test_mail' => 'iduso@mail.ru',
    'name' => 'Martin German',
    'restore_salt' => 'S2per_-%^CoolRestoreSa1t@354%__Ever++', // S2per_-%^CoolRestoreSa1t@354%__Ever++
    'history_post_count' => 25,
    'history_post_search' => 50,
    'file_export_salt' => '7dfklj*3478LKjdf8459KLJDKLF',
    //'pathUploads' => '@web',
    'pathUploads' => realpath(dirname(__FILE__)).'\..\web\upload\\',
    'fileMaxAmount' => 10,
    'fileMaxSize' => 10*1024*1024,
];
