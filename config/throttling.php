<?php 

// config/throttling.php
return [
    'login_attempts' => env('LOGIN_ATTEMPTS', 10),
    'login_decay' => env('LOGIN_DECAY', 1), // 1 minute
];
