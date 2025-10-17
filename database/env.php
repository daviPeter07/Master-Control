<?php
return [
  'db_password' => $_ENV['DB_PASS'] ?? 'rNLsUvaQOREujovGKwYhRnbAXsBfqojD',
  'db_user' => $_ENV['DB_USER'] ?? 'root',
  'db_server' => $_ENV['DB_HOST'] ?? 'mysql.railway.internal',
  'db_name' => $_ENV['DB_NAME'] ?? 'railway',
];
