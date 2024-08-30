<?php

namespace Biblys\Admin;

use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Cloud\CloudService;
use Biblys\Service\Config;
use Exception;
use GuzzleHttp\Client;
use OrderManager;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Entry
{
    private string $_name;
    private ?string $_path = null;
    private ?string $_url = null;
    private string $_target;
    private string $_icon;
    private string $_class;
    private bool $_hasClass = false;
    private int $_taskCount = 0;
    private string $_subscription;
    private bool $_hasSubscription = false;
    private string $_category;

    public function __construct($name, $options = [])
    {
        $this->setName($name);

        if (isset($options['url'])) {
            $this->setUrl($options['url']);
        }

        if (isset($options['target'])) {
            $this->setTarget($options['target']);
        } else {
            $this->setTarget('_self');
        }

        $this->setIcon('cog');
        if (isset($options['icon'])) {
            $this->setIcon($options['icon']);
        }

        if (isset($options['path'])) {
            $this->setPath($options['path']);
        }

        if (isset($options['class'])) {
            $this->_hasClass = true;
            $this->setClass($options['class']);
        }

        if (isset($options['taskCount'])) {
            $this->setTaskCount($options['taskCount']);
        }

        if (isset($options['subscription'])) {
            $this->_hasSubscription = true;
            $this->setSubscription($options['subscription']);
        }

        if (isset($options['category'])) {
            $this->setCategory($options['category']);
        }
    }

    public function setName($name): void
    {
        $this->_name = $name;
    }

    public function getName(): string
    {
        return $this->_name;
    }

    private function setPath(string $path): void
    {
        $this->_path = $path;
    }

    public function getPath(): ?string
    {
        return $this->_path;
    }

    public function setUrl($url): void
    {
        $this->_url = $url;
    }

    /**
     * @throws Exception
     */
    public function getUrl(): string
    {
        if ($this->_url === null) {
            throw new Exception(sprintf("Entry %s with path %s has no url",
                $this->getName(),
                $this->getPath()
            ));
        }
        return $this->_url;
    }

    public function setTarget($target): void
    {
        $this->_target = $target;
    }

    public function getTarget(): string
    {
        return $this->_target;
    }

    public function setIcon($icon): void
    {
        $this->_icon = $icon;
    }

    public function getIcon(): string
    {
        return $this->_icon;
    }

    public function hasClass(): bool
    {
        return $this->_hasClass;
    }

    public function setClass($class): void
    {
        $this->_class = $class;
    }

    public function getClass(): string
    {
        return $this->_class;
    }

    public function setTaskCount($count): void
    {
        $this->_taskCount = $count;
    }

    public function getTaskCount(): int
    {
        return $this->_taskCount;
    }

    public function setSubscription($subscription): void
    {
        $this->_subscription = $subscription;
    }

    public function getSubscription(): string
    {
        return $this->_subscription;
    }

    public function hasSubscription(): bool
    {
        return $this->_hasSubscription;
    }

    public function setCategory($category): void
    {
        $this->_category = $category;
    }

    public function getCategory(): string
    {
        return $this->_category;
    }

    /**
     * @return array
     */
    public static function findAll(): array
    {
        $config = \Biblys\Legacy\LegacyCodeHelper::getGlobalConfig();;

        // Orders to be shipped
        $om = new OrderManager();
        $orders = $om->count(['order_type' => 'web', 'order_payment_date' => 'NOT NULL', 'order_shipping_date' => 'NULL', 'order_cancel_date' => 'NULL']);

        $entries = [];

        $entries[] = new Entry('Nouvel article', ['category' => 'articles', 'url' => '/pages/article_edit', 'icon' => 'book']);
        $entries[] = new Entry('Rayons', ['category' => 'articles', 'path' => 'rayon_index', 'icon' => 'sort-amount-asc']);
        $entries[] = new Entry('Termes de recherche', ['category' => 'articles', 'path' => 'article_search_terms', 'icon' => 'search', 'subscription' => 'search-terms']);
        $entries[] = new Entry('Codes ISBN', ['category' => 'articles', 'url' => '/pages/adm_isbn_codes', 'icon' => 'barcode']);
        $entries[] = new Entry('Catalogue', ['category' => 'articles', 'path' => 'article_admin_catalog', 'icon' => 'list-alt']);
        $entries[] = new Entry('Offres spéciales', ['category' => 'articles', 'path' => 'special_offer_index', 'icon' => 'certificate']);

        $entries[] = new Entry('Ajouter au stock', ['category' => 'stock', 'url' => '#', 'icon' => 'plus', 'class' => 'stockQuickAdd']);
        $entries[] = new Entry('Stocks', ['category' => 'stock', 'url' => '/pages/adm_stocks', 'icon' => 'cubes']);
        $entries[] = new Entry('Listes', ['category' => 'stock', 'url' => '/pages/list', 'icon' => 'list']);
        $entries[] = new Entry('Réassort', ['category' => 'stock', 'url' => '/pages/adm_reorder', 'icon' => 'refresh']);
        $entries[] = new Entry('Fournisseurs', ['category' => 'stock', 'url' => '/pages/adm_suppliers', 'icon' => 'truck']);
        $entries[] = new Entry('Inventaires', ['category' => 'stock', 'path' => 'inventory_index', 'icon' => 'check']);
        $entries[] = new Entry('États des stocks', ['category' => 'stock', 'url' => '/pages/adm_stock_status', 'icon' => 'bar-chart']);

        $entries[] = new Entry('Clients', ['category' => 'sales', 'url' => '/pages/adm_customers', 'icon' => 'address-card']);
        $entries[] = new Entry('Caisse', ['category' => 'sales', 'url' => '/pages/adm_checkout', 'icon' => 'money']);
        $entries[] = new Entry('Ventes', ['category' => 'sales', 'url' => '/pages/adm_orders_shop', 'icon' => 'line-chart']);
        $entries[] = new Entry('Commandes', ['category' => 'sales', 'path' => 'order_index', 'icon' => 'dropbox', 'taskCount' => $orders, 'subscription' => 'orders']);
        $entries[] = new Entry('Paniers', ['category' => 'sales', 'url' => '/pages/adm_carts', 'icon' => 'shopping-basket', 'subscription' => 'carts']);
        $entries[] = new Entry('Paiements', ['category' => 'sales', 'path' => 'payments_index', 'icon' => 'credit-card']);
        $entries[] = new Entry('Frais de port', ['category' => 'sales', 'path' => 'shipping_admin', 'icon' => 'envelope']);
        $entries[] = new Entry('Financement particip.', ['category' => 'sales', 'path' => 'cf_campaign_list', 'icon' => 'money']);

        $entries[] = new Entry('Ventes numériques', ['category' => 'ebooks', 'url' => '/pages/adm_ebooks', 'icon' => 'book']);
        $entries[] = new Entry('Invitations de téléchargement', ['category' => 'ebooks', 'path' => 'invitation_list', 'icon' => 'send']);

        $entries[] = new Entry('Pages', ['category' => 'content', 'url' => '/pages/adm_pages', 'icon' => 'file']);
        $entries[] = new Entry('Billets', ['category' => 'content', 'url' => '/pages/adm_posts', 'icon' => 'newspaper-o']);
        $entries[] = new Entry('Évènements', ['category' => 'content', 'url' => '/pages/log_events_admin', 'icon' => 'calendar']);
        $entries[] = new Entry('Médias', ['category' => 'content', 'url' => '/pages/adm_media', 'icon' => 'image']);
        $entries[] = new Entry('Liste de contacts', ['category' => 'content', 'path' => 'mailing_contacts', 'icon' => 'address-book']);

        $entries[] = new Entry('Chiffre d\'affaires', ['category' => 'stats', 'url' => '/pages/adm_revenue', 'icon' => 'money']);
        $entries[] = new Entry('Best-sellers', ['category' => 'stats', 'url' => '/pages/adm_stats_best-sellers', 'icon' => 'sort-amount-desc']);
        $entries[] = new Entry('C.A. par fournisseur', ['category' => 'stats', 'path' => 'stats_suppliers', 'icon' => 'truck']);
        $entries[] = new Entry('C.A. par facture', ['category' => 'stats', 'url' => '/pages/adm_stats_invoices', 'icon' => 'file']);
        $entries[] = new Entry('Ventes par article', ['category' => 'stats', 'url' => '/pages/adm_sales_articles', 'icon' => 'book']);
        $entries[] = new Entry('Livres recherchés', ['category' => 'stats', 'url' => '/pages/adm_alerts', 'icon' => 'bell']);
        $entries[] = new Entry('Exemplaires perdus', ['category' => 'stats', 'path' => 'stats_lost', 'icon' => 'compass']);

        // Site
        $entries[] = new Entry('Utilisateur·trice·s', ['category' => 'site', 'path' => 'user_index', 'icon' => 'user']);
        $entries[] = new Entry('Administrateur·trice·s', ['category' => 'site', 'url' => '/pages/adm_admins', 'icon' => 'users']);

        $entries = self::_addAnalyticsLinks($config, $entries);

        $entries[] = new Entry('Espace disque', ['category' => 'site', 'path' => 'maintenance_disk_usage', 'icon' => 'database']);
        $entries[] = new Entry('Options', ['category' => 'site', 'path' => 'site_options', 'icon' => 'cogs']);
        $entries[] = new Entry('Valeurs par défaut', ['category' => 'site', 'path' => 'site_default_values', 'icon' => 'pencil-square-o']);
        $entries[] = new Entry('Éditeur de thème', ['category' => 'site', 'path' => 'template_index', 'icon' => 'code']);
        $entries[] = new Entry('Tâches planifiées', ['category' => 'site', 'path' => 'crons_tasks', 'icon' => 'clock-o']);

        $entries[] = new Entry('Améliorer Biblys', ['category' => 'biblys', 'url' => 'https://ameliorer.biblys.cloud', 'icon' => 'lightbulb-o']);
        $cloud = new CloudService($config, new Client());
        if ($cloud->isConfigured()) {
            $entries[] = new Entry('Abonnement Cloud', ['category' => 'biblys', 'path' => 'main_admin_cloud', 'icon' => 'cloud']);
        }
        $entries[] = new Entry('Documentation', ['category' => 'biblys', 'url' => 'https://docs.biblys.fr/', 'icon' => 'book']);
        $entries[] = new Entry('Mises à jour', ['category' => 'biblys', 'url' => 'https://github.com/biblys/biblys/releases/', 'icon' => 'cloud-download']);

        return $entries;
    }


    /**
     * @return Entry[]
     */
    public static function getCustomEntries(): array
    {
        $globalSite = LegacyCodeHelper::getGlobalSite();

        $entries = json_decode($globalSite->getOpt('admin_entries'));

        // If JSON is malformatted
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [new Entry("L'option de site `admin_entries` n'est pas une chaîne JSON valide.", ['category' => 'custom', 'path' => 'site_options'])];
        }

        $custom = [];
        if (count($entries)) {
            foreach ($entries as $entry) {
                $custom[] = new Entry(
                    $entry->name,
                    [
                        'url' => $entry->url,
                        'icon' => $entry->icon,
                        'category' => 'custom',
                    ]
                );
            }
        }

        return $custom;
    }

    /**
     * @param string $category
     * @return Entry[]
     */
    public static function findByCategory(string $category): array
    {
        if ($category === 'custom') {
            return self::getCustomEntries();
        }

        $entries = self::findAll();

        return array_filter($entries, function ($entry) use ($category) {
            return $entry->getCategory() == $category;
        });
    }

    /**
     * @param Entry[] $entries
     * @param UrlGenerator $generator
     * @return Entry[]
     */
    public static function generateUrlsForEntries(array $entries, UrlGenerator $generator): array
    {
        return array_map(function (Entry $entry) use($generator) {
            if ($entry->getPath() !== null) {
                $url = $generator->generate($entry->getPath());
                $entry->setUrl($url);
            }
            return $entry;
        }, $entries);
    }

    /**
     * @param Config $config
     * @param array $entries
     * @return array
     */
    public static function _addAnalyticsLinks(Config $config, array $entries): array
    {
        if ($config->has("matomo.login") && $config->has("matomo.md5pass")) {
            $entries[] = new Entry("Statistiques (Matomo)", ["category" => "site", "path" => "stats_matomo", 'icon' => 'area-chart']);
        }

        if ($config->has("umami.share_url")) {
            $entries[] = new Entry("Statistiques (Umami)", ["category" => "site", "path" => "stats_umami", 'icon' => 'area-chart']);
        }

        return $entries;
    }
}
