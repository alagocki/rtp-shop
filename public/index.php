<?php declare(strict_types=1);

use Shopware\Core\DevOps\Environment\EnvironmentHelper;
use Shopware\Core\Framework\Plugin\KernelPluginLoader\ComposerPluginLoader;
use Shopware\Core\Installer\InstallerKernel;
use Shopware\Core\Framework\Adapter\Kernel\KernelFactory;

$_SERVER['SCRIPT_FILENAME'] = __FILE__;

require_once __DIR__ . '/../vendor/autoload_runtime.php';

$filename = (new \DateTime())->format('Y-m-').'visits.csv';
$fileheader = false;
if (!file_exists($filename)) {
    $fileheader = true;
}
$fh = fopen($filename, 'a');
$line = [
    'date' => (new \DateTime())->format('Y-m-d H:i:s'),
    'host' => $_SERVER['HTTP_HOST'] ?? '-',
    'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? '-',
    'remote_host' => $_SERVER['REMOTE_HOST'] ?? '-',
    'browser' => $_SERVER['HTTP_USER_AGENT'] ?? '-',
    'path' => $_SERVER['QUERY_STRING'] ?? '-',
];
if ($fileheader) {
    $heads = array_keys($line);
    fputcsv($fh, $heads, ',', '"', "\\");
}
fputcsv($fh, $line, ',', '"', "\\");
fclose($fh);

if (!file_exists(__DIR__ . '/../.env') && !file_exists(__DIR__ . '/../.env.dist') && !file_exists(__DIR__ . '/../.env.local.php')) {
    $_SERVER['APP_RUNTIME_OPTIONS']['disable_dotenv'] = true;
}

return function (array $context) {
    $classLoader = require __DIR__ . '/../vendor/autoload.php';

    if (!EnvironmentHelper::getVariable('SHOPWARE_SKIP_WEBINSTALLER', false) && !file_exists(dirname(__DIR__) . '/install.lock')) {
        $baseURL = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_NAME']);
        $baseURL = rtrim($baseURL, '/');

        if (strpos($_SERVER['REQUEST_URI'], '/installer') === false) {
            header('Location: ' . $baseURL . '/installer');
            exit;
        }
    }

    $appEnv = $context['APP_ENV'] ?? 'dev';
    $debug = (bool) ($context['APP_DEBUG'] ?? ($appEnv !== 'prod'));

    if (!EnvironmentHelper::getVariable('SHOPWARE_SKIP_WEBINSTALLER', false) && !file_exists(dirname(__DIR__) . '/install.lock')) {
        return new InstallerKernel($appEnv, $debug);
    }

    $pluginLoader = null;

    if (EnvironmentHelper::getVariable('COMPOSER_PLUGIN_LOADER', false)) {
        $pluginLoader = new ComposerPluginLoader($classLoader, null);
    }

    return KernelFactory::create(
        environment: $appEnv,
        debug: $debug,
        classLoader: $classLoader,
        pluginLoader: $pluginLoader,
    );
};
