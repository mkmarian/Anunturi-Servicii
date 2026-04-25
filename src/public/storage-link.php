<?php
$target = dirname(__DIR__) . '/storage/app/public';
$link   = __DIR__ . '/storage';

if (file_exists($link)) {
    echo 'Link-ul există deja la: ' . $link;
} elseif (symlink($target, $link)) {
    echo 'Storage link creat cu succes!';
} else {
    echo 'Eroare la crearea symlink-ului. Încearcă din cPanel Terminal: php artisan storage:link';
}
