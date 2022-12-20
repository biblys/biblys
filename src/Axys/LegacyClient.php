<?php

namespace Axys;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyClient
{
    private array $options;
    private string $baseUrl;
    private ?string $userToken;

    public function __construct(array $options = [], string $userToken = null)
    {
        $this->options = $options;

        if (!isset($this->options['host'])) {
            $this->options['host'] = 'axys.me';
        }

        if (!isset($this->options['protocol'])) {
            $this->options['protocol'] = 'https';
        }

        if (!isset($this->options['port'])) {
            $this->options['port'] = 80;
        }

        $port = null;
        if ($this->options['port'] !== 80) {
            $port = ':'.$this->options['port'];
        }

        $this->baseUrl = $this->options['protocol'].'://'.$this->options['host'].$port;

        $this->userToken = $userToken;
    }

    /**
     * @param Config $config
     * @param UrlGenerator $urlgenerator
     * @param Request $request
     * @return string
     * @throws PropelException
     */
    public static function buildMenu(
        Config       $config,
        UrlGenerator $urlgenerator,
        Request      $request
    ): string
    {
        $currentSite = CurrentSite::buildFromConfig($config);
        $site = $currentSite->getSite();
        $currentUser = CurrentUser::buildFromRequest($request);

        $menuEntries = [];

        if ($site->getWishlist()) {
            $menuEntries[] = '<li><a href="/pages/log_mywishes" rel="nofollow">mes envies</a></li>';
        }

        if ($currentSite->hasOptionEnabled("alerts")) {
            $menuEntries[] = '<li><a href="/pages/log_myalerts" rel="nofollow">mes alertes</a></li>';
        }

        if ($site->getVpc()) {
            $menuEntries[] = '<li><a href="/pages/log_myorders" rel="nofollow">mes commandes</a></li>';
        }

        if ($site->getShop()) {
            $menuEntries[] = '<li><a href="/pages/log_mybooks" rel="nofollow">mes achats</a></li>';
        }

        if ($currentSite->hasOptionEnabled("show_elibrary")) {
            $menuEntries[] = '<li><a href="/pages/log_myebooks" rel="nofollow">ma bibliothèque</a></li>';
        }

        if ($currentUser->hasPublisherRight()) {
            $menuEntries[] = '<li><a href="/pages/log_dashboard" rel="nofollow">tableau de bord</a></li>';
        }

        if ($currentUser->isAdminForSite($site)) {
            $menuEntries[] = '<li>
                <a href="' . $urlgenerator->generate('main_admin') . '" rel="nofollow">administration</a>
            </li>';
        }

        return '<ul id="addToAxysMenu" class="hidden">' . join($menuEntries) . '</ul>';
    }

    public function getWidgetUrl(): string
    {
        $url = $this->baseUrl."/widget.php";

        if ($this->userToken) {
            $url .= '?UID='.$this->userToken;
        }

        return $url;
    }
}
