<?php

namespace Biblys\Utils;

use Sinergi\BrowserDetector\Browser as BrowserDetector;

class Browser extends BrowserDetector
{
    const CHROME_MIN_VERSION  = 62,
          FIREFOX_MIN_VERSION = 56,
          EDGE_MIN_VERSION    = 15,
          SAFARI_MIN_VERSION  = 11,
          OPERA_MIN_VERSION   = 48;

    /**
     * Check to see if current browser is up to date
     * @return boolean
     */
    public function isUpToDate()
    {

        if ($this->getName() === 'Internet Explorer') {
            return false;
        }

        if ($version = $this->getMinimumVersion()) {
            if (version_compare($this->getVersion(), $this->getMinimumVersion(), '>')) {
                return true;
            }
            return false;
        }

        // if we don't know browser's last version
        return false;
    }

    /**
     * Get minimum version for current browser
     * @return boolean
     */
    public function getMinimumVersion()
    {
        if ($this->getName() == "Chrome") return self::CHROME_MIN_VERSION;
        elseif ($this->getName() == "Firefox") return self::FIREFOX_MIN_VERSION;
        elseif ($this->getName() == "Safari") return self::SAFARI_MIN_VERSION;
        elseif ($this->getName() == "Opera") return self::OPERA_MIN_VERSION;
        elseif ($this->getName() == "Edge") return self::EDGE_MIN_VERSION;
        else return false;
    }

        /**
     * Return warning or error message
     * @param type $mode
     * @return string
     */
    public function getUpdateAlert($mode = 'warning')
    {
        if ($this->getName() === 'Internet Explorer') {
            return '
                <p class="error">
                    <strong>Erreur : votre  navigateur Internet Explorer est obsolète.</strong><br>
                    Certaines fonctionnalités de cette page nécessitent l\'utilisation d\'un navigateur récent.<br>
                    Pour continuer, merci d\'en <a href="http://www.biblys.fr/pages/doc_browser-update">choisir un nouveau</a>.<br>
                </p>
            ';

        } elseif ($mode == 'error') {
            return '
                <p class="error">
                    <strong>Erreur : votre  navigateur est obsolète.</strong><br>
                    Certaines fonctionnalités de cette page nécessitent l\'utilisation d\'un navigateur récent.<br>
                    Pour continuer, merci de le <a href="http://www.biblys.fr/pages/doc_browser-update">mettre à jour</a>.<br>
                    <br>
                    Votre navigateur : '.$this->getName().' version '.$this->getVersion().'<br>
                    Version minimum requise : '.$this->getMinimumVersion().'
                </p>
            ';
        } elseif ($mode == 'warning') {
            return '
                <p class="warning">
                    <strong>Attention : votre navigateur est obsolète.</strong><br>
                    Vous devriez le <a href="http://www.biblys.fr/pages/doc_browser-update">mettre à jour</a> pour éviter des problèmes<br>
                    et profiter des fonctionnalités avancées de Biblys.<br>
                    <br>
                    Votre navigateur : '.$this->getName().' version '.$this->getVersion().'<br>
                    Version recommandée : '.$this->getMinimumVersion().'
                </p>
            ';
        }
    }
}
