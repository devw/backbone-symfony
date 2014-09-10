<?php

namespace HighFive\Composer;

use Composer\IO\IOInterface;
use Composer\Script\Event;
use Symfony\Component\Yaml\Inline;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class ScriptHandler
{
    private static $map = array(
        'AM__KISSMETRICS_KEY' => 'kissmetrics_api_key',
        'AM__GOOGLE_ANALYTICS_ACCOUNT' => 'google_analytics_account',
        'AM__MANDRILL_KEY' => 'mandrill_api_key',
        'AM__DELIVERY_ADDRESS' => 'mailer_delivery_address',
        'AM__SECRET' => 'secret',
        'AM__DB_HOST' => 'database_host',
        'AM__DB_PORT' => 'database_port',
        'AM__DB_NAME' => 'database_name',
        'AM__DB_USER' => 'database_user',
        'AM__DB_PASSWORD' => 'database_password',
        'AM__REQUEST_CONTEXT_HOST' => 'router.request_context.host',
        'AM__REQUEST_CONTEXT_SCHEME' => 'router.request_context.scheme',
        'AM__REQUEST_CONTEXT_BASE_PATH' => 'request_context.base_path',
        'AM__REQUEST_CONTEXT_BASE_URL' => 'request_context.base_url'
    );

    public static function buildParameters(Event $event)
    {
        $configDir = __DIR__ . '/../../../app/config/';
        $realFile = $configDir . 'parameters.yml';
        $exists = is_file($realFile);

        $yamlParser = new Parser();
        $io = $event->getIO();

        if ($exists) {
            $io->write('<info>Updating the parameters.yml file.</info>');
        } else {
            $io->write('<info>Creating the parameters.yml file.</info>');
        }

        // Find the expected params
        $expectedValues = $yamlParser->parse(file_get_contents($configDir.'parameters.yml.dist'));
        if (!isset($expectedValues['parameters'])) {
            throw new \InvalidArgumentException('The parameters.yml.dist file seems invalid.');
        }
        $expectedParams = (array) $expectedValues['parameters'];

        // find the actual params
        $actualValues = array('parameters' => array());
        if ($exists) {
            $actualValues = array_merge($actualValues, $yamlParser->parse(file_get_contents($realFile)));
        }
        $actualParams = (array) $actualValues['parameters'];

        // Remove the outdated params
        foreach ($actualParams as $key => $value) {
            if (!array_key_exists($key, $expectedParams)) {
                unset($actualParams[$key]);
            }
        }

        // Add the params coming from the environment values
        $actualParams = array_replace($actualParams, self::getEnvValues());

        $actualParams = self::getParams($io, $expectedParams, $actualParams);

        file_put_contents($realFile, "# This file is auto-generated during the composer install\n" . Yaml::dump(array('parameters' => $actualParams)));
    }

    private static function getEnvValues()
    {
        $params = array();
        foreach (self::$map as $env => $param) {
            $value = getenv($env);
            if ($value) {
                $params[$param] = Inline::parse($value);
            }
        }

        return $params;
    }

    private static function getParams(IOInterface $io, array $expectedParams, array $actualParams)
    {
        // Simply use the expectedParams value as default for the missing params.
        if (!$io->isInteractive()) {
            return array_replace($expectedParams, $actualParams);
        }

        $isStarted = false;

        foreach ($expectedParams as $key => $message) {
            if (array_key_exists($key, $actualParams)) {
                continue;
            }

            if (!$isStarted) {
                $isStarted = true;
                $io->write('<comment>Some parameters are missing. Please provide them.</comment>');
            }

            $default = Inline::dump($message);
            $value = $io->ask(sprintf('<question>%s</question> (<comment>%s</comment>):', $key, $default), $default);

            $actualParams[$key] = Inline::parse($value);
        }

        return $actualParams;
    }
}
