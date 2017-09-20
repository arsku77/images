<?php
return [
    'adminEmail' => 'admin@example.com',

    'maxFileSize' => 1024 * 1024 * 2, // 2 megabites
    'storagePath' => '@frontend/web/uploads/',
    'storageUri' => '/uploads/',   // http://images.com/uploads/f1/d7/739f9a9c9a99294.jpg
    // Настройки могут быть вложенными
    'profilePicture' => [
        'maxWidth' => 1280,
        'maxHeight' => 1024,
    ],
    'postPicture' => [
        'maxWidth' => 1024,
        'maxHeight' => 768,
    ],

];