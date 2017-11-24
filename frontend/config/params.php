<?php
return [
    'adminEmail' => 'admin@example.com',

    'maxFileSize' => 1024 * 1024 * 2, // 2 megabites
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
    'feedPostLimit' => 200,
    'maxCommentsInOnePost' => 6,
    'maxCommentLenghtInPost' => 1000,
    'limitPostsInProfile' => 20,
    'limitPostsInPostList' => 50,
    'supportedLanguages' => [
        'en-US',
        'ru-RU',
        'lt-LT',
    ],

];