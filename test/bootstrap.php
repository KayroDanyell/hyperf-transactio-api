<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Database\Model\Factory;
use Hyperf\Database\Model\FactoryBuilder;
use Psr\Log\LogLevel;

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);
date_default_timezone_set('Asia/Shanghai');

Swoole\Runtime::enableCoroutine(true);

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

require BASE_PATH . '/vendor/autoload.php';

! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', Hyperf\Engine\DefaultOption::hookFlags());

Hyperf\Di\ClassLoader::init();

$container = require BASE_PATH . '/config/container.php';

$container->get(Hyperf\Contract\ApplicationInterface::class);

$config = $container->get(ConfigInterface::class);

$config->set('logger.default', []);

$config->set(StdoutLoggerInterface::class, [
    'log_level' => [
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::EMERGENCY,
        LogLevel::ERROR,
        LogLevel::INFO,
        LogLevel::NOTICE,
        LogLevel::WARNING,
    ],
]);

run(function () use ($container) {
    $container = ApplicationContext::getContainer();
    $container->get('Hyperf\Database\Commands\Migrations\FreshCommand')->run(
        new Symfony\Component\Console\Input\StringInput(''),
        new Symfony\Component\Console\Output\ConsoleOutput()
    );
});

if (! function_exists('factory')) {
    function factory($argument): FactoryBuilder
    {
        $factory = ApplicationContext::getContainer()->get(Factory::class);

        return $factory->of($argument);
    }
}