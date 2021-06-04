<?php

namespace Framework\Composer;

use Composer\Console\Application;
use Exception;
use Symfony\Component\Console\Input\ArrayInput;

class ScriptRunner
{
    /**
     * @throws ComposerException
     * @throws Exception
     */
    public static function run(string $command)
    {
        global $config;

        // Set composer home
        $composer_home = $config->get('composer_home');
        if (!$composer_home) {
            throw new Exception("L'option `composer_home` doit être définie dans le fichier de configuration pour utiliser composer.");
        }
        putenv('COMPOSER_HOME='.$composer_home);

        // Change directory to Biblys root
        chdir(BIBLYS_PATH);

        // Updating composer packages
        $application = new Application();
        $application->setAutoExit(false);
        $output = new MessageAccumulator();
        $code = $application->run(new ArrayInput(['command' => $command]), $output);

        if ($code !== 0) {
            throw new ComposerException(
                "Une erreur est survenue lors de la mise à jour automatique des composants.",
                $output->getOutput()
            );
        }
    }
}