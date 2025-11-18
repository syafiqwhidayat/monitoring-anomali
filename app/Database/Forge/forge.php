<?php
$forge = \Config\Database::forge();

if ($forge->createDatabase('monitoring_anomali', true)) {
    echo "Database monitoring_anomali berhasil dibuat";
}

$forge->createTable('users');

$fields = [
    'users' => [
        'type' => 'VARCHAR',
        'constraint' => 255,
    ],
];
