<?php return array (
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'mercure' => 
      array (
        'driver' => 'mercure',
        'url' => NULL,
        'public_url' => NULL,
        'secret' => NULL,
        'encryption_key' => NULL,
        'claims' => 
        array (
          'iss' => NULL,
          'client_id' => 'Prof. Matin Eye Hospital',
        ),
        'cookie_name' => NULL,
        'subscribe_expiration' => 5,
        'async' => false,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => '12',
      'verify' => true,
      'limit' => NULL,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
    'rehash_on_login' => true,
  ),
  'images' => 
  array (
    'default' => 'gd',
  ),
  'app' => 
  array (
    'name' => 'Prof. Matin Eye Hospital',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost:8000',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'Asia/Dhaka',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:Dn97abxY3dv1J53J9VMZOPG6PUcnuj9buMekcBNg9zA=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Concurrency\\ConcurrencyServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Image\\ImageServiceProvider',
      11 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      12 => 'Illuminate\\Hashing\\HashServiceProvider',
      13 => 'Illuminate\\Mail\\MailServiceProvider',
      14 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      15 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      16 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      17 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      18 => 'Illuminate\\Queue\\QueueServiceProvider',
      19 => 'Illuminate\\Redis\\RedisServiceProvider',
      20 => 'Illuminate\\Session\\SessionServiceProvider',
      21 => 'Illuminate\\Translation\\TranslationServiceProvider',
      22 => 'Illuminate\\Validation\\ValidationServiceProvider',
      23 => 'Illuminate\\View\\ViewServiceProvider',
      24 => 'App\\Providers\\AppServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Benchmark' => 'Illuminate\\Support\\Benchmark',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Concurrency' => 'Illuminate\\Support\\Facades\\Concurrency',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Context' => 'Illuminate\\Support\\Facades\\Context',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Image' => 'Illuminate\\Support\\Facades\\Image',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schedule' => 'Illuminate\\Support\\Facades\\Schedule',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'Uri' => 'Illuminate\\Support\\Uri',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'scoped-session',
        'provider' => 'admins',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'hospital-accounts',
        'model' => 'Modules\\TenantCore\\Models\\User',
        'doctor_model' => 'Modules\\Hospital\\Models\\Doctor',
      ),
      'admins' => 
      array (
        'driver' => 'cached-eloquent',
        'model' => 'Modules\\Core\\Models\\Admin',
      ),
      'doctors' => 
      array (
        'driver' => 'eloquent',
        'model' => 'Modules\\Hospital\\Models\\Doctor',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'connection' => 'tenant',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
      'admins' => 
      array (
        'provider' => 'admins',
        'connection' => 'central',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
      'doctors' => 
      array (
        'provider' => 'doctors',
        'connection' => 'tenant',
        'table' => 'doctor_password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
    'login_types' => 
    array (
      'user' => 
      array (
        'label' => 'User',
        'broker' => 'users',
      ),
      'doctor' => 
      array (
        'label' => 'Doctor',
        'broker' => 'doctors',
      ),
    ),
    'password_min_length' => 5,
  ),
  'billing' => 
  array (
    'currency' => 'BDT',
    'currencies' => 
    array (
      0 => 'BDT',
      1 => 'USD',
      2 => 'EUR',
      3 => 'GBP',
      4 => 'INR',
    ),
    'default_plan' => 'starter',
    'invoice_prefix' => 'INV',
    'callback_base_url' => NULL,
    'drivers' => 
    array (
      'sslcommerz' => 'Modules\\Core\\Gateways\\SslcommerzGateway',
      'bkash' => 'Modules\\Core\\Gateways\\BkashGateway',
      'nagad' => 'Modules\\Core\\Gateways\\NagadGateway',
      'eps' => 'Modules\\Core\\Gateways\\EpsGateway',
    ),
  ),
  'boost' => 
  array (
    'enabled' => true,
    'rules' => 
    array (
      'enabled' => true,
      'scoped_guidelines' => false,
    ),
    'guidelines' => 
    array (
      'exclude' => 
      array (
      ),
    ),
    'skills' => 
    array (
      'exclude' => 
      array (
        0 => 'testing-best-practices',
        1 => 'tailwindcss-development',
      ),
    ),
    'executable_paths' => 
    array (
      'php' => NULL,
      'composer' => NULL,
      'npm' => NULL,
      'vendor_bin' => NULL,
      'current_directory' => NULL,
    ),
    'browser_logs_watcher' => true,
    'browser_log_levels' => 
    array (
      0 => 'error',
      1 => 'warning',
      2 => 'info',
      3 => 'debug',
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'session' => 
      array (
        'driver' => 'session',
        'key' => '_cache',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'cache',
        'lock_connection' => NULL,
        'lock_table' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/data',
        'lock_path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/locks',
      ),
      'storage' => 
      array (
        'driver' => 'storage',
        'disk' => NULL,
        'path' => 'framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'stores' => 
        array (
          0 => 'database',
          1 => 'array',
        ),
      ),
      'context' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/context',
        'lock_path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/locks/context',
      ),
      'branding' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/branding',
        'lock_path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/locks/branding',
      ),
      'tenant-handoff' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/tenant-handoff',
        'lock_path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/cache/tenant-handoff-locks',
      ),
    ),
    'prefix' => 'prof-matin-eye-hospital-cache-',
    'serializable_classes' => false,
  ),
  'database' => 
  array (
    'default' => 'central',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'new_prof_matin_eye_hospital',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'busy_timeout' => NULL,
        'journal_mode' => NULL,
        'synchronous' => NULL,
        'transaction_mode' => 'DEFERRED',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'new_prof_matin_eye_hospital',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'new_prof_matin_eye_hospital',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'new_prof_matin_eye_hospital',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'new_prof_matin_eye_hospital',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
      'central' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'new_prof_matin_eye_hospital_central',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'tenant' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => NULL,
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'provisioning' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => NULL,
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
    ),
    'migrations' => 
    array (
      'table' => 'migrations',
      'update_date_on_publish' => true,
    ),
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'prof-matin-eye-hospital-database-',
        'persistent' => false,
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
    ),
  ),
  'debugbar' => 
  array (
    'enabled' => NULL,
    'collect_jobs' => false,
    'except' => 
    array (
      0 => 'telescope*',
      1 => 'horizon*',
      2 => '_boost/browser-logs',
      3 => 'livewire-*/livewire.js',
    ),
    'collectors' => 
    array (
      'phpinfo' => false,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => false,
      'auth' => false,
      'gate' => true,
      'session' => false,
      'symfony_request' => true,
      'mail' => true,
      'laravel' => true,
      'events' => false,
      'logs' => false,
      'config' => false,
      'cache' => true,
      'models' => true,
      'livewire' => true,
      'inertia' => true,
      'jobs' => true,
      'pennant' => true,
      'ai' => true,
      'http_client' => true,
    ),
    'options' => 
    array (
      'time' => 
      array (
        'memory_usage' => false,
      ),
      'messages' => 
      array (
        'trace' => true,
        'backtrace_exclude_paths' => 
        array (
        ),
        'capture_dumps' => false,
        'timeline' => true,
      ),
      'memory' => 
      array (
        'reset_peak' => false,
        'with_baseline' => false,
        'precision' => 0,
      ),
      'auth' => 
      array (
        'show_name' => true,
        'show_guards' => true,
      ),
      'gate' => 
      array (
        'trace' => false,
        'timeline' => false,
      ),
      'db' => 
      array (
        'with_params' => true,
        'exclude_paths' => 
        array (
        ),
        'backtrace' => true,
        'backtrace_exclude_paths' => 
        array (
        ),
        'backtrace_editor_links' => false,
        'timeline' => false,
        'duration_background' => true,
        'explain' => true,
        'show_query_result' => false,
        'only_slow_queries' => true,
        'slow_threshold' => false,
        'memory_usage' => false,
        'soft_limit' => 100,
        'hard_limit' => 500,
      ),
      'mail' => 
      array (
        'timeline' => true,
        'show_body' => true,
      ),
      'views' => 
      array (
        'timeline' => true,
        'timeline_duration' => false,
        'data' => false,
        'group' => 50,
        'exclude_paths' => 
        array (
          0 => 'vendor/filament',
        ),
      ),
      'inertia' => 
      array (
        'pages' => 'js/Pages',
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'session' => 
      array (
        'masked' => 
        array (
        ),
      ),
      'symfony_request' => 
      array (
        'label' => true,
        'masked' => 
        array (
        ),
      ),
      'events' => 
      array (
        'data' => false,
        'listeners' => false,
        'excluded' => 
        array (
        ),
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
      'config' => 
      array (
        'masked' => 
        array (
        ),
      ),
      'cache' => 
      array (
        'values' => true,
        'timeline' => false,
      ),
      'http_client' => 
      array (
        'masked' => 
        array (
        ),
        'timeline' => true,
      ),
      'ai' => 
      array (
        'values' => true,
      ),
    ),
    'custom_collectors' => 
    array (
    ),
    'editor' => 'phpstorm',
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'ajax_handler_auto_show' => true,
    'ajax_handler_enable_tab' => true,
    'capture_streamed' => false,
    'streamed_content_types' => 
    array (
      0 => 'text/event-stream',
    ),
    'defer_datasets' => false,
    'remote_sites_path' => NULL,
    'local_sites_path' => NULL,
    'storage' => 
    array (
      'enabled' => true,
      'open' => NULL,
      'driver' => 'file',
      'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\debugbar',
      'connection' => NULL,
      'provider' => '',
    ),
    'force_allow_enable' => false,
    'use_dist_files' => true,
    'include_vendors' => true,
    'error_handler' => false,
    'error_level' => 30719,
    'clockwork' => false,
    'inject' => true,
    'route_prefix' => '_debugbar',
    'route_middleware' => 
    array (
    ),
    'route_domain' => NULL,
    'theme' => 'auto',
    'debug_backtrace_limit' => 50,
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\app/private',
        'serve' => false,
        'throw' => false,
        'report' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\app/public',
        'url' => 'http://localhost:8000/storage',
        'visibility' => 'public',
        'throw' => false,
        'report' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
        'report' => false,
      ),
    ),
    'links' => 
    array (
      'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\public\\storage' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\app/public',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\logs/laravel.log',
        'level' => 'debug',
        'max_files' => 14,
        'replace_placeholders' => true,
      ),
      'monthly' => 
      array (
        'driver' => 'monthly',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\logs/laravel.log',
        'level' => 'debug',
        'max_files' => 3,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Prof. Matin Eye Hospital',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'formatter' => NULL,
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\logs/laravel.log',
      ),
      'browser' => 
      array (
        'driver' => 'single',
        'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\logs\\browser.log',
        'level' => 'debug',
        'days' => 14,
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'workspace',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'scheme' => NULL,
        'url' => NULL,
        'host' => 'sandbox.smtp.mailtrap.io',
        'port' => '2525',
        'username' => 'f982af178ffdb8',
        'password' => '0477ca7390e61a',
        'timeout' => NULL,
        'local_domain' => 'localhost',
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
        'retry_after' => 60,
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
      'workspace' => 
      array (
        'transport' => 'workspace',
        'fallback' => 'smtp',
        'fallback_from' => 
        array (
          'address' => 'hello@example.com',
          'name' => 'Prof. Matin Eye Hospital',
        ),
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Prof. Matin Eye Hospital',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\resources\\views/vendor/mail',
      ),
      'extensions' => 
      array (
      ),
    ),
  ),
  'modules' => 
  array (
    'namespace' => 'Modules',
    'vapor_maintenance_mode' => false,
    'stubs' => 
    array (
      'enabled' => true,
      'path' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\stubs/modules',
      'files' => 
      array (
        'routes/web' => 'routes/web.php',
        'composer' => 'composer.json',
      ),
      'replacements' => 
      array (
        'routes/web' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'PLURAL_LOWER_NAME',
          3 => 'KEBAB_NAME',
          4 => 'MODULE_NAMESPACE',
          5 => 'CONTROLLER_NAMESPACE',
        ),
        'routes/api' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'PLURAL_LOWER_NAME',
          3 => 'KEBAB_NAME',
          4 => 'MODULE_NAMESPACE',
          5 => 'CONTROLLER_NAMESPACE',
        ),
        'vite' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'KEBAB_NAME',
        ),
        'json' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'KEBAB_NAME',
          3 => 'MODULE_NAMESPACE',
          4 => 'PROVIDER_NAMESPACE',
        ),
        'views/index' => 
        array (
          0 => 'LOWER_NAME',
        ),
        'views/master' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'KEBAB_NAME',
        ),
        'scaffold/config' => 
        array (
          0 => 'STUDLY_NAME',
        ),
        'composer' => 
        array (
          0 => 'LOWER_NAME',
          1 => 'STUDLY_NAME',
          2 => 'VENDOR',
          3 => 'AUTHOR_NAME',
          4 => 'AUTHOR_EMAIL',
          5 => 'MODULE_NAMESPACE',
          6 => 'PROVIDER_NAMESPACE',
          7 => 'APP_FOLDER_NAME',
        ),
      ),
      'gitkeep' => false,
    ),
    'paths' => 
    array (
      'modules' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules',
      'assets' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\public\\modules',
      'migration' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\database/migrations',
      'app_folder' => 'app/',
      'generator' => 
      array (
        'actions' => 
        array (
          'path' => 'app/Actions',
          'generate' => false,
        ),
        'casts' => 
        array (
          'path' => 'app/Casts',
          'generate' => false,
        ),
        'channels' => 
        array (
          'path' => 'app/Broadcasting',
          'generate' => false,
        ),
        'class' => 
        array (
          'path' => 'app/Classes',
          'generate' => false,
        ),
        'command' => 
        array (
          'path' => 'app/Console',
          'generate' => false,
        ),
        'command_replacements' => 
        array (
          'path' => 'app/Console/Replacements',
          'generate' => false,
        ),
        'component-class' => 
        array (
          'path' => 'app/View/Components',
          'generate' => false,
        ),
        'emails' => 
        array (
          'path' => 'app/Emails',
          'generate' => false,
        ),
        'event' => 
        array (
          'path' => 'app/Events',
          'generate' => false,
        ),
        'event-provider' => 
        array (
          'path' => 'app/Providers',
          'generate' => false,
        ),
        'enums' => 
        array (
          'path' => 'app/Enums',
          'generate' => false,
        ),
        'exceptions' => 
        array (
          'path' => 'app/Exceptions',
          'generate' => false,
        ),
        'jobs' => 
        array (
          'path' => 'app/Jobs',
          'generate' => false,
        ),
        'helpers' => 
        array (
          'path' => 'app/Helpers',
          'generate' => false,
        ),
        'interfaces' => 
        array (
          'path' => 'app/Interfaces',
          'generate' => false,
        ),
        'listener' => 
        array (
          'path' => 'app/Listeners',
          'generate' => false,
        ),
        'model' => 
        array (
          'path' => 'app/Models',
          'generate' => false,
        ),
        'notifications' => 
        array (
          'path' => 'app/Notifications',
          'generate' => false,
        ),
        'observer' => 
        array (
          'path' => 'app/Observers',
          'generate' => false,
        ),
        'policies' => 
        array (
          'path' => 'app/Policies',
          'generate' => false,
        ),
        'provider' => 
        array (
          'path' => 'app/Providers',
          'generate' => true,
        ),
        'repository' => 
        array (
          'path' => 'app/Repositories',
          'generate' => false,
        ),
        'resource' => 
        array (
          'path' => 'app/Transformers',
          'generate' => false,
        ),
        'route-provider' => 
        array (
          'path' => 'app/Providers',
          'generate' => false,
        ),
        'rules' => 
        array (
          'path' => 'app/Rules',
          'generate' => false,
        ),
        'services' => 
        array (
          'path' => 'app/Services',
          'generate' => false,
        ),
        'scopes' => 
        array (
          'path' => 'app/Models/Scopes',
          'generate' => false,
        ),
        'traits' => 
        array (
          'path' => 'app/Traits',
          'generate' => false,
        ),
        'controller' => 
        array (
          'path' => 'app/Http/Controllers',
          'generate' => false,
        ),
        'filter' => 
        array (
          'path' => 'app/Http/Middleware',
          'generate' => false,
        ),
        'request' => 
        array (
          'path' => 'app/Http/Requests',
          'generate' => false,
        ),
        'config' => 
        array (
          'path' => 'config',
          'generate' => false,
        ),
        'factory' => 
        array (
          'path' => 'database/factories',
          'generate' => false,
        ),
        'migration' => 
        array (
          'path' => 'database/migrations',
          'generate' => false,
        ),
        'seeder' => 
        array (
          'path' => 'database/seeders',
          'generate' => false,
        ),
        'lang' => 
        array (
          'path' => 'lang',
          'generate' => false,
        ),
        'assets' => 
        array (
          'path' => 'resources/assets',
          'generate' => false,
        ),
        'component-view' => 
        array (
          'path' => 'resources/views/components',
          'generate' => false,
        ),
        'views' => 
        array (
          'path' => 'resources/views',
          'generate' => false,
        ),
        'inertia' => 
        array (
          'path' => 'resources/js/Pages',
          'generate' => false,
        ),
        'inertia-components' => 
        array (
          'path' => 'resources/js/Components',
          'generate' => false,
        ),
        'routes' => 
        array (
          'path' => 'routes',
          'generate' => true,
        ),
        'test-feature' => 
        array (
          'path' => 'tests/Feature',
          'generate' => false,
        ),
        'test-unit' => 
        array (
          'path' => 'tests/Unit',
          'generate' => false,
        ),
      ),
    ),
    'auto-discover' => 
    array (
      'migrations' => false,
      'translations' => false,
    ),
    'commands' => 
    array (
      0 => 'Nwidart\\Modules\\Commands\\Actions\\CheckLangCommand',
      1 => 'Nwidart\\Modules\\Commands\\Actions\\DisableCommand',
      2 => 'Nwidart\\Modules\\Commands\\Actions\\DumpCommand',
      3 => 'Nwidart\\Modules\\Commands\\Actions\\EnableCommand',
      4 => 'Nwidart\\Modules\\Commands\\Actions\\InstallCommand',
      5 => 'Nwidart\\Modules\\Commands\\Actions\\ListCommand',
      6 => 'Nwidart\\Modules\\Commands\\Actions\\ListCommands',
      7 => 'Nwidart\\Modules\\Commands\\Actions\\ModelPruneCommand',
      8 => 'Nwidart\\Modules\\Commands\\Actions\\ModelShowCommand',
      9 => 'Nwidart\\Modules\\Commands\\Actions\\ModuleDeleteCommand',
      10 => 'Nwidart\\Modules\\Commands\\Actions\\UnUseCommand',
      11 => 'Nwidart\\Modules\\Commands\\Actions\\UpdateCommand',
      12 => 'Nwidart\\Modules\\Commands\\Actions\\UseCommand',
      13 => 'Nwidart\\Modules\\Commands\\Database\\MigrateCommand',
      14 => 'Nwidart\\Modules\\Commands\\Database\\MigrateRefreshCommand',
      15 => 'Nwidart\\Modules\\Commands\\Database\\MigrateResetCommand',
      16 => 'Nwidart\\Modules\\Commands\\Database\\MigrateRollbackCommand',
      17 => 'Nwidart\\Modules\\Commands\\Database\\MigrateStatusCommand',
      18 => 'Nwidart\\Modules\\Commands\\Database\\SeedCommand',
      19 => 'Nwidart\\Modules\\Commands\\Make\\ActionMakeCommand',
      20 => 'Nwidart\\Modules\\Commands\\Make\\CastMakeCommand',
      21 => 'Nwidart\\Modules\\Commands\\Make\\ChannelMakeCommand',
      22 => 'Nwidart\\Modules\\Commands\\Make\\ClassMakeCommand',
      23 => 'Nwidart\\Modules\\Commands\\Make\\CommandMakeCommand',
      24 => 'Nwidart\\Modules\\Commands\\Make\\ComponentClassMakeCommand',
      25 => 'Nwidart\\Modules\\Commands\\Make\\ComponentViewMakeCommand',
      26 => 'Nwidart\\Modules\\Commands\\Make\\ControllerMakeCommand',
      27 => 'Nwidart\\Modules\\Commands\\Make\\EventMakeCommand',
      28 => 'Nwidart\\Modules\\Commands\\Make\\EventProviderMakeCommand',
      29 => 'Nwidart\\Modules\\Commands\\Make\\EnumMakeCommand',
      30 => 'Nwidart\\Modules\\Commands\\Make\\ExceptionMakeCommand',
      31 => 'Nwidart\\Modules\\Commands\\Make\\FactoryMakeCommand',
      32 => 'Nwidart\\Modules\\Commands\\Make\\InterfaceMakeCommand',
      33 => 'Nwidart\\Modules\\Commands\\Make\\HelperMakeCommand',
      34 => 'Nwidart\\Modules\\Commands\\Make\\InertiaComponentMakeCommand',
      35 => 'Nwidart\\Modules\\Commands\\Make\\InertiaPageMakeCommand',
      36 => 'Nwidart\\Modules\\Commands\\Make\\JobMakeCommand',
      37 => 'Nwidart\\Modules\\Commands\\Make\\ListenerMakeCommand',
      38 => 'Nwidart\\Modules\\Commands\\Make\\MailMakeCommand',
      39 => 'Nwidart\\Modules\\Commands\\Make\\MiddlewareMakeCommand',
      40 => 'Nwidart\\Modules\\Commands\\Make\\MigrationMakeCommand',
      41 => 'Nwidart\\Modules\\Commands\\Make\\ModelMakeCommand',
      42 => 'Nwidart\\Modules\\Commands\\Make\\ModuleMakeCommand',
      43 => 'Nwidart\\Modules\\Commands\\Make\\NotificationMakeCommand',
      44 => 'Nwidart\\Modules\\Commands\\Make\\ObserverMakeCommand',
      45 => 'Nwidart\\Modules\\Commands\\Make\\PolicyMakeCommand',
      46 => 'Nwidart\\Modules\\Commands\\Make\\ProviderMakeCommand',
      47 => 'Nwidart\\Modules\\Commands\\Make\\RepositoryMakeCommand',
      48 => 'Nwidart\\Modules\\Commands\\Make\\RequestMakeCommand',
      49 => 'Nwidart\\Modules\\Commands\\Make\\ResourceMakeCommand',
      50 => 'Nwidart\\Modules\\Commands\\Make\\RouteProviderMakeCommand',
      51 => 'Nwidart\\Modules\\Commands\\Make\\RuleMakeCommand',
      52 => 'Nwidart\\Modules\\Commands\\Make\\ReplacementMakeCommand',
      53 => 'Nwidart\\Modules\\Commands\\Make\\ScopeMakeCommand',
      54 => 'Nwidart\\Modules\\Commands\\Make\\SeedMakeCommand',
      55 => 'Nwidart\\Modules\\Commands\\Make\\ServiceMakeCommand',
      56 => 'Nwidart\\Modules\\Commands\\Make\\TraitMakeCommand',
      57 => 'Nwidart\\Modules\\Commands\\Make\\TestMakeCommand',
      58 => 'Nwidart\\Modules\\Commands\\Make\\ViewMakeCommand',
      59 => 'Nwidart\\Modules\\Commands\\Publish\\PublishCommand',
      60 => 'Nwidart\\Modules\\Commands\\Publish\\PublishConfigurationCommand',
      61 => 'Nwidart\\Modules\\Commands\\Publish\\PublishInertiaCommand',
      62 => 'Nwidart\\Modules\\Commands\\Publish\\PublishMigrationCommand',
      63 => 'Nwidart\\Modules\\Commands\\Publish\\PublishTranslationCommand',
      64 => 'Nwidart\\Modules\\Commands\\ComposerUpdateCommand',
      65 => 'Nwidart\\Modules\\Commands\\LaravelModulesV6Migrator',
      66 => 'Nwidart\\Modules\\Commands\\SetupCommand',
      67 => 'Nwidart\\Modules\\Commands\\UpdatePhpunitCoverage',
      68 => 'Nwidart\\Modules\\Commands\\Database\\MigrateFreshCommand',
    ),
    'scan' => 
    array (
      'enabled' => false,
      'paths' => 
      array (
        0 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\vendor/*/*',
      ),
    ),
    'composer' => 
    array (
      'vendor' => 'prof-matin',
      'author' => 
      array (
        'name' => 'Nicolas Widart',
        'email' => 'n.widart@gmail.com',
      ),
      'composer-output' => false,
    ),
    'register' => 
    array (
      'translations' => true,
      'files' => 'register',
    ),
    'activators' => 
    array (
      'file' => 
      array (
        'class' => 'Nwidart\\Modules\\Activators\\FileActivator',
        'statuses-file' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\modules_statuses.json',
      ),
    ),
    'activator' => 'file',
    'inertia' => 
    array (
      'frontend' => 'vue',
    ),
  ),
  'multitenancy' => 
  array (
    'tenant_finder' => NULL,
    'tenant_artisan_search_fields' => 
    array (
      0 => 'id',
      1 => 'slug',
    ),
    'switch_tenant_tasks' => 
    array (
      0 => 'Modules\\Core\\Tasks\\SwitchTenantDatabase',
      1 => 'Modules\\Core\\Tasks\\SwitchTenantSettings',
    ),
    'tenant_model' => 'Modules\\Core\\Models\\Tenant',
    'queues_are_tenant_aware_by_default' => true,
    'tenant_database_connection_name' => 'tenant',
    'landlord_database_connection_name' => 'central',
    'current_tenant_context_key' => 'tenantId',
    'current_tenant_container_key' => 'currentTenant',
    'shared_routes_cache' => false,
    'actions' => 
    array (
      'make_tenant_current_action' => 'Spatie\\Multitenancy\\Actions\\MakeTenantCurrentAction',
      'forget_current_tenant_action' => 'Spatie\\Multitenancy\\Actions\\ForgetCurrentTenantAction',
      'make_queue_tenant_aware_action' => 'Spatie\\Multitenancy\\Actions\\MakeQueueTenantAwareAction',
      'migrate_tenant' => 'Spatie\\Multitenancy\\Actions\\MigrateTenantAction',
    ),
    'queueable_to_job' => 
    array (
      'Illuminate\\Mail\\SendQueuedMailable' => 'mailable',
      'Illuminate\\Notifications\\SendQueuedNotifications' => 'notification',
      'Illuminate\\Queue\\CallQueuedClosure' => 'closure',
      'Illuminate\\Events\\CallQueuedListener' => 'class',
      'Illuminate\\Broadcasting\\BroadcastEvent' => 'event',
    ),
    'tenant_aware_interface' => 'Spatie\\Multitenancy\\Jobs\\TenantAware',
    'not_tenant_aware_interface' => 'Spatie\\Multitenancy\\Jobs\\NotTenantAware',
    'tenant_aware_jobs' => 
    array (
    ),
    'not_tenant_aware_jobs' => 
    array (
    ),
    'central_domains' => 
    array (
      0 => 'localhost',
    ),
    'tenant_base_domain' => 'localhost',
    'migrations' => 
    array (
      'central' => 
      array (
        0 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\database\\migrations',
        1 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Core/database/migrations/shared',
        2 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Core/database/migrations/central',
      ),
      'tenant' => 
      array (
        0 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Core/database/migrations/shared',
        1 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Core/database/migrations/tenant',
        2 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/TenantCore/database/migrations',
        3 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Accounting/database/migrations',
        4 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Hospital/database/migrations',
        5 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Pharmacy/database/migrations',
        6 => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\Modules/Prescription/database/migrations',
      ),
    ),
    'tenant_seeders' => 
    array (
      'Accounting' => 'Modules\\Accounting\\Database\\Seeders\\AccountingDatabaseSeeder',
    ),
  ),
  'pagination' => 
  array (
    'default' => 10,
    'options' => 
    array (
      0 => 5,
      1 => 10,
      2 => 15,
      3 => 20,
      4 => 30,
      5 => 50,
      6 => 100,
      7 => 'all',
    ),
  ),
  'permission' => 
  array (
    'models' => 
    array (
      'permission' => 'Spatie\\Permission\\Models\\Permission',
      'role' => 'Modules\\Core\\Models\\Role',
      'team' => NULL,
      'default_model' => NULL,
    ),
    'table_names' => 
    array (
      'roles' => 'roles',
      'permissions' => 'permissions',
      'model_has_permissions' => 'model_has_permissions',
      'model_has_roles' => 'model_has_roles',
      'role_has_permissions' => 'role_has_permissions',
    ),
    'column_names' => 
    array (
      'role_pivot_key' => NULL,
      'permission_pivot_key' => NULL,
      'model_morph_key' => 'model_id',
      'team_foreign_key' => 'team_id',
    ),
    'register_permission_check_method' => true,
    'register_octane_reset_listener' => false,
    'events_enabled' => true,
    'teams' => false,
    'team_resolver' => 'Spatie\\Permission\\DefaultTeamResolver',
    'use_passport_client_credentials' => false,
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'enable_wildcard_permission' => false,
    'cache' => 
    array (
      'expiration_time' => 
      \DateInterval::__set_state(array(
         'from_string' => true,
         'date_string' => '24 hours',
      )),
      'key' => 'spatie.permission.cache.central',
      'store' => 'default',
    ),
  ),
  'permissions' => 
  array (
    'authenticated_routes' => 
    array (
      0 => 'home',
      1 => 'core.deploy.index',
      2 => 'core.deploy.store',
      3 => 'core.deploy.run',
      4 => 'tenantcore.branch-dashboard.index',
      5 => 'tenantcore.branch-dashboard.store',
      6 => 'tenantcore.branch-dashboard.destroy',
      7 => 'tenantcore.divisions.options',
      8 => 'tenantcore.districts.options',
      9 => 'tenantcore.thanas.options',
      10 => 'tenantcore.income-heads.options',
      11 => 'tenantcore.expense-heads.options',
      12 => 'hospital.investigation-categories.options',
      13 => 'hospital.investigations.options',
      14 => 'hospital.operation-categories.options',
      15 => 'hospital.operations.options',
      16 => 'hospital.departments.options',
      17 => 'hospital.patients.options',
      18 => 'hospital.references.options',
      19 => 'hospital.doctors.options',
      20 => 'pharmacy.categories.options',
      21 => 'pharmacy.dosages.options',
      22 => 'pharmacy.units.options',
      23 => 'pharmacy.shelf.options',
      24 => 'pharmacy.suppliers.options',
      25 => 'pharmacy.products.options',
      26 => 'pharmacy.variants.options',
      27 => 'pharmacy.product-units.options',
    ),
    'platform_routes' => 
    array (
      'core.tenants.index' => 'tenants.view',
      'core.tenants.create' => 'tenants.create',
      'core.tenants.store' => 'tenants.create',
      'core.tenants.edit' => 'tenants.update',
      'core.tenants.update' => 'tenants.update',
      'core.tenants.destroy' => 'tenants.delete',
      'core.tenants.enter' => 'tenants.enter',
      'core.accounts.index' => 'accounts.view',
      'core.accounts.create' => 'accounts.create',
      'core.accounts.store' => 'accounts.create',
      'core.accounts.edit' => 'accounts.update',
      'core.accounts.update' => 'accounts.update',
      'core.accounts.destroy' => 'accounts.delete',
      'core.plans.index' => 'plans.view',
      'core.plans.create' => 'plans.create',
      'core.plans.store' => 'plans.create',
      'core.plans.edit' => 'plans.update',
      'core.plans.update' => 'plans.update',
      'core.plans.destroy' => 'plans.delete',
      'core.plan-permissions.index' => 'plans.view',
      'core.plan-permissions.update' => 'plans.update',
      'core.subscriptions.index' => 'subscriptions.view',
      'core.subscriptions.create' => 'subscriptions.create',
      'core.subscriptions.store' => 'subscriptions.create',
      'core.subscriptions.edit' => 'subscriptions.update',
      'core.subscriptions.update' => 'subscriptions.update',
      'core.invoices.index' => 'invoices.view',
      'core.invoices.show' => 'invoices.view',
      'core.invoices.create' => 'invoices.create',
      'core.invoices.store' => 'invoices.create',
      'core.invoices.edit' => 'invoices.update',
      'core.invoices.update' => 'invoices.update',
      'core.invoices.generate' => 'invoices.create',
      'core.invoices.issue' => 'invoices.issue',
      'core.invoices.void' => 'invoices.void',
      'core.payments.index' => 'payments.view',
      'core.payments.show' => 'payments.view',
      'core.payments.create' => 'payments.create',
      'core.payments.store' => 'payments.create',
      'core.checkouts.approve' => 'payments.create',
      'core.checkouts.reject' => 'payments.create',
      'core.settings.index' => 'settings.view',
      'core.settings.edit' => 'settings.update',
      'core.settings.update' => 'settings.update',
      'core.sms-accounts.index' => 'sms-accounts.view',
      'core.sms-accounts.create' => 'sms-accounts.create',
      'core.sms-accounts.store' => 'sms-accounts.create',
      'core.sms-accounts.edit' => 'sms-accounts.update',
      'core.sms-accounts.update' => 'sms-accounts.update',
      'core.sms-recharges.index' => 'sms-recharges.view',
      'core.sms-recharges.show' => 'sms-recharges.view',
      'core.sms-recharges.update' => 'sms-recharges.update',
      'core.sms-history.index' => 'sms-history.view',
      'core.sms-history.show' => 'sms-history.view',
      'core.sms-history.resolve' => 'sms-history.update',
    ),
    'tenant_permissions' => 
    array (
      0 => 'hospital.view',
      1 => 'pharmacy-payments.discount',
    ),
    'tenant_route_requirements' => 
    array (
      'hospital.doctors.permissions-update' => 
      array (
        0 => 'doctor-permissions.view',
      ),
      'pharmacy.customers.store' => 
      array (
        0 => 'pharmacy-sales.create',
      ),
    ),
    'tenant_route_alternatives' => 
    array (
      'core.sms-recharges.proof' => 
      array (
        0 => 'sms-recharges.view',
        1 => 'sms-recharges.update',
      ),
      'tenantcore.sms-recharges.proof' => 
      array (
        0 => 'sms.view',
        1 => 'sms-recharges.create',
      ),
      'pharmacy.lots.options' => 
      array (
        0 => 'pharmacy-damage.create',
        1 => 'pharmacy-adjustments.create',
      ),
      'pharmacy.purchase-items.options' => 
      array (
        0 => 'pharmacy-purchases.view',
        1 => 'pharmacy-purchases.create',
        2 => 'pharmacy-purchases.update',
      ),
      'pharmacy.sale-payments.customers' => 
      array (
        0 => 'pharmacy-payments.create',
        1 => 'pharmacy-reports.view',
      ),
      'pharmacy.purchase-payments.suppliers' => 
      array (
        0 => 'pharmacy-payments.create',
        1 => 'pharmacy-reports.view',
      ),
    ),
    'tenant_routes' => 
    array (
      'tenantcore.module-dashboard.index' => 'module-dashboard.view',
      'tenantcore.dashboard' => 'tenant-dashboard.view',
      'tenantcore.reports.daily' => 'reports-daily.view',
      'tenantcore.reports.cash' => 'reports-cash.view',
      'tenantcore.reports.monthly-cash' => 'reports-monthly-cash.view',
      'hospital.reports.cash-balance' => 'hospital-cash-report.view',
      'hospital.reports.patient-due' => 'hospital-patient-due.view',
      'pharmacy.reports.cash-balance' => 'pharmacy-cash-report.view',
      'accounting.dashboard' => 'accounting-dashboard.view',
      'accounting.ledgers.options' => 'accounting.view',
      'accounting.reports.ledger' => 'accounting-reports.view',
      'accounting.reports.trial-balance' => 'accounting-reports.view',
      'accounting.reports.income-expense' => 'accounting-reports.view',
      'accounting.reports.balance-sheet' => 'accounting-reports.view',
      'accounting.reports.cash-flow' => 'accounting-reports.view',
      'accounting.reports.cash-bank-book' => 'accounting-reports.view',
      'accounting.reports.changes-in-equity' => 'accounting-reports.view',
      'accounting.reports.receipts-payments' => 'accounting-reports.view',
      'accounting.chart-of-accounts.index' => 'accounting-accounts.view',
      'accounting.chart-of-accounts.create' => 'accounting-accounts.create',
      'accounting.chart-of-accounts.store' => 'accounting-accounts.create',
      'accounting.chart-of-accounts.edit' => 'accounting-accounts.update',
      'accounting.chart-of-accounts.update' => 'accounting-accounts.update',
      'accounting.chart-of-accounts.destroy' => 'accounting-accounts.delete',
      'accounting.account-modules.index' => 'accounting-mappings.view',
      'accounting.account-modules.edit' => 'accounting-mappings.update',
      'accounting.account-modules.update' => 'accounting-mappings.update',
      'accounting.payment-accounts.index' => 'accounting-mappings.view',
      'accounting.payment-accounts.create' => 'accounting-mappings.create',
      'accounting.payment-accounts.store' => 'accounting-mappings.create',
      'accounting.payment-accounts.edit' => 'accounting-mappings.update',
      'accounting.payment-accounts.update' => 'accounting-mappings.update',
      'accounting.payment-accounts.destroy' => 'accounting-mappings.delete',
      'accounting.journal-entries.index' => 'accounting-journals.view',
      'accounting.journal-entries.show' => 'accounting-journals.view',
      'accounting.journal-entries.create' => 'accounting-journals.create',
      'accounting.journal-entries.store' => 'accounting-journals.create',
      'accounting.journal-entries.edit' => 'accounting-journals.update',
      'accounting.journal-entries.update' => 'accounting-journals.update',
      'accounting.journal-entries.destroy' => 'accounting-journals.delete',
      'tenantcore.billing.index' => 'billing.view',
      'tenantcore.billing.show' => 'billing.view',
      'tenantcore.billing.checkouts.store' => 'billing.pay',
      'tenantcore.audit-logs.branches' => 'audit-logs.view',
      'tenantcore.sms.index' => 'sms.view',
      'tenantcore.sms-recharges.index' => 'sms.view',
      'tenantcore.sms-recharges.create' => 'sms-recharges.create',
      'tenantcore.sms-recharges.store' => 'sms-recharges.create',
      'prescription.dashboard' => 'prescription-dashboard.view',
      'prescription.prescriptions.index' => 'prescriptions.view',
      'prescription.prescriptions.show' => 'prescriptions.view',
      'prescription.prescriptions.create' => 'prescriptions.create',
      'prescription.prescriptions.store' => 'prescriptions.create',
      'prescription.prescriptions.edit' => 'prescriptions.update',
      'prescription.prescriptions.update' => 'prescriptions.update',
      'prescription.prescriptions.destroy' => 'prescriptions.delete',
      'prescription.patients.modal' => 'prescriptions.create',
      'prescription.patients.store' => 'prescriptions.create',
      'prescription.glass-patients.modal' => 'glass-prescriptions.create',
      'prescription.glass-patients.store' => 'glass-prescriptions.create',
      'prescription.options.selector' => 'prescription.view',
      'prescription.options.index' => 'prescription-options.view',
      'prescription.options.create' => 'prescription-options.create',
      'prescription.options.store' => 'prescription-options.create',
      'prescription.options.edit' => 'prescription-options.update',
      'prescription.options.update' => 'prescription-options.update',
      'prescription.options.destroy' => 'prescription-options.delete',
      'prescription.glass-prescriptions.index' => 'glass-prescriptions.view',
      'prescription.glass-prescriptions.show' => 'glass-prescriptions.view',
      'prescription.glass-prescriptions.create' => 'glass-prescriptions.create',
      'prescription.glass-prescriptions.store' => 'glass-prescriptions.create',
      'prescription.glass-prescriptions.edit' => 'glass-prescriptions.update',
      'prescription.glass-prescriptions.update' => 'glass-prescriptions.update',
      'prescription.glass-prescriptions.destroy' => 'glass-prescriptions.delete',
      'prescription.settings.index' => 'settings.view',
      'prescription.settings.update' => 'settings.update',
      'hospital.settings.index' => 'settings.view',
      'hospital.settings.update' => 'settings.update',
      'pharmacy.settings.index' => 'settings.view',
      'pharmacy.settings.update' => 'settings.update',
      'pharmacy.dashboard' => 'pharmacy-dashboard.view',
      'pharmacy.items.options' => 'pharmacy.view',
      'pharmacy.reports.products' => 'pharmacy-reports.view',
      'pharmacy.lots.options' => 'pharmacy.view',
      'pharmacy.purchase-sources.options' => 'pharmacy-purchase-returns.create',
      'pharmacy.sale-sources.options' => 'pharmacy-sale-returns.create',
      'pharmacy.customers.options' => 'pharmacy.view',
      'pharmacy.sale-payments.invoices' => 'pharmacy-payments.create',
      'pharmacy.sale-payments.customers' => 'pharmacy.view',
      'pharmacy.purchase-payments.invoices' => 'pharmacy-payments.create',
      'pharmacy.purchase-payments.suppliers' => 'pharmacy.view',
      'pharmacy.categories.index' => 'pharmacy-categories.view',
      'pharmacy.categories.create' => 'pharmacy-categories.create',
      'pharmacy.categories.store' => 'pharmacy-categories.create',
      'pharmacy.categories.edit' => 'pharmacy-categories.update',
      'pharmacy.categories.update' => 'pharmacy-categories.update',
      'pharmacy.categories.destroy' => 'pharmacy-categories.delete',
      'pharmacy.dosages.index' => 'pharmacy-dosages.view',
      'pharmacy.dosages.create' => 'pharmacy-dosages.create',
      'pharmacy.dosages.store' => 'pharmacy-dosages.create',
      'pharmacy.dosages.edit' => 'pharmacy-dosages.update',
      'pharmacy.dosages.update' => 'pharmacy-dosages.update',
      'pharmacy.dosages.destroy' => 'pharmacy-dosages.delete',
      'pharmacy.shelf.index' => 'pharmacy-shelf.view',
      'pharmacy.shelf.create' => 'pharmacy-shelf.create',
      'pharmacy.shelf.store' => 'pharmacy-shelf.create',
      'pharmacy.shelf.edit' => 'pharmacy-shelf.update',
      'pharmacy.shelf.update' => 'pharmacy-shelf.update',
      'pharmacy.shelf.destroy' => 'pharmacy-shelf.delete',
      'pharmacy.units.index' => 'pharmacy-units.view',
      'pharmacy.units.create' => 'pharmacy-units.create',
      'pharmacy.units.store' => 'pharmacy-units.create',
      'pharmacy.units.edit' => 'pharmacy-units.update',
      'pharmacy.units.update' => 'pharmacy-units.update',
      'pharmacy.units.destroy' => 'pharmacy-units.delete',
      'pharmacy.suppliers.index' => 'pharmacy-suppliers.view',
      'pharmacy.suppliers.create' => 'pharmacy-suppliers.create',
      'pharmacy.suppliers.store' => 'pharmacy-suppliers.create',
      'pharmacy.suppliers.edit' => 'pharmacy-suppliers.update',
      'pharmacy.suppliers.update' => 'pharmacy-suppliers.update',
      'pharmacy.suppliers.destroy' => 'pharmacy-suppliers.delete',
      'pharmacy.products.index' => 'pharmacy-products.view',
      'pharmacy.products.create' => 'pharmacy-products.create',
      'pharmacy.products.store' => 'pharmacy-products.create',
      'pharmacy.products.edit' => 'pharmacy-products.update',
      'pharmacy.products.update' => 'pharmacy-products.update',
      'pharmacy.products.destroy' => 'pharmacy-products.delete',
      'pharmacy.variants.index' => 'pharmacy-products.view',
      'pharmacy.variants.create' => 'pharmacy-products.create',
      'pharmacy.variants.store' => 'pharmacy-products.create',
      'pharmacy.variants.edit' => 'pharmacy-products.update',
      'pharmacy.variants.update' => 'pharmacy-products.update',
      'pharmacy.variants.destroy' => 'pharmacy-products.delete',
      'pharmacy.product-units.index' => 'pharmacy-products.view',
      'pharmacy.product-units.create' => 'pharmacy-products.create',
      'pharmacy.product-units.store' => 'pharmacy-products.create',
      'pharmacy.product-units.edit' => 'pharmacy-products.update',
      'pharmacy.product-units.update' => 'pharmacy-products.update',
      'pharmacy.product-units.destroy' => 'pharmacy-products.delete',
      'pharmacy.purchases.index' => 'pharmacy-purchases.view',
      'pharmacy.purchases.create' => 'pharmacy-purchases.create',
      'pharmacy.purchases.store' => 'pharmacy-purchases.create',
      'pharmacy.purchases.show' => 'pharmacy-purchases.view',
      'pharmacy.purchases.edit' => 'pharmacy-purchases.update',
      'pharmacy.purchases.update' => 'pharmacy-purchases.update',
      'pharmacy.purchases.destroy' => 'pharmacy-purchases.delete',
      'pharmacy.purchases.void' => 'pharmacy-purchases.void',
      'pharmacy.sales.index' => 'pharmacy-sales.view',
      'pharmacy.sales.create' => 'pharmacy-sales.create',
      'pharmacy.sales.store' => 'pharmacy-sales.create',
      'pharmacy.sales.show' => 'pharmacy-sales.view',
      'pharmacy.sales.edit' => 'pharmacy-sales.update',
      'pharmacy.sales.update' => 'pharmacy-sales.update',
      'pharmacy.sales.destroy' => 'pharmacy-sales.delete',
      'pharmacy.sales.void' => 'pharmacy-sales.void',
      'pharmacy.purchase-returns.index' => 'pharmacy-purchase-returns.view',
      'pharmacy.purchase-returns.create' => 'pharmacy-purchase-returns.create',
      'pharmacy.purchase-returns.store' => 'pharmacy-purchase-returns.create',
      'pharmacy.purchase-returns.show' => 'pharmacy-purchase-returns.view',
      'pharmacy.purchase-returns.edit' => 'pharmacy-purchase-returns.update',
      'pharmacy.purchase-returns.update' => 'pharmacy-purchase-returns.update',
      'pharmacy.purchase-returns.destroy' => 'pharmacy-purchase-returns.delete',
      'pharmacy.purchase-returns.void' => 'pharmacy-purchase-returns.void',
      'pharmacy.sale-returns.index' => 'pharmacy-sale-returns.view',
      'pharmacy.sale-returns.create' => 'pharmacy-sale-returns.create',
      'pharmacy.sale-returns.store' => 'pharmacy-sale-returns.create',
      'pharmacy.sale-returns.show' => 'pharmacy-sale-returns.view',
      'pharmacy.sale-returns.edit' => 'pharmacy-sale-returns.update',
      'pharmacy.sale-returns.update' => 'pharmacy-sale-returns.update',
      'pharmacy.sale-returns.destroy' => 'pharmacy-sale-returns.delete',
      'pharmacy.sale-returns.void' => 'pharmacy-sale-returns.void',
      'pharmacy.opening-stock.index' => 'pharmacy-opening-stock.view',
      'pharmacy.opening-stock.create' => 'pharmacy-opening-stock.create',
      'pharmacy.opening-stock.store' => 'pharmacy-opening-stock.create',
      'pharmacy.opening-stock.show' => 'pharmacy-opening-stock.view',
      'pharmacy.opening-stock.void' => 'pharmacy-opening-stock.void',
      'pharmacy.adjustments.index' => 'pharmacy-adjustments.view',
      'pharmacy.adjustments.create' => 'pharmacy-adjustments.create',
      'pharmacy.adjustments.store' => 'pharmacy-adjustments.create',
      'pharmacy.adjustments.show' => 'pharmacy-adjustments.view',
      'pharmacy.adjustments.void' => 'pharmacy-adjustments.void',
      'pharmacy.damage.index' => 'pharmacy-damage.view',
      'pharmacy.damage.create' => 'pharmacy-damage.create',
      'pharmacy.damage.store' => 'pharmacy-damage.create',
      'pharmacy.damage.show' => 'pharmacy-damage.view',
      'pharmacy.damage.void' => 'pharmacy-damage.void',
      'pharmacy.transfers.index' => 'pharmacy-transfers.view',
      'pharmacy.transfers.create' => 'pharmacy-transfers.create',
      'pharmacy.transfers.store' => 'pharmacy-transfers.create',
      'pharmacy.transfers.show' => 'pharmacy-transfers.view',
      'pharmacy.transfers.void' => 'pharmacy-transfers.void',
      'pharmacy.sale-payments.index' => 'pharmacy-payments.view',
      'pharmacy.sale-payments.create' => 'pharmacy-payments.create',
      'pharmacy.sale-payments.store' => 'pharmacy-payments.create',
      'pharmacy.sale-payments.show' => 'pharmacy-payments.view',
      'pharmacy.sale-payments.void' => 'pharmacy-payments.void',
      'pharmacy.sale-payments.destroy' => 'pharmacy-payments.delete',
      'pharmacy.purchase-payments.index' => 'pharmacy-payments.view',
      'pharmacy.purchase-payments.create' => 'pharmacy-payments.create',
      'pharmacy.purchase-payments.store' => 'pharmacy-payments.create',
      'pharmacy.purchase-payments.show' => 'pharmacy-payments.view',
      'pharmacy.purchase-payments.void' => 'pharmacy-payments.void',
      'pharmacy.purchase-payments.destroy' => 'pharmacy-payments.delete',
      'pharmacy.customers.index' => 'pharmacy-customers.view',
      'pharmacy.customers.store' => 'pharmacy-customers.create',
      'pharmacy.reports.index' => 'pharmacy-reports.view',
      'pharmacy.reports.stock' => 'pharmacy-reports.view',
      'pharmacy.reports.movements' => 'pharmacy-reports.view',
      'pharmacy.reports.expiry' => 'pharmacy-reports.view',
      'pharmacy.reports.profit-loss' => 'pharmacy-reports.view',
      'pharmacy.reports.purchase-discounts' => 'pharmacy-reports.view',
      'pharmacy.reports.daily' => 'pharmacy-reports.view',
      'pharmacy.reports.customer-dues' => 'pharmacy-reports.view',
      'pharmacy.reports.supplier-dues' => 'pharmacy-reports.view',
      'hospital.dashboard' => 'hospital-dashboard.view',
      'hospital.investigations.index' => 'investigations.view',
      'hospital.investigations.create' => 'investigations.create',
      'hospital.investigations.store' => 'investigations.create',
      'hospital.investigations.edit' => 'investigations.update',
      'hospital.investigations.update' => 'investigations.update',
      'hospital.investigations.destroy' => 'investigations.delete',
      'hospital.investigation-categories.index' => 'investigation-categories.view',
      'hospital.investigation-categories.create' => 'investigation-categories.create',
      'hospital.investigation-categories.store' => 'investigation-categories.create',
      'hospital.investigation-categories.edit' => 'investigation-categories.update',
      'hospital.investigation-categories.update' => 'investigation-categories.update',
      'hospital.investigation-categories.destroy' => 'investigation-categories.delete',
      'hospital.operation-categories.index' => 'operation-categories.view',
      'hospital.operation-categories.create' => 'operation-categories.create',
      'hospital.operation-categories.store' => 'operation-categories.create',
      'hospital.operation-categories.edit' => 'operation-categories.update',
      'hospital.operation-categories.update' => 'operation-categories.update',
      'hospital.operation-categories.destroy' => 'operation-categories.delete',
      'hospital.operations.index' => 'operations.view',
      'hospital.operations.create' => 'operations.create',
      'hospital.operations.store' => 'operations.create',
      'hospital.operations.edit' => 'operations.update',
      'hospital.operations.update' => 'operations.update',
      'hospital.operations.destroy' => 'operations.delete',
      'hospital.patients.index' => 'patients.view',
      'hospital.patients.create' => 'patients.create',
      'hospital.patients.store' => 'patients.create',
      'hospital.patients.edit' => 'patients.update',
      'hospital.patients.update' => 'patients.update',
      'hospital.patients.destroy' => 'patients.delete',
      'hospital.references.index' => 'references.view',
      'hospital.references.create' => 'references.create',
      'hospital.references.store' => 'references.create',
      'hospital.references.edit' => 'references.update',
      'hospital.references.update' => 'references.update',
      'hospital.references.destroy' => 'references.delete',
      'hospital.departments.index' => 'departments.view',
      'hospital.departments.create' => 'departments.create',
      'hospital.departments.store' => 'departments.create',
      'hospital.departments.edit' => 'departments.update',
      'hospital.departments.update' => 'departments.update',
      'hospital.departments.destroy' => 'departments.delete',
      'hospital.doctors.index' => 'doctors.view',
      'hospital.doctors.show' => 'doctors.view',
      'hospital.doctors.create' => 'doctors.create',
      'hospital.doctors.store' => 'doctors.create',
      'hospital.doctors.edit' => 'doctors.update',
      'hospital.doctors.update' => 'doctors.update',
      'hospital.doctors.destroy' => 'doctors.delete',
      'hospital.doctors.permissions' => 'doctor-permissions.view',
      'hospital.doctors.permissions-update' => 'doctor-permissions.manage',
      'hospital.appointments.index' => 'appointments.view',
      'hospital.appointments.create' => 'appointments.create',
      'hospital.appointments.store' => 'appointments.create',
      'hospital.appointments.edit' => 'appointments.update',
      'hospital.appointments.update' => 'appointments.update',
      'hospital.appointments.destroy' => 'appointments.delete',
      'hospital.consultations.index' => 'consultations.view',
      'hospital.consultations.show' => 'consultations.view',
      'hospital.consultations.create' => 'consultations.create',
      'hospital.consultations.store' => 'consultations.create',
      'hospital.consultations.edit' => 'consultations.update',
      'hospital.consultations.update' => 'consultations.update',
      'hospital.consultations.destroy' => 'consultations.delete',
      'hospital.patient-investigations.index' => 'patient-investigations.view',
      'hospital.patient-investigations.show' => 'patient-investigations.view',
      'hospital.patient-investigations.create' => 'patient-investigations.create',
      'hospital.patient-investigations.store' => 'patient-investigations.create',
      'hospital.patient-investigations.edit' => 'patient-investigations.update',
      'hospital.patient-investigations.update' => 'patient-investigations.update',
      'hospital.patient-investigations.destroy' => 'patient-investigations.delete',
      'hospital.delivery-investigations.index' => 'delivery-investigations.view',
      'hospital.delivery-investigations.update' => 'delivery-investigations.update',
      'hospital.patient-operations.index' => 'patient-operations.view',
      'hospital.patient-operations.show' => 'patient-operations.view',
      'hospital.patient-operations.create' => 'patient-operations.create',
      'hospital.patient-operations.store' => 'patient-operations.create',
      'hospital.patient-operations.edit' => 'patient-operations.update',
      'hospital.patient-operations.update' => 'patient-operations.update',
      'hospital.patient-operations.destroy' => 'patient-operations.delete',
      'hospital.commissions.index' => 'commissions.view',
      'hospital.commissions.update' => 'commissions.update',
      'hospital.refunds.index' => 'refunds.view',
      'hospital.refunds.show' => 'refunds.view',
      'hospital.refunds.create' => 'refunds.create',
      'hospital.refunds.store' => 'refunds.create',
      'hospital.refunds.edit' => 'refunds.update',
      'hospital.refunds.update' => 'refunds.update',
      'hospital.refunds.destroy' => 'refunds.delete',
      'hospital.refunds.invoices' => 'refunds.create',
      'hospital.refunds.invoice' => 'refunds.create',
      'hospital.due-collections.index' => 'due-collections.view',
      'hospital.due-collections.show' => 'due-collections.view',
      'hospital.due-collections.create' => 'due-collections.create',
      'hospital.due-collections.store' => 'due-collections.create',
      'hospital.due-collections.edit' => 'due-collections.update',
      'hospital.due-collections.update' => 'due-collections.update',
      'hospital.due-collections.destroy' => 'due-collections.delete',
      'hospital.due-collections.invoices' => 'due-collections.create',
      'hospital.due-collections.invoice' => 'due-collections.create',
      'tenantcore.payment-types.index' => 'payment-types.view',
      'tenantcore.payment-types.create' => 'payment-types.create',
      'tenantcore.payment-types.store' => 'payment-types.create',
      'tenantcore.payment-types.edit' => 'payment-types.update',
      'tenantcore.payment-types.update' => 'payment-types.update',
      'tenantcore.payment-types.destroy' => 'payment-types.delete',
      'tenantcore.income-heads.index' => 'income-heads.view',
      'tenantcore.income-heads.create' => 'income-heads.create',
      'tenantcore.income-heads.store' => 'income-heads.create',
      'tenantcore.income-heads.edit' => 'income-heads.update',
      'tenantcore.income-heads.update' => 'income-heads.update',
      'tenantcore.income-heads.destroy' => 'income-heads.delete',
      'tenantcore.expense-heads.index' => 'expense-heads.view',
      'tenantcore.expense-heads.create' => 'expense-heads.create',
      'tenantcore.expense-heads.store' => 'expense-heads.create',
      'tenantcore.expense-heads.edit' => 'expense-heads.update',
      'tenantcore.expense-heads.update' => 'expense-heads.update',
      'tenantcore.expense-heads.destroy' => 'expense-heads.delete',
      'tenantcore.incomes.index' => 'incomes.view',
      'tenantcore.incomes.create' => 'incomes.create',
      'tenantcore.incomes.store' => 'incomes.create',
      'tenantcore.incomes.edit' => 'incomes.update',
      'tenantcore.incomes.update' => 'incomes.update',
      'tenantcore.incomes.destroy' => 'incomes.delete',
      'tenantcore.expenses.index' => 'expenses.view',
      'tenantcore.expenses.create' => 'expenses.create',
      'tenantcore.expenses.store' => 'expenses.create',
      'tenantcore.expenses.edit' => 'expenses.update',
      'tenantcore.expenses.update' => 'expenses.update',
      'tenantcore.expenses.destroy' => 'expenses.delete',
      'hospital.refund-causes.index' => 'refund-causes.view',
      'hospital.refund-causes.create' => 'refund-causes.create',
      'hospital.refund-causes.store' => 'refund-causes.create',
      'hospital.refund-causes.edit' => 'refund-causes.update',
      'hospital.refund-causes.update' => 'refund-causes.update',
      'hospital.refund-causes.destroy' => 'refund-causes.delete',
      'hospital.patients.modal' => 'patients.create',
      'tenantcore.branches.index' => 'branches.view',
      'tenantcore.audit-logs.index' => 'audit-logs.view',
      'tenantcore.audit-logs.show' => 'audit-logs.view',
      'tenantcore.branches.create' => 'branches.create',
      'tenantcore.branches.store' => 'branches.create',
      'tenantcore.branches.edit' => 'branches.update',
      'tenantcore.branches.update' => 'branches.update',
      'tenantcore.branches.destroy' => 'branches.delete',
      'tenantcore.divisions.index' => 'divisions.view',
      'tenantcore.divisions.create' => 'divisions.create',
      'tenantcore.divisions.store' => 'divisions.create',
      'tenantcore.divisions.edit' => 'divisions.update',
      'tenantcore.divisions.update' => 'divisions.update',
      'tenantcore.divisions.destroy' => 'divisions.delete',
      'tenantcore.districts.index' => 'districts.view',
      'tenantcore.districts.create' => 'districts.create',
      'tenantcore.districts.store' => 'districts.create',
      'tenantcore.districts.edit' => 'districts.update',
      'tenantcore.districts.update' => 'districts.update',
      'tenantcore.districts.destroy' => 'districts.delete',
      'tenantcore.thanas.index' => 'thanas.view',
      'tenantcore.thanas.create' => 'thanas.create',
      'tenantcore.thanas.store' => 'thanas.create',
      'tenantcore.thanas.edit' => 'thanas.update',
      'tenantcore.thanas.update' => 'thanas.update',
      'tenantcore.thanas.destroy' => 'thanas.delete',
      'tenantcore.accounts.index' => 'accounts.view',
      'tenantcore.accounts.create' => 'accounts.create',
      'tenantcore.accounts.store' => 'accounts.create',
      'tenantcore.accounts.edit' => 'accounts.update',
      'tenantcore.accounts.update' => 'accounts.update',
      'tenantcore.accounts.destroy' => 'accounts.delete',
      'core.roles.index' => 'roles.view',
      'core.roles.create' => 'roles.create',
      'core.roles.store' => 'roles.create',
      'core.roles.edit' => 'roles.update',
      'core.roles.update' => 'roles.update',
      'core.roles.destroy' => 'roles.delete',
      'core.permissions.index' => 'permissions.view',
      'core.permissions.update' => 'permissions.manage',
      'tenantcore.settings.index' => 'settings.view',
      'tenantcore.settings.edit' => 'settings.update',
      'tenantcore.settings.update' => 'settings.update',
    ),
    'groups' => 
    array (
      'module-dashboard' => 
      array (
        'label' => 'Module dashboard',
        'area' => 'Administration',
      ),
      'tenant-dashboard' => 
      array (
        'label' => 'Workspace dashboard',
        'area' => 'Administration',
      ),
      'reports-daily' => 
      array (
        'label' => 'Income / Expense Report',
        'area' => 'Administration',
      ),
      'reports-cash' => 
      array (
        'label' => 'Cash Report',
        'area' => 'Administration',
      ),
      'reports-monthly-cash' => 
      array (
        'label' => 'Report',
        'group_key' => 'reports',
        'permission_label' => 'Monthly Cash Report',
        'area' => 'Hospital',
      ),
      'hospital-cash-report' => 
      array (
        'label' => 'Report',
        'group_key' => 'reports',
        'permission_label' => 'Cash and Due Balance',
        'area' => 'Hospital',
      ),
      'hospital-patient-due' => 
      array (
        'label' => 'Report',
        'group_key' => 'reports',
        'permission_label' => 'Patient Due',
        'area' => 'Hospital',
      ),
      'pharmacy-cash-report' => 
      array (
        'label' => 'Cash and Due Balance',
        'area' => 'Pharmacy',
      ),
      'accounting' => 
      array (
        'label' => 'Accounting',
        'area' => 'Accounting',
      ),
      'accounting-dashboard' => 
      array (
        'label' => 'Accounting dashboard',
        'area' => 'Accounting',
      ),
      'accounting-accounts' => 
      array (
        'label' => 'Chart of accounts',
        'area' => 'Accounting',
      ),
      'accounting-journals' => 
      array (
        'label' => 'Vouchers',
        'area' => 'Accounting',
      ),
      'accounting-mappings' => 
      array (
        'label' => 'Account mappings',
        'area' => 'Accounting',
      ),
      'accounting-reports' => 
      array (
        'label' => 'Accounting reports',
        'area' => 'Accounting',
      ),
      'prescription' => 
      array (
        'label' => 'Prescription workspace',
        'area' => 'Prescription',
      ),
      'prescription-dashboard' => 
      array (
        'label' => 'Prescription dashboard',
        'area' => 'Prescription',
      ),
      'prescriptions' => 
      array (
        'label' => 'General prescriptions',
        'area' => 'Prescription',
      ),
      'prescription-options' => 
      array (
        'label' => 'Prescription options',
        'area' => 'Prescription',
      ),
      'glass-prescriptions' => 
      array (
        'label' => 'Glass prescriptions',
        'area' => 'Prescription',
      ),
      'pharmacy' => 
      array (
        'label' => 'Pharmacy',
        'area' => 'Pharmacy',
      ),
      'pharmacy-dashboard' => 
      array (
        'label' => 'Pharmacy dashboard',
        'area' => 'Pharmacy',
      ),
      'pharmacy-categories' => 
      array (
        'label' => 'Categories / Generics',
        'area' => 'Pharmacy',
      ),
      'pharmacy-dosages' => 
      array (
        'label' => 'Dosages',
        'area' => 'Pharmacy',
      ),
      'pharmacy-shelf' => 
      array (
        'label' => 'Shelf',
        'area' => 'Pharmacy',
      ),
      'pharmacy-units' => 
      array (
        'label' => 'Units',
        'area' => 'Pharmacy',
      ),
      'pharmacy-suppliers' => 
      array (
        'label' => 'Suppliers',
        'area' => 'Pharmacy',
      ),
      'pharmacy-products' => 
      array (
        'label' => 'Products and variants',
        'area' => 'Pharmacy',
      ),
      'pharmacy-purchases' => 
      array (
        'label' => 'Purchases',
        'area' => 'Pharmacy',
      ),
      'pharmacy-sales' => 
      array (
        'label' => 'Sales',
        'area' => 'Pharmacy',
      ),
      'pharmacy-purchase-returns' => 
      array (
        'label' => 'Purchase returns',
        'area' => 'Pharmacy',
      ),
      'pharmacy-sale-returns' => 
      array (
        'label' => 'Sales returns',
        'area' => 'Pharmacy',
      ),
      'pharmacy-opening-stock' => 
      array (
        'label' => 'Opening stock',
        'area' => 'Pharmacy',
      ),
      'pharmacy-adjustments' => 
      array (
        'label' => 'Stock adjustments',
        'area' => 'Pharmacy',
      ),
      'pharmacy-damage' => 
      array (
        'label' => 'Damage / wastage',
        'area' => 'Pharmacy',
      ),
      'pharmacy-transfers' => 
      array (
        'label' => 'Branch transfers',
        'area' => 'Pharmacy',
      ),
      'pharmacy-payments' => 
      array (
        'label' => 'Customer / supplier payments',
        'area' => 'Pharmacy',
      ),
      'pharmacy-customers' => 
      array (
        'label' => 'Customers',
        'area' => 'Pharmacy',
      ),
      'pharmacy-reports' => 
      array (
        'label' => 'Pharmacy reports',
        'area' => 'Pharmacy',
      ),
      'hospital' => 
      array (
        'label' => 'Hospital',
        'area' => 'Hospital',
      ),
      'hospital-dashboard' => 
      array (
        'label' => 'Hospital dashboard',
        'area' => 'Hospital',
      ),
      'investigation-categories' => 
      array (
        'label' => 'Investigation Categories',
        'area' => 'Hospital',
      ),
      'investigations' => 
      array (
        'label' => 'Investigations',
        'area' => 'Hospital',
      ),
      'operation-categories' => 
      array (
        'label' => 'Operation Categories',
        'area' => 'Hospital',
      ),
      'operations' => 
      array (
        'label' => 'Operations',
        'area' => 'Hospital',
      ),
      'patients' => 
      array (
        'label' => 'Patients',
        'area' => 'Hospital',
      ),
      'references' => 
      array (
        'label' => 'References',
        'area' => 'Hospital',
      ),
      'departments' => 
      array (
        'label' => 'Doctor Departments',
        'area' => 'Hospital',
      ),
      'doctors' => 
      array (
        'label' => 'Doctors',
        'area' => 'Hospital',
      ),
      'doctor-permissions' => 
      array (
        'label' => 'Doctor Permissions',
        'area' => 'Hospital',
      ),
      'appointments' => 
      array (
        'label' => 'Appointments',
        'area' => 'Hospital',
      ),
      'consultations' => 
      array (
        'label' => 'Consultations',
        'area' => 'Hospital',
      ),
      'patient-investigations' => 
      array (
        'label' => 'Patient Investigations',
        'area' => 'Hospital',
      ),
      'delivery-investigations' => 
      array (
        'label' => 'Delivery Investigations',
        'area' => 'Hospital',
      ),
      'patient-operations' => 
      array (
        'label' => 'Patient Operations',
        'area' => 'Hospital',
      ),
      'commissions' => 
      array (
        'label' => 'Commissions',
        'area' => 'Hospital',
      ),
      'refunds' => 
      array (
        'label' => 'Refunds',
        'area' => 'Hospital',
      ),
      'due-collections' => 
      array (
        'label' => 'Due Collections',
        'area' => 'Hospital',
      ),
      'payment-types' => 
      array (
        'label' => 'Payment Types',
        'area' => 'Administration',
      ),
      'income-heads' => 
      array (
        'label' => 'Income Heads',
        'area' => 'Administration',
      ),
      'expense-heads' => 
      array (
        'label' => 'Expense Heads',
        'area' => 'Administration',
      ),
      'incomes' => 
      array (
        'label' => 'Income',
        'area' => 'Administration',
      ),
      'expenses' => 
      array (
        'label' => 'Expenses',
        'area' => 'Administration',
      ),
      'refund-causes' => 
      array (
        'label' => 'Refund Causes',
        'area' => 'Hospital',
      ),
      'branches' => 
      array (
        'label' => 'Branches',
        'area' => 'Administration',
      ),
      'audit-logs' => 
      array (
        'label' => 'Audit Logs',
        'area' => 'Administration',
      ),
      'divisions' => 
      array (
        'label' => 'Divisions',
        'area' => 'Administration',
      ),
      'districts' => 
      array (
        'label' => 'Districts',
        'area' => 'Administration',
      ),
      'thanas' => 
      array (
        'label' => 'Thanas',
        'area' => 'Administration',
      ),
      'accounts' => 
      array (
        'label' => 'Accounts',
        'area' => 'Access control',
      ),
      'roles' => 
      array (
        'label' => 'Roles',
        'area' => 'Access control',
      ),
      'permissions' => 
      array (
        'label' => 'Permissions',
        'area' => 'Access control',
      ),
      'tenants' => 
      array (
        'label' => 'Tenants',
        'area' => 'Platform',
      ),
      'plans' => 
      array (
        'label' => 'Plans',
        'area' => 'Platform',
      ),
      'subscriptions' => 
      array (
        'label' => 'Subscriptions',
        'area' => 'Platform',
      ),
      'invoices' => 
      array (
        'label' => 'Invoices',
        'area' => 'Billing',
      ),
      'payments' => 
      array (
        'label' => 'Payments',
        'area' => 'Billing',
      ),
      'billing' => 
      array (
        'label' => 'Billing portal',
        'area' => 'Billing',
      ),
      'settings' => 
      array (
        'label' => 'Settings',
        'area' => 'Administration',
      ),
      'sms' => 
      array (
        'label' => 'SMS balance and history',
        'area' => 'SMS',
      ),
      'sms-accounts' => 
      array (
        'label' => 'Tenant SMS accounts',
        'area' => 'SMS',
      ),
      'sms-recharges' => 
      array (
        'label' => 'SMS recharges',
        'area' => 'SMS',
      ),
      'sms-history' => 
      array (
        'label' => 'SMS history',
        'area' => 'SMS',
      ),
    ),
    'actions' => 
    array (
      'discount' => 'Settlement discount',
      'view' => 'View',
      'create' => 'Create',
      'update' => 'Update',
      'enter' => 'Open dashboard',
      'delete' => 'Delete',
      'assign' => 'Assign roles',
      'manage' => 'Manage permissions',
      'issue' => 'Issue invoices',
      'void' => 'Void invoices',
      'pay' => 'Pay invoices',
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => 'central',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
      'deferred' => 
      array (
        'driver' => 'deferred',
      ),
      'background' => 
      array (
        'driver' => 'background',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'connections' => 
        array (
          0 => 'database',
          1 => 'deferred',
        ),
      ),
    ),
    'batching' => 
    array (
      'database' => 'central',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'central',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'key' => NULL,
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/sessions',
    'connection' => 'central',
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'prof-matin-eye-hospital-session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
    'serialization' => 'json',
  ),
  'settings' => 
  array (
    'tenant_groups' => 
    array (
      0 => 'company',
      1 => 'branding',
      2 => 'general',
      3 => 'email',
      4 => 'hospital',
      5 => 'pharmacy',
      6 => 'prescription',
    ),
    'tenant_only_groups' => 
    array (
      0 => 'hospital',
      1 => 'pharmacy',
      2 => 'prescription',
    ),
    'group_aliases' => 
    array (
      'patients' => 'hospital',
    ),
    'payment_groups' => 
    array (
      0 => 'manual',
      1 => 'sslcommerz',
      2 => 'bkash',
      3 => 'nagad',
      4 => 'eps',
    ),
    'sms_features' => 
    array (
      'hospital.consultation' => 'Consultation billing',
      'hospital.investigation' => 'Investigation billing',
      'hospital.operation' => 'Operation billing',
      'hospital.investigation_delivery' => 'Investigation delivery',
      'hospital.due_collection' => 'Due collection',
      'hospital.refund' => 'Refund',
      'pharmacy.medicine.sale' => 'Medicine sale',
      'pharmacy.medicine.sale_return' => 'Medicine sales return',
      'pharmacy.medicine.payment' => 'Medicine payment',
      'pharmacy.optic.sale' => 'Optic sale',
      'pharmacy.optic.sale_return' => 'Optic sales return',
      'pharmacy.optic.payment' => 'Optic payment',
    ),
    'hospital_sms_notifications' => 
    array (
      'hospital.consultation' => 
      array (
        'label' => 'Consultation invoice',
        'enabled' => 'sms_consultation_enabled',
        'template' => 'sms_consultation_template',
        'description' => 'When a new consultation is saved.',
      ),
      'hospital.investigation' => 
      array (
        'label' => 'Investigation invoice',
        'enabled' => 'sms_investigation_enabled',
        'template' => 'sms_investigation_template',
        'description' => 'When a new investigation invoice is saved.',
      ),
      'hospital.operation' => 
      array (
        'label' => 'Operation invoice',
        'enabled' => 'sms_operation_enabled',
        'template' => 'sms_operation_template',
        'description' => 'When a new operation invoice is saved.',
      ),
      'hospital.investigation_delivery' => 
      array (
        'label' => 'Investigation delivery',
        'enabled' => 'sms_investigation_delivery_enabled',
        'template' => 'sms_investigation_delivery_template',
        'description' => 'Once, when an investigation is marked Delivered.',
      ),
      'hospital.due_collection' => 
      array (
        'label' => 'Due collection',
        'enabled' => 'sms_due_collection_enabled',
        'template' => 'sms_due_collection_template',
        'description' => 'When a new due collection receipt is saved.',
      ),
      'hospital.refund' => 
      array (
        'label' => 'Refund',
        'enabled' => 'sms_refund_enabled',
        'template' => 'sms_refund_template',
        'description' => 'When a new refund voucher is saved.',
      ),
    ),
    'pharmacy_sms_notifications' => 
    array (
      0 => 
      array (
        'feature' => 'pharmacy.medicine.sale',
        'type' => 'Medicine',
        'label' => 'Sale',
        'enabled' => 'sms_medicine_sale_enabled',
        'template' => 'sms_medicine_sale_template',
      ),
      1 => 
      array (
        'feature' => 'pharmacy.medicine.sale_return',
        'type' => 'Medicine',
        'label' => 'Sales return',
        'enabled' => 'sms_medicine_sale_return_enabled',
        'template' => 'sms_medicine_sale_return_template',
      ),
      2 => 
      array (
        'feature' => 'pharmacy.medicine.payment',
        'type' => 'Medicine',
        'label' => 'Customer collection',
        'enabled' => 'sms_medicine_customer_payment_enabled',
        'template' => 'sms_medicine_customer_payment_template',
      ),
      3 => 
      array (
        'feature' => 'pharmacy.medicine.payment',
        'type' => 'Medicine',
        'label' => 'Supplier payment',
        'enabled' => 'sms_medicine_supplier_payment_enabled',
        'template' => 'sms_medicine_supplier_payment_template',
      ),
      4 => 
      array (
        'feature' => 'pharmacy.optic.sale',
        'type' => 'Optic',
        'label' => 'Sale',
        'enabled' => 'sms_optic_sale_enabled',
        'template' => 'sms_optic_sale_template',
      ),
      5 => 
      array (
        'feature' => 'pharmacy.optic.sale_return',
        'type' => 'Optic',
        'label' => 'Sales return',
        'enabled' => 'sms_optic_sale_return_enabled',
        'template' => 'sms_optic_sale_return_template',
      ),
      6 => 
      array (
        'feature' => 'pharmacy.optic.payment',
        'type' => 'Optic',
        'label' => 'Customer collection',
        'enabled' => 'sms_optic_customer_payment_enabled',
        'template' => 'sms_optic_customer_payment_template',
      ),
      7 => 
      array (
        'feature' => 'pharmacy.optic.payment',
        'type' => 'Optic',
        'label' => 'Supplier payment',
        'enabled' => 'sms_optic_supplier_payment_enabled',
        'template' => 'sms_optic_supplier_payment_template',
      ),
    ),
    'reference_discount_modes' => 
    array (
      'gross' => 'Before discount',
      'net' => 'After discount',
      'subtract_discount' => 'Discount from commission',
    ),
    'reference_due_modes' => 
    array (
      'full_payment' => 'After full payment',
      'deduct_due' => 'Due from commission',
      'proportional' => 'As patient pays',
    ),
    'hospital_sections' => 
    array (
      'patient' => 
      array (
        0 => 'id_scope',
        1 => 'patient_id_pattern',
        2 => 'patient_branch_id_pattern',
      ),
      'doctor' => 
      array (
        0 => 'doctor_id_scope',
        1 => 'doctor_id_pattern',
        2 => 'doctor_branch_id_pattern',
      ),
      'reference' => 
      array (
        0 => 'reference_id_scope',
        1 => 'reference_id_pattern',
        2 => 'reference_branch_id_pattern',
        3 => 'reference_defaults_enabled',
        4 => 'reference_consultation_percent',
        5 => 'reference_investigation_percent',
        6 => 'reference_operation_percent',
        7 => 'reference_percentages_enabled',
        8 => 'reference_consultation_percent_enabled',
        9 => 'reference_investigation_percent_enabled',
        10 => 'reference_operation_percent_enabled',
        11 => 'reference_consultation_discount_mode',
        12 => 'reference_consultation_due_mode',
        13 => 'reference_investigation_discount_mode',
        14 => 'reference_investigation_due_mode',
        15 => 'reference_operation_discount_mode',
        16 => 'reference_operation_due_mode',
      ),
      'appointment' => 
      array (
        0 => 'appointment_serial_scope',
        1 => 'appointment_serial_reset',
        2 => 'appointment_serial_pattern',
        3 => 'appointment_invoice_scope',
        4 => 'appointment_invoice_reset',
        5 => 'appointment_invoice_pattern',
      ),
      'consultation' => 
      array (
        0 => 'consultation_serial_enabled',
        1 => 'consultation_serial_scope',
        2 => 'consultation_serial_reset',
        3 => 'consultation_serial_pattern',
        4 => 'consultation_invoice_enabled',
        5 => 'consultation_invoice_scope',
        6 => 'consultation_invoice_reset',
        7 => 'consultation_invoice_pattern',
        8 => 'consultation_reference_enabled',
        9 => 'consultation_payment_default',
      ),
      'investigation' => 
      array (
        0 => 'investigation_invoice_scope',
        1 => 'investigation_invoice_reset',
        2 => 'investigation_invoice_pattern',
        3 => 'investigation_reference_enabled',
        4 => 'investigation_payment_default',
      ),
      'operation' => 
      array (
        0 => 'operation_invoice_scope',
        1 => 'operation_invoice_reset',
        2 => 'operation_invoice_pattern',
        3 => 'operation_reference_enabled',
        4 => 'operation_payment_default',
      ),
      'refund' => 
      array (
        0 => 'refund_invoice_scope',
        1 => 'refund_invoice_reset',
        2 => 'refund_invoice_pattern',
      ),
      'due_collection' => 
      array (
        0 => 'due_collection_invoice_scope',
        1 => 'due_collection_invoice_reset',
        2 => 'due_collection_invoice_pattern',
      ),
      'sms' => 
      array (
        0 => 'sms_consultation_enabled',
        1 => 'sms_consultation_template',
        2 => 'sms_investigation_enabled',
        3 => 'sms_investigation_template',
        4 => 'sms_operation_enabled',
        5 => 'sms_operation_template',
        6 => 'sms_investigation_delivery_enabled',
        7 => 'sms_investigation_delivery_template',
        8 => 'sms_due_collection_enabled',
        9 => 'sms_due_collection_template',
        10 => 'sms_refund_enabled',
        11 => 'sms_refund_template',
      ),
    ),
    'pharmacy_sections' => 
    array (
      'general' => 
      array (
        0 => 'stock_method',
        1 => 'medicine_sale_without_stock_enabled',
        2 => 'optic_sale_without_stock_enabled',
        3 => 'customer_scope',
        4 => 'sale_invoice_paper',
        5 => 'sale_pos_width_mm',
        6 => 'vat_enabled',
        7 => 'vat_mode',
        8 => 'vat_percent',
      ),
      'numbering' => 
      array (
        0 => 'purchase_invoice_pattern',
        1 => 'sale_invoice_pattern',
        2 => 'purchase_return_invoice_pattern',
        3 => 'sale_return_invoice_pattern',
        4 => 'payment_invoice_pattern',
        5 => 'adjustment_invoice_pattern',
        6 => 'damage_invoice_pattern',
        7 => 'transfer_invoice_pattern',
      ),
      'items' => 
      array (
        0 => 'medicine_purchase_discount_enabled',
        1 => 'medicine_purchase_vat_enabled',
        2 => 'medicine_purchase_sale_price_enabled',
        3 => 'medicine_purchase_expiry_enabled',
        4 => 'optic_purchase_discount_enabled',
        5 => 'optic_purchase_vat_enabled',
        6 => 'optic_purchase_sale_price_enabled',
        7 => 'medicine_sale_discount_enabled',
        8 => 'optic_sale_discount_enabled',
      ),
      'sms' => 
      array (
        0 => 'sms_medicine_sale_enabled',
        1 => 'sms_medicine_sale_template',
        2 => 'sms_medicine_sale_return_enabled',
        3 => 'sms_medicine_sale_return_template',
        4 => 'sms_medicine_customer_payment_enabled',
        5 => 'sms_medicine_customer_payment_template',
        6 => 'sms_medicine_supplier_payment_enabled',
        7 => 'sms_medicine_supplier_payment_template',
        8 => 'sms_optic_sale_enabled',
        9 => 'sms_optic_sale_template',
        10 => 'sms_optic_sale_return_enabled',
        11 => 'sms_optic_sale_return_template',
        12 => 'sms_optic_customer_payment_enabled',
        13 => 'sms_optic_customer_payment_template',
        14 => 'sms_optic_supplier_payment_enabled',
        15 => 'sms_optic_supplier_payment_template',
      ),
    ),
    'groups' => 
    array (
      'prescription' => 
      array (
        'label' => 'Prescription',
        'description' => 'General and glass prescription forms and printing.',
        'icon' => 'prescription',
        'route' => 'prescription.settings.index',
      ),
      'company' => 
      array (
        'label' => 'Company',
        'description' => 'Organization contact details and tax identification.',
        'icon' => 'building',
      ),
      'branding' => 
      array (
        'label' => 'Branding',
        'description' => 'The name, tagline, logo, and favicon shown in your workspace.',
        'icon' => 'palette',
      ),
      'billing' => 
      array (
        'label' => 'Billing',
        'description' => 'Invoice currency, numbering prefix, and payment instructions.',
        'icon' => 'file-invoice-dollar',
      ),
      'general' => 
      array (
        'label' => 'General',
        'description' => 'Workspace timezone and language preferences.',
        'icon' => 'sliders',
      ),
      'email' => 
      array (
        'label' => 'Email',
        'description' => 'SMTP delivery and sender identity for this workspace.',
        'icon' => 'envelope',
      ),
      'hospital' => 
      array (
        'label' => 'Hospital',
        'description' => 'Patient, Doctor and Reference IDs, commissions, serials, and invoice numbering.',
        'icon' => 'hospital',
        'route' => 'hospital.settings.index',
      ),
      'pharmacy' => 
      array (
        'label' => 'Pharmacy',
        'description' => 'Stock, numbering and item fields.',
        'icon' => 'boxes-stacked',
        'route' => 'pharmacy.settings.index',
      ),
      'sms' => 
      array (
        'label' => 'SMS',
        'description' => 'Configure an HTTP SMS provider and map its API fields.',
        'icon' => 'comment-sms',
      ),
      'manual' => 
      array (
        'label' => 'Manual payment',
        'description' => 'Manual payment availability, customer instructions, and a fixed extra charge.',
        'icon' => 'money-bill-transfer',
      ),
      'sslcommerz' => 
      array (
        'label' => 'SSLCommerz',
        'description' => 'Store credentials and payment environment.',
        'icon' => 'credit-card',
      ),
      'bkash' => 
      array (
        'label' => 'bKash',
        'description' => 'Application credentials for bKash payments.',
        'icon' => 'mobile-screen-button',
      ),
      'nagad' => 
      array (
        'label' => 'Nagad',
        'description' => 'Merchant identity and signing keys for Nagad.',
        'icon' => 'mobile-screen-button',
      ),
      'eps' => 
      array (
        'label' => 'EPS',
        'description' => 'Merchant and API credentials for EPS payments.',
        'icon' => 'credit-card',
      ),
    ),
    'credentials' => 
    array (
      'email' => 
      array (
        'username' => 
        array (
          'label' => 'SMTP username',
          'max' => 255,
          'optional' => true,
        ),
        'password' => 
        array (
          'label' => 'SMTP password',
          'max' => 4096,
          'optional' => true,
        ),
      ),
      'sms' => 
      array (
        'api_key' => 
        array (
          'label' => 'API key',
          'max' => 4096,
          'optional' => true,
        ),
        'username' => 
        array (
          'label' => 'API username',
          'max' => 255,
          'optional' => true,
        ),
        'password' => 
        array (
          'label' => 'API password',
          'max' => 4096,
          'optional' => true,
        ),
        'headers' => 
        array (
          'label' => 'Headers (JSON object)',
          'max' => 8192,
          'multiline' => true,
          'json' => true,
          'optional' => true,
        ),
        'parameters' => 
        array (
          'label' => 'Parameters (JSON object)',
          'max' => 16384,
          'multiline' => true,
          'json' => true,
          'default' => '{"api_key":"{api_key}","senderid":"{sender_id}","number":"{to}","message":"{message}"}',
        ),
      ),
      'sslcommerz' => 
      array (
        'store_id' => 
        array (
          'label' => 'Store ID',
          'max' => 255,
        ),
        'store_password' => 
        array (
          'label' => 'Store password',
          'min' => 5,
          'max' => 4096,
        ),
      ),
      'bkash' => 
      array (
        'app_key' => 
        array (
          'label' => 'App key',
          'max' => 255,
        ),
        'app_secret' => 
        array (
          'label' => 'App secret',
          'max' => 4096,
        ),
        'username' => 
        array (
          'label' => 'Username',
          'max' => 255,
        ),
        'password' => 
        array (
          'label' => 'Password',
          'min' => 5,
          'max' => 4096,
        ),
      ),
      'nagad' => 
      array (
        'merchant_id' => 
        array (
          'label' => 'Merchant ID',
          'max' => 255,
        ),
        'merchant_number' => 
        array (
          'label' => 'Merchant number',
          'max' => 50,
        ),
        'public_key' => 
        array (
          'label' => 'Public key',
          'max' => 8192,
          'multiline' => true,
        ),
        'private_key' => 
        array (
          'label' => 'Private key',
          'max' => 16384,
          'multiline' => true,
        ),
      ),
      'eps' => 
      array (
        'merchant_id' => 
        array (
          'label' => 'Merchant ID',
          'max' => 255,
        ),
        'store_id' => 
        array (
          'label' => 'Store ID',
          'max' => 255,
        ),
        'username' => 
        array (
          'label' => 'Username',
          'max' => 255,
        ),
        'password' => 
        array (
          'label' => 'Password',
          'min' => 5,
          'max' => 4096,
        ),
        'hash_key' => 
        array (
          'label' => 'Hash key',
          'max' => 4096,
        ),
      ),
    ),
    'defaults' => 
    array (
      'prescription' => 
      array (
        'general_enabled' => true,
        'general_chief_complaints_enabled' => true,
        'general_history_enabled' => true,
        'general_examination_enabled' => true,
        'general_diagnosis_enabled' => true,
        'general_investigations_enabled' => true,
        'general_advice_enabled' => true,
        'general_follow_up_enabled' => true,
        'general_paper' => 'a4',
        'general_custom_width' => '210',
        'general_custom_height' => '297',
        'general_margin_top' => '',
        'general_margin_right' => '',
        'general_margin_bottom' => '',
        'general_margin_left' => '',
        'general_rx_pattern' => 'RX-{SEQ:6}',
        'general_header_enabled' => true,
        'glass_enabled' => true,
        'glass_pd_enabled' => true,
        'glass_disclaimer_enabled' => true,
        'glass_lens_options_enabled' => true,
        'glass_paper' => 'a5',
        'glass_custom_width' => '210',
        'glass_custom_height' => '297',
        'glass_margin_top' => '',
        'glass_margin_right' => '',
        'glass_margin_bottom' => '',
        'glass_margin_left' => '',
        'glass_rx_pattern' => 'GL-{SEQ:6}',
        'glass_header_enabled' => true,
      ),
      'pharmacy' => 
      array (
        'stock_method' => 'fifo',
        'medicine_sale_without_stock_enabled' => false,
        'optic_sale_without_stock_enabled' => false,
        'customer_scope' => 'all',
        'sale_invoice_paper' => 'a4',
        'sale_pos_width_mm' => '80',
        'vat_enabled' => false,
        'vat_mode' => 'exclusive',
        'vat_percent' => '0.00',
        'purchase_invoice_pattern' => 'PURCHASE-B{BRANCH}-{SEQ:6}',
        'sale_invoice_pattern' => 'SALE-B{BRANCH}-{SEQ:6}',
        'purchase_return_invoice_pattern' => 'PURCHASE-RETURN-B{BRANCH}-{SEQ:6}',
        'sale_return_invoice_pattern' => 'SALE-RETURN-B{BRANCH}-{SEQ:6}',
        'payment_invoice_pattern' => 'PAY-{BRANCH}-{SEQ:6}',
        'adjustment_invoice_pattern' => 'ADJUSTMENT-B{BRANCH}-{SEQ:6}',
        'damage_invoice_pattern' => 'DAMAGE-B{BRANCH}-{SEQ:6}',
        'transfer_invoice_pattern' => 'TRANSFER-B{BRANCH}-{SEQ:6}',
        'medicine_purchase_discount_enabled' => true,
        'medicine_purchase_vat_enabled' => true,
        'medicine_purchase_sale_price_enabled' => true,
        'medicine_purchase_expiry_enabled' => true,
        'optic_purchase_discount_enabled' => true,
        'optic_purchase_vat_enabled' => true,
        'optic_purchase_sale_price_enabled' => true,
        'medicine_sale_discount_enabled' => true,
        'optic_sale_discount_enabled' => true,
        'sms_medicine_sale_enabled' => false,
        'sms_medicine_sale_template' => '{organization}: Dear {name}, invoice {invoice} ({date}). Total BDT {total}, paid BDT {paid}, due BDT {due}.',
        'sms_medicine_sale_return_enabled' => false,
        'sms_medicine_sale_return_template' => '{organization}: Return {invoice} for {source_invoice} ({date}). Credit BDT {total}, cash refund BDT {refund}, due reduced BDT {due_adjustment}.',
        'sms_medicine_customer_payment_enabled' => false,
        'sms_medicine_customer_payment_template' => '{organization}: Dear {name}, received BDT {amount} on {date}. Receipt {invoice}. Remaining due BDT {due}.',
        'sms_medicine_supplier_payment_enabled' => false,
        'sms_medicine_supplier_payment_template' => '{organization}: Dear {name}, paid BDT {amount} on {date}. Receipt {invoice}. Remaining due BDT {due}.',
        'sms_optic_sale_enabled' => false,
        'sms_optic_sale_template' => '{organization}: Dear {name}, invoice {invoice} ({date}). Total BDT {total}, paid BDT {paid}, due BDT {due}.',
        'sms_optic_sale_return_enabled' => false,
        'sms_optic_sale_return_template' => '{organization}: Return {invoice} for {source_invoice} ({date}). Credit BDT {total}, cash refund BDT {refund}, due reduced BDT {due_adjustment}.',
        'sms_optic_customer_payment_enabled' => false,
        'sms_optic_customer_payment_template' => '{organization}: Dear {name}, received BDT {amount} on {date}. Receipt {invoice}. Remaining due BDT {due}.',
        'sms_optic_supplier_payment_enabled' => false,
        'sms_optic_supplier_payment_template' => '{organization}: Dear {name}, paid BDT {amount} on {date}. Receipt {invoice}. Remaining due BDT {due}.',
      ),
      'company' => 
      array (
        'name' => 'Prof. Matin Eye Hospital',
        'email' => 'hello@example.com',
        'phone' => '',
        'address' => '',
        'tax_id' => '',
      ),
      'branding' => 
      array (
        'app_name' => 'Prof. Matin Eye Hospital',
        'tagline' => '',
        'logo_path' => '',
        'favicon_path' => '',
      ),
      'billing' => 
      array (
        'currency' => 'BDT',
        'invoice_prefix' => 'INV',
        'payment_instructions' => '',
      ),
      'general' => 
      array (
        'timezone' => 'Asia/Dhaka',
        'locale' => 'en',
        'module_order' => 
        array (
        ),
        'module_names' => 
        array (
        ),
      ),
      'email' => 
      array (
        'enabled' => false,
        'host' => '',
        'port' => '587',
        'encryption' => 'tls',
        'from_address' => '',
        'from_name' => '',
      ),
      'hospital' => 
      array (
        'id_scope' => 'tenant',
        'patient_id_pattern' => 'P{SEQ:6}',
        'patient_branch_id_pattern' => 'B{BRANCH}-P{SEQ:6}',
        'doctor_id_scope' => 'tenant',
        'doctor_id_pattern' => 'D{SEQ:6}',
        'doctor_branch_id_pattern' => 'B{BRANCH}-D{SEQ:6}',
        'reference_id_scope' => 'tenant',
        'reference_id_pattern' => 'R{SEQ:6}',
        'reference_branch_id_pattern' => 'B{BRANCH}-R{SEQ:6}',
        'reference_defaults_enabled' => false,
        'reference_consultation_percent' => '0.00',
        'reference_investigation_percent' => '0.00',
        'reference_operation_percent' => '0.00',
        'reference_percentages_enabled' => true,
        'reference_consultation_percent_enabled' => true,
        'reference_investigation_percent_enabled' => true,
        'reference_operation_percent_enabled' => true,
        'reference_consultation_discount_mode' => 'net',
        'reference_consultation_due_mode' => 'full_payment',
        'reference_investigation_discount_mode' => 'net',
        'reference_investigation_due_mode' => 'full_payment',
        'reference_operation_discount_mode' => 'net',
        'reference_operation_due_mode' => 'full_payment',
        'appointment_serial_scope' => 'doctor',
        'appointment_serial_reset' => 'day',
        'appointment_serial_pattern' => '{SEQ:3}',
        'appointment_invoice_scope' => 'branch',
        'appointment_invoice_reset' => 'never',
        'appointment_invoice_pattern' => 'APT-B{BRANCH}-{SEQ:6}',
        'consultation_serial_enabled' => true,
        'consultation_serial_scope' => 'doctor',
        'consultation_serial_reset' => 'day',
        'consultation_serial_pattern' => '{SEQ:3}',
        'consultation_invoice_enabled' => true,
        'consultation_invoice_scope' => 'branch',
        'consultation_invoice_reset' => 'never',
        'consultation_invoice_pattern' => 'CON-B{BRANCH}-{SEQ:6}',
        'consultation_reference_enabled' => false,
        'consultation_payment_default' => 'due',
        'investigation_invoice_scope' => 'branch',
        'investigation_invoice_reset' => 'never',
        'investigation_invoice_pattern' => 'INV-B{BRANCH}-{SEQ:6}',
        'investigation_reference_enabled' => false,
        'investigation_payment_default' => 'due',
        'operation_invoice_scope' => 'branch',
        'operation_invoice_reset' => 'never',
        'operation_invoice_pattern' => 'OP-B{BRANCH}-{SEQ:6}',
        'operation_reference_enabled' => false,
        'operation_payment_default' => 'due',
        'refund_invoice_scope' => 'tenant',
        'refund_invoice_reset' => 'never',
        'refund_invoice_pattern' => 'REF-{SEQ:6}',
        'due_collection_invoice_scope' => 'tenant',
        'due_collection_invoice_reset' => 'never',
        'due_collection_invoice_pattern' => 'DC-{SEQ:6}',
        'sms_consultation_enabled' => false,
        'sms_consultation_template' => '{organization}: {name}, consultation {invoice} on {date}. Total Tk {total}, paid Tk {paid}, due Tk {due}.',
        'sms_investigation_enabled' => false,
        'sms_investigation_template' => '{organization}: {name}, investigation {invoice} on {date}. Total Tk {total}, paid Tk {paid}, due Tk {due}.',
        'sms_operation_enabled' => false,
        'sms_operation_template' => '{organization}: {name}, operation {invoice} on {date}. Total Tk {total}, paid Tk {paid}, due Tk {due}.',
        'sms_investigation_delivery_enabled' => false,
        'sms_investigation_delivery_template' => '{organization}: {name}, investigation report {invoice} has been delivered on {date}. Remaining due Tk {due}.',
        'sms_due_collection_enabled' => false,
        'sms_due_collection_template' => '{organization}: {name}, receipt {invoice} on {date}: received Tk {amount}. Remaining due Tk {due}.',
        'sms_refund_enabled' => false,
        'sms_refund_template' => '{organization}: {name}, refund voucher {invoice}: cash returned Tk {amount}. Remaining bill Tk {total}, due Tk {due}.',
      ),
      'sms' => 
      array (
        'enabled' => false,
        'provider' => '',
        'api_url' => '',
        'method' => 'POST',
        'body_format' => 'json',
        'sender_id' => '',
        'success_field' => '',
        'success_value' => '',
        'failure_values' => '',
      ),
      'manual' => 
      array (
        'enabled' => true,
        'instructions' => '',
        'extra_charge' => '0.00',
      ),
      'sslcommerz' => 
      array (
        'enabled' => false,
        'sandbox' => true,
        'extra_charge' => '0.00',
      ),
      'bkash' => 
      array (
        'enabled' => false,
        'sandbox' => true,
        'extra_charge' => '0.00',
      ),
      'nagad' => 
      array (
        'enabled' => false,
        'sandbox' => true,
        'extra_charge' => '0.00',
      ),
      'eps' => 
      array (
        'enabled' => false,
        'sandbox' => true,
        'extra_charge' => '0.00',
      ),
    ),
    'rules' => 
    array (
      'prescription' => 
      array (
        'general_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_chief_complaints_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_history_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_examination_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_diagnosis_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_investigations_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_advice_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_follow_up_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'general_paper' => 
        array (
          0 => 'required',
          1 => 'in:a4,a5,custom',
        ),
        'general_custom_width' => 
        array (
          0 => 'exclude_unless:data.general_paper,custom',
          1 => 'required',
          2 => 'string',
          3 => 'numeric',
          4 => 'between:50,1000',
          5 => 'decimal:0,2',
        ),
        'general_custom_height' => 
        array (
          0 => 'exclude_unless:data.general_paper,custom',
          1 => 'required',
          2 => 'string',
          3 => 'numeric',
          4 => 'between:50,1000',
          5 => 'decimal:0,2',
        ),
        'general_margin_top' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'general_margin_right' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'general_margin_bottom' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'general_margin_left' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'general_rx_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
          4 => 'not_regex:/[\\x00-\\x1F\\x7F]/',
          5 => 'prescription_rx_pattern',
        ),
        'general_header_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'glass_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'glass_pd_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'glass_disclaimer_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'glass_lens_options_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'glass_paper' => 
        array (
          0 => 'required',
          1 => 'in:a4,a5,custom',
        ),
        'glass_custom_width' => 
        array (
          0 => 'exclude_unless:data.glass_paper,custom',
          1 => 'required',
          2 => 'string',
          3 => 'numeric',
          4 => 'between:50,1000',
          5 => 'decimal:0,2',
        ),
        'glass_custom_height' => 
        array (
          0 => 'exclude_unless:data.glass_paper,custom',
          1 => 'required',
          2 => 'string',
          3 => 'numeric',
          4 => 'between:50,1000',
          5 => 'decimal:0,2',
        ),
        'glass_margin_top' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'glass_margin_right' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'glass_margin_bottom' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'glass_margin_left' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'numeric',
          3 => 'between:0,50',
          4 => 'decimal:0,2',
        ),
        'glass_rx_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
          4 => 'not_regex:/[\\x00-\\x1F\\x7F]/',
          5 => 'prescription_rx_pattern',
        ),
        'glass_header_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
      ),
      'pharmacy' => 
      array (
        'stock_method' => 
        array (
          0 => 'required',
          1 => 'in:fifo,lifo',
        ),
        'medicine_sale_without_stock_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'optic_sale_without_stock_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'customer_scope' => 
        array (
          0 => 'required',
          1 => 'in:all,customer',
        ),
        'sale_invoice_paper' => 
        array (
          0 => 'required',
          1 => 'in:a4,a5,pos',
        ),
        'sale_pos_width_mm' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'regex:/\\A\\d{1,3}(?:\\.\\d{1,2})?\\z/',
          4 => 'numeric',
          5 => 'between:20,300',
        ),
        'vat_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'vat_mode' => 
        array (
          0 => 'required',
          1 => 'in:inclusive,exclusive',
        ),
        'vat_percent' => 
        array (
          0 => 'required',
          1 => 'numeric',
          2 => 'between:0,100',
          3 => 'decimal:0,2',
        ),
        'purchase_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'sale_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'purchase_return_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'sale_return_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'payment_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'adjustment_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'damage_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'transfer_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'medicine_purchase_discount_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'medicine_purchase_vat_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'medicine_purchase_sale_price_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'medicine_purchase_expiry_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'optic_purchase_discount_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'optic_purchase_vat_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'optic_purchase_sale_price_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'medicine_sale_discount_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'optic_sale_discount_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_medicine_sale_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_medicine_sale_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_medicine_sale_return_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_medicine_sale_return_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_medicine_customer_payment_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_medicine_customer_payment_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_medicine_supplier_payment_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_medicine_supplier_payment_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_optic_sale_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_optic_sale_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_optic_sale_return_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_optic_sale_return_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_optic_customer_payment_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_optic_customer_payment_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_optic_supplier_payment_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_optic_supplier_payment_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
      ),
      'company' => 
      array (
        'name' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:255',
        ),
        'email' => 
        array (
          0 => 'nullable',
          1 => 'email:rfc',
          2 => 'max:255',
        ),
        'phone' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:30',
          3 => 'regex:/\\A[0-9]+\\z/',
        ),
        'address' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:2000',
        ),
        'tax_id' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:100',
        ),
      ),
      'branding' => 
      array (
        'app_name' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:120',
        ),
        'tagline' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:255',
        ),
      ),
      'billing' => 
      array (
        'currency' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A[A-Z]{3}\\z/i',
        ),
        'invoice_prefix' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:20',
          3 => 'regex:/\\A[A-Z0-9_-]+\\z/i',
        ),
        'payment_instructions' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:4000',
        ),
      ),
      'general' => 
      array (
        'timezone' => 
        array (
          0 => 'required',
          1 => 'timezone',
        ),
        'locale' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:12',
          3 => 'regex:/\\A[a-z]{2}(?:[_-][A-Z]{2})?\\z/',
        ),
        'module_order' => 
        array (
          0 => 'sometimes',
          1 => 'array',
        ),
        'module_names' => 
        array (
          0 => 'sometimes',
          1 => 'array',
        ),
      ),
      'hospital' => 
      array (
        'id_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'patient_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'patient_branch_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'doctor_id_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'doctor_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'doctor_branch_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'reference_id_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'reference_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'reference_branch_id_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:40',
        ),
        'reference_defaults_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'reference_consultation_percent' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'decimal:0,2',
          3 => 'between:0,100',
        ),
        'reference_investigation_percent' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'decimal:0,2',
          3 => 'between:0,100',
        ),
        'reference_operation_percent' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'decimal:0,2',
          3 => 'between:0,100',
        ),
        'reference_percentages_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'reference_consultation_percent_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'reference_investigation_percent_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'reference_operation_percent_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'reference_consultation_discount_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:gross,net,subtract_discount',
        ),
        'reference_consultation_due_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:full_payment,deduct_due,proportional',
        ),
        'reference_investigation_discount_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:gross,net,subtract_discount',
        ),
        'reference_investigation_due_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:full_payment,deduct_due,proportional',
        ),
        'reference_operation_discount_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:gross,net,subtract_discount',
        ),
        'reference_operation_due_mode' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:full_payment,deduct_due,proportional',
        ),
        'appointment_serial_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch,doctor',
        ),
        'appointment_serial_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'appointment_serial_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'appointment_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'appointment_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'appointment_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'consultation_serial_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'consultation_serial_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch,doctor',
        ),
        'consultation_serial_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'consultation_serial_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'consultation_invoice_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'consultation_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'consultation_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'consultation_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'consultation_reference_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'consultation_payment_default' => 
        array (
          0 => 'required',
          1 => 'in:paid,due',
        ),
        'investigation_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'investigation_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'investigation_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'investigation_reference_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'investigation_payment_default' => 
        array (
          0 => 'required',
          1 => 'in:paid,due',
        ),
        'operation_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'operation_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'operation_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'operation_reference_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'operation_payment_default' => 
        array (
          0 => 'required',
          1 => 'in:paid,due',
        ),
        'refund_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'refund_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'refund_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'due_collection_invoice_scope' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:tenant,branch',
        ),
        'due_collection_invoice_reset' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'in:never,year,month,day',
        ),
        'due_collection_invoice_pattern' => 
        array (
          0 => 'bail',
          1 => 'required',
          2 => 'string',
          3 => 'max:80',
        ),
        'sms_consultation_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_consultation_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_investigation_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_investigation_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_operation_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_operation_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_investigation_delivery_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_investigation_delivery_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_due_collection_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_due_collection_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
        'sms_refund_enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sms_refund_template' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'max:1600',
        ),
      ),
      'email' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'host' => 
        array (
          0 => 'required_if:data.enabled,1',
          1 => 'nullable',
          2 => 'string',
          3 => 'max:255',
          4 => 'regex:/\\A[a-z0-9](?:[a-z0-9.-]*[a-z0-9])?\\z/i',
        ),
        'port' => 
        array (
          0 => 'required',
          1 => 'integer',
          2 => 'between:1,65535',
        ),
        'encryption' => 
        array (
          0 => 'required',
          1 => 'in:tls,ssl,none',
        ),
        'from_address' => 
        array (
          0 => 'required_if:data.enabled,1',
          1 => 'nullable',
          2 => 'email:rfc',
          3 => 'max:255',
        ),
        'from_name' => 
        array (
          0 => 'required_if:data.enabled,1',
          1 => 'nullable',
          2 => 'string',
          3 => 'max:255',
        ),
      ),
      'sms' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'provider' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:100',
        ),
        'api_url' => 
        array (
          0 => 'required_if:data.enabled,1',
          1 => 'nullable',
          2 => 'url:http,https',
          3 => 'max:2048',
        ),
        'method' => 
        array (
          0 => 'required',
          1 => 'in:GET,POST',
        ),
        'body_format' => 
        array (
          0 => 'required',
          1 => 'in:json,form',
        ),
        'sender_id' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:100',
        ),
        'success_field' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:100',
        ),
        'success_value' => 
        array (
          0 => 'required_with:data.success_field',
          1 => 'nullable',
          2 => 'string',
          3 => 'max:100',
        ),
        'failure_values' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:1000',
        ),
      ),
      'manual' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'instructions' => 
        array (
          0 => 'nullable',
          1 => 'string',
          2 => 'max:4000',
        ),
        'extra_charge' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A\\d{1,10}(?:\\.\\d{1,2})?\\z/',
        ),
      ),
      'sslcommerz' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sandbox' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'extra_charge' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A\\d{1,10}(?:\\.\\d{1,2})?\\z/',
        ),
      ),
      'bkash' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sandbox' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'extra_charge' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A\\d{1,10}(?:\\.\\d{1,2})?\\z/',
        ),
      ),
      'nagad' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sandbox' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'extra_charge' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A\\d{1,10}(?:\\.\\d{1,2})?\\z/',
        ),
      ),
      'eps' => 
      array (
        'enabled' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'sandbox' => 
        array (
          0 => 'required',
          1 => 'boolean',
        ),
        'extra_charge' => 
        array (
          0 => 'required',
          1 => 'string',
          2 => 'regex:/\\A\\d{1,10}(?:\\.\\d{1,2})?\\z/',
        ),
      ),
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
    ),
    'compiled' => 'D:\\laragon\\www\\laravel_project\\prof_matin_eye_hospital\\storage\\framework/views',
  ),
  'mcp' => 
  array (
    'redirect_domains' => 
    array (
      0 => '*',
    ),
    'custom_schemes' => 
    array (
    ),
    'authorization_server' => NULL,
    'tool_search' => 
    array (
      'max_tool_calls' => 10,
      'max_output_bytes' => 65536,
    ),
    'clients' => 
    array (
      'github' => 
      array (
        'url' => 'https://api.githubcopilot.com/mcp/',
        'token' => NULL,
        'headers' => 
        array (
          'X-MCP-Readonly' => 'true',
        ),
      ),
      'linear' => 
      array (
        'url' => 'https://mcp.linear.app/mcp',
        'token' => NULL,
      ),
      'playwright' => 
      array (
        'command' => 'npx',
        'args' => 
        array (
          0 => '-y',
          1 => '@playwright/mcp@latest',
          2 => '--headless',
        ),
      ),
    ),
  ),
  'accounting' => 
  array (
    'report_roles' => 
    array (
      111100 => 'cash_in_hand',
      111200 => 'cash_at_bank',
      111300 => 'cash_at_bank',
    ),
    'cash_flow_activities' => 
    array (
      110000 => 'operating',
      210000 => 'operating',
      310000 => 'financing',
      410000 => 'operating',
      510000 => 'operating',
    ),
    'accounts' => 
    array (
      'cash' => '111101',
      'bank' => '111201',
      'mobile' => '111301',
      'patient_receivable' => '112101',
      'customer_receivable' => '112102',
      'medicine_inventory' => '113101',
      'optic_inventory' => '113201',
      'interbranch_clearing' => '114101',
      'supplier_payable' => '211101',
      'vat_payable' => '212101',
      'customer_advance' => '213101',
      'capital' => '311101',
      'opening_balance' => '311102',
      'consultation_income' => '411101',
      'investigation_income' => '411102',
      'operation_income' => '411103',
      'medicine_sales' => '412101',
      'optic_sales' => '412201',
      'other_income' => '414101',
      'supplier_discount' => '415101',
      'adjustment_gain' => '416101',
      'purchase_return_gain' => '417101',
      'medicine_cogs' => '511101',
      'optic_cogs' => '511201',
      'general_expense' => '512101',
      'discount_allowed' => '513101',
      'wastage' => '514101',
      'sales_returns' => '514201',
      'hospital_refunds' => '514301',
      'commission_expense' => '514401',
      'adjustment_loss' => '514501',
      'valuation_variance' => '514601',
      'purchase_return_loss' => '514701',
    ),
    'integrations' => 
    array (
      'tenantcore.income' => 
      array (
        'name' => 'Income entry',
        'module' => 'tenantcore',
        'voucher' => 'receipt',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'value' => 
          array (
            'name' => 'Income',
            'account' => 'other_income',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'tenantcore.expense' => 
      array (
        'name' => 'Expense entry',
        'module' => 'tenantcore',
        'voucher' => 'payment',
        'components' => 
        array (
          'value' => 
          array (
            'name' => 'Expense',
            'account' => 'general_expense',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
        ),
      ),
      'pharmacy.medicine.purchase' => 
      array (
        'name' => 'Medicine — Purchase',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.sale' => 
      array (
        'name' => 'Medicine — Sale',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'discount' => 
          array (
            'name' => 'Sales discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'revenue' => 
          array (
            'name' => 'Sales income',
            'account' => 'medicine_sales',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'vat' => 
          array (
            'name' => 'Output VAT',
            'account' => 'vat_payable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'cogs' => 
          array (
            'name' => 'Cost of sales',
            'account' => 'medicine_cogs',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.sale-return' => 
      array (
        'name' => 'Medicine — Sales return',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'returns' => 
          array (
            'name' => 'Sales return',
            'account' => 'sales_returns',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'vat' => 
          array (
            'name' => 'VAT reversal',
            'account' => 'vat_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'cogs' => 
          array (
            'name' => 'Cost reversal',
            'account' => 'medicine_cogs',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.purchase-return' => 
      array (
        'name' => 'Medicine — Purchase return',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'variance_loss' => 
          array (
            'name' => 'Valuation loss',
            'account' => 'purchase_return_loss',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'variance_gain' => 
          array (
            'name' => 'Valuation gain',
            'account' => 'purchase_return_gain',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.customer-payment' => 
      array (
        'name' => 'Medicine — Customer payment',
        'module' => 'pharmacy',
        'voucher' => 'receipt',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'discount' => 
          array (
            'name' => 'Settlement discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.supplier-payment' => 
      array (
        'name' => 'Medicine — Supplier payment',
        'module' => 'pharmacy',
        'voucher' => 'payment',
        'components' => 
        array (
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'discount' => 
          array (
            'name' => 'Settlement discount',
            'account' => 'supplier_discount',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.opening-stock' => 
      array (
        'name' => 'Medicine — Opening stock',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'equity' => 
          array (
            'name' => 'Opening balance',
            'account' => 'opening_balance',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.wastage' => 
      array (
        'name' => 'Medicine — Damage / Wastage',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'expense' => 
          array (
            'name' => 'Damage / Wastage',
            'account' => 'wastage',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.adjustment' => 
      array (
        'name' => 'Medicine — Stock adjustment',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory_in' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'gain' => 
          array (
            'name' => 'Adjustment gain',
            'account' => 'adjustment_gain',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'loss' => 
          array (
            'name' => 'Adjustment loss',
            'account' => 'adjustment_loss',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory_out' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.transfer-out' => 
      array (
        'name' => 'Medicine — Branch transfer — Out',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'clearing' => 
          array (
            'name' => 'Interbranch clearing',
            'account' => 'interbranch_clearing',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.transfer-in' => 
      array (
        'name' => 'Medicine — Branch transfer — In',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'clearing' => 
          array (
            'name' => 'Interbranch clearing',
            'account' => 'interbranch_clearing',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.medicine.stock-variance' => 
      array (
        'name' => 'Medicine — Stock cost correction',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory_in' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'gain' => 
          array (
            'name' => 'Cost correction',
            'account' => 'valuation_variance',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'loss' => 
          array (
            'name' => 'Cost correction',
            'account' => 'valuation_variance',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory_out' => 
          array (
            'name' => 'Inventory',
            'account' => 'medicine_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.purchase' => 
      array (
        'name' => 'Optic — Purchase',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.sale' => 
      array (
        'name' => 'Optic — Sale',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'discount' => 
          array (
            'name' => 'Sales discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'revenue' => 
          array (
            'name' => 'Sales income',
            'account' => 'optic_sales',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'vat' => 
          array (
            'name' => 'Output VAT',
            'account' => 'vat_payable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'cogs' => 
          array (
            'name' => 'Cost of sales',
            'account' => 'optic_cogs',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.sale-return' => 
      array (
        'name' => 'Optic — Sales return',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'returns' => 
          array (
            'name' => 'Sales return',
            'account' => 'sales_returns',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'vat' => 
          array (
            'name' => 'VAT reversal',
            'account' => 'vat_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'cogs' => 
          array (
            'name' => 'Cost reversal',
            'account' => 'optic_cogs',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.purchase-return' => 
      array (
        'name' => 'Optic — Purchase return',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'variance_loss' => 
          array (
            'name' => 'Valuation loss',
            'account' => 'purchase_return_loss',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'variance_gain' => 
          array (
            'name' => 'Valuation gain',
            'account' => 'purchase_return_gain',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.customer-payment' => 
      array (
        'name' => 'Optic — Customer payment',
        'module' => 'pharmacy',
        'voucher' => 'receipt',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'discount' => 
          array (
            'name' => 'Settlement discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'receivable' => 
          array (
            'name' => 'Customer due',
            'account' => 'customer_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.supplier-payment' => 
      array (
        'name' => 'Optic — Supplier payment',
        'module' => 'pharmacy',
        'voucher' => 'payment',
        'components' => 
        array (
          'payable' => 
          array (
            'name' => 'Supplier due',
            'account' => 'supplier_payable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'discount' => 
          array (
            'name' => 'Settlement discount',
            'account' => 'supplier_discount',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.opening-stock' => 
      array (
        'name' => 'Optic — Opening stock',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'equity' => 
          array (
            'name' => 'Opening balance',
            'account' => 'opening_balance',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.wastage' => 
      array (
        'name' => 'Optic — Damage / Wastage',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'expense' => 
          array (
            'name' => 'Damage / Wastage',
            'account' => 'wastage',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.adjustment' => 
      array (
        'name' => 'Optic — Stock adjustment',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory_in' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'gain' => 
          array (
            'name' => 'Adjustment gain',
            'account' => 'adjustment_gain',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'loss' => 
          array (
            'name' => 'Adjustment loss',
            'account' => 'adjustment_loss',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory_out' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.transfer-out' => 
      array (
        'name' => 'Optic — Branch transfer — Out',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'clearing' => 
          array (
            'name' => 'Interbranch clearing',
            'account' => 'interbranch_clearing',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.transfer-in' => 
      array (
        'name' => 'Optic — Branch transfer — In',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'clearing' => 
          array (
            'name' => 'Interbranch clearing',
            'account' => 'interbranch_clearing',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'pharmacy.optic.stock-variance' => 
      array (
        'name' => 'Optic — Stock cost correction',
        'module' => 'pharmacy',
        'voucher' => 'journal',
        'components' => 
        array (
          'inventory_in' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'gain' => 
          array (
            'name' => 'Cost correction',
            'account' => 'valuation_variance',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
          'loss' => 
          array (
            'name' => 'Cost correction',
            'account' => 'valuation_variance',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'inventory_out' => 
          array (
            'name' => 'Inventory',
            'account' => 'optic_inventory',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.consultation' => 
      array (
        'name' => 'Consultation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Patient due',
            'account' => 'patient_receivable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'discount' => 
          array (
            'name' => 'Discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'revenue' => 
          array (
            'name' => 'Consultation income',
            'account' => 'consultation_income',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.investigation' => 
      array (
        'name' => 'Patient investigation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Patient due',
            'account' => 'patient_receivable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'discount' => 
          array (
            'name' => 'Discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'revenue' => 
          array (
            'name' => 'Investigation income',
            'account' => 'investigation_income',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.operation' => 
      array (
        'name' => 'Patient operation',
        'module' => 'hospital',
        'voucher' => 'journal',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Patient due',
            'account' => 'patient_receivable',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'discount' => 
          array (
            'name' => 'Discount',
            'account' => 'discount_allowed',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'revenue' => 
          array (
            'name' => 'Operation income',
            'account' => 'operation_income',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.due-collection' => 
      array (
        'name' => 'Hospital due collection',
        'module' => 'hospital',
        'voucher' => 'receipt',
        'components' => 
        array (
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'debit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Patient due',
            'account' => 'patient_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.refund' => 
      array (
        'name' => 'Hospital refund',
        'module' => 'hospital',
        'voucher' => 'payment',
        'components' => 
        array (
          'refund' => 
          array (
            'name' => 'Refund',
            'account' => 'hospital_refunds',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
          'receivable' => 
          array (
            'name' => 'Patient due adjustment',
            'account' => 'patient_receivable',
            'side' => 'credit',
            'use_payment_type' => false,
          ),
        ),
      ),
      'hospital.commission' => 
      array (
        'name' => 'Reference commission payment',
        'module' => 'hospital',
        'voucher' => 'payment',
        'components' => 
        array (
          'expense' => 
          array (
            'name' => 'Commission expense',
            'account' => 'commission_expense',
            'side' => 'debit',
            'use_payment_type' => false,
          ),
          'settlement' => 
          array (
            'name' => 'Cash / Bank',
            'account' => 'cash',
            'side' => 'credit',
            'use_payment_type' => true,
          ),
        ),
      ),
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
    'trust_project' => 'always',
  ),
);
