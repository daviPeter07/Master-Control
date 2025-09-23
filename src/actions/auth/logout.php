<?php
session_start();
session_unset();
session_destroy();

header("Location: /masterControl/src/auth/login/index.php");
exit();
