<?php
return [
  'db_password' => $_ENV['DB_PASS'] ?? 'Info@1234',
  'db_user' => $_ENV['DB_USER'] ?? 'root',
  'db_server' => $_ENV['DB_HOST'] ?? 'localhost',
  'db_name' => $_ENV['DB_NAME'] ?? 'mastercontrol',
];
