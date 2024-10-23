<?php
/*
 * Copyright (C) 2024 Clément Latzarus
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


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
        $config = Config::load();

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