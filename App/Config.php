<?php
$https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'? 'https://': 'http://';
$year = date('Y');
define("BASE_URL", "{$https}mobelinn.test/");
define("DEFAULT_CONTROLLER", "index");
define("DEFAULT_LAYOUT", "default");
define('DEFAULT_ROUTE', ROOT . 'Views' . DS . 'layout' . DS . DEFAULT_LAYOUT . DS);

// Paths
define('PATH_VIEWS', ROOT . 'Views' . DS);
define('LANG', 'es-mx');
define('PATH_LANG', ROOT . 'Languages' . DS);
define('PATH_STORAGE', 'Storage' . DS);
define('PATH_LIBS', 'Libs' . DS);
define('PATH_CACHE', 'tmp' . DS . 'cache' . DS);

define("APP_NAME", "Mobel Inn");
define("APP_COMPANY", "");
define("APP_AUTHOR", "Alejandro Godinez");
define("COPYRIGHT", "&copy Copyright {$year} - " . APP_NAME  );
define("SESSION_TIME", 60);
define("SANDBOX", true);


// DATOS PARA CONEXION LOCAL A BASE DE DATOS MYSQL...
define("HOST", "127.0.0.1");
define("USER", "homestead");
define("PASS", "secret");
define("DB_NAME","u554034426_app_mobelinn");
define("CHAR", "utf8");
define('DB_TYPE', 'pdo');

// skydropx
define('SKYDROPX_SANDBOX', true);
define('SKYDROPX_API_KEY_SANDBOX', '9oX9esePSWvGWcJD8TJtzCyYg4EJhKBgz7A1sz7xk20');
define('SKYDROPX_API_KEY_PRODUCTION', '');

// Paypal
define('PAYPAL_SANDBOX', true);
define('PAYPAL_CLIENT_ID_SANDBOX', 'AWDpv-Z8sljZ3uTaCtBuvpDkMLeXaFQMNaga1O69iUB0JhF9Dv4XEwZK5eUCxAlLTFNpNQpPPi6ftjmK');
define('PAYPAL_SECRET_SANDBOX', 'ENuve6K5m3bagjiH7p5YMcyW4gGU8dxEKNgFyOq9GXocIBXxD_aqYCUFG3QFkukaAJ6sd_mMZ8sg7VLy');
define('PAYPAL_CLIENT_ID_PRODUCTION', '');
define('PAYPAL_SECRET_PRODUCTION', '');

// Mercado Pago
define('MP_SANDBOX', true);
define('MP_API_KEY_SANDBOX', 'TEST-ba1d6f5e-d9dc-408c-96c7-a9e95c09c93e');
define('MP_TOKEN_SANDBOX', 'TEST-3360386434857592-020415-dd41beed7aac310ca55452a1d35bfd3a-1668877480');
define('MP_API_KEY_PRODUCTION', 'TEST-ba1d6f5e-d9dc-408c-96c7-a9e95c09c93e');
define('MP_TOKEN_PRODUCTION', 'TEST-3360386434857592-020415-dd41beed7aac310ca55452a1d35bfd3a-1668877480');

// Currency openexchangerates.org
define('CURRENCY_CONVERT_ID', '3f28bccad1984a80a67a3471b75a7b21');
