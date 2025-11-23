<?php

include dirname(__DIR__) . '/config/settings/server.php';

return ['system', [
        'dev' => true,
        'is_local' => $isLocal,
        'dev_domain' => 'http://localhost:8000',
        'prod_domain' => 'https://clothes-ecommerce.com.pl',
        'uploads_dir' => $uploadsDir,
        'admin_pagination' => 15
    ]
];