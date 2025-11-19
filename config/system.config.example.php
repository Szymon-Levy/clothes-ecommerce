<?php

include dirname(__DIR__) . '/config/settings/server.php';

return ['system', [
        'dev' => true,
        'doc_root' => $docRoot,
        'is_local' => $isLocal,
        'dev_domain' => 'http://localhost',
        'prod_domain' => '',
        'uploads_dir' => $uploadsDir,
        'admin_pagination' => 15
    ]
];