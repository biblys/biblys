<?php


namespace Framework;


use Composer\Console\Application;
use Exception;
use Symfony\Component\Console\Input\ArrayInput;

class Composer
{
    /**
     * @throws Exception
     */
    public static function runScript(string $command)
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
        $code = $application->run(new ArrayInput(['command' => $command]));

        if ($code !== 0) {
            throw new Exception('Une erreur est survenue lors de la mise à jour automatique
                    des composants.');
        }
    }
}