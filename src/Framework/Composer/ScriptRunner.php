<?php

namespace Framework\Composer;

use Biblys\Service\Config;
use Composer\Console\Application;
use Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ScriptRunner
{
    /**
     * @throws ComposerException
     * @throws Exception
     */
    public static function run(string $command)
    {
        $config = new Config();

        // Set composer home
        $composer_home = $config->get('composer_home');
        if (!$composer_home) {
            throw new Exception("L'option `composer_home` doit être définie dans le fichier de configuration pour utiliser composer.");
        }
        putenv('COMPOSER_HOME='.$composer_home);

        chdir(__DIR__."/../../../");

        $application = new Application();
        $application->setAutoExit(false);
        $output = new BufferedOutput();
        $code = $application->run(new ArrayInput(['command' => $command]), $output);

        if ($code !== 0) {
            throw new ComposerException(
                "Une erreur est survenue lors de l'execution du script `$command`.",
                $output->fetch(),
                $code,
            );
        }
    }
}