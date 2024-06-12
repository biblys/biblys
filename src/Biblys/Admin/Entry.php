<?php

namespace Biblys\Admin;

class Entry
{
    private $_name;
    private $_url;
    private $_target;
    private $_icon;
    private $_class;
    private $_hasClass = false;
    private $_taskCount;
    private $_subscription;
    private $_hasSubscription = false;
    private $_category;

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
            global $urlgenerator;
            $this->setUrl($urlgenerator->generate($options['path']));
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

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setTarget($target)
    {
        $this->_target = $target;
    }

    public function getTarget()
    {
        return $this->_target;
    }

    public function setIcon($icon)
    {
        $this->_icon = $icon;
    }

    public function getIcon()
    {
        return $this->_icon;
    }

    public function hasClass()
    {
        return $this->_hasClass;
    }

    public function setClass($class)
    {
        $this->_class = $class;
    }

    public function getClass()
    {
        return $this->_class;
    }

    public function setTaskCount($count)
    {
        $this->_taskCount = $count;
    }

    public function getTaskCount()
    {
        return $this->_taskCount;
    }

    public function setSubscription($subscription)
    {
        $this->_subscription = $subscription;
    }

    public function getSubscription()
    {
        return $this->_subscription;
    }

    public function hasSubscription()
    {
        return $this->_hasSubscription;
    }

    public function setCategory($category)
    {
        $this->_category = $category;
    }

    public function getCategory()
    {
        return $this->_category;
    }

    public static function findAll()
    {
        global $site, $config;

        // Biblys update available
        $updates = 0;
        $updater = new \PhpGitAutoupdate(BIBLYS_PATH, BIBLYS_VERSION);
        $diff = time() - $site->getOpt('updates_last_checked');
        if ($diff > 60 * 60 * 24) {
            $updater->downloadUpdates();
            $site->setOpt('updates_last_checked', time());
        }
        if ($updater->updateAvailable()) {
            $updates = 1;
        }

        // Orders to be shipped
        $om = new \OrderManager();
        $orders = $om->count(['order_type' => 'web', 'order_payment_date' => 'NOT NULL', 'order_shipping_date' => 'NULL', 'order_cancel_date' => 'NULL']);

        $entries = [];

        $entries[] = new Entry('Nouvel article', ['category' => 'articles', 'url' => '/pages/adm_article', 'icon' => 'book']);
        $entries[] = new Entry('Nouveau contributeur', ['category' => 'articles', 'url' => '/pages/adm_people', 'icon' => 'user']);
        $entries[] = new Entry('Rayons', ['category' => 'articles', 'path' => 'rayon_index', 'icon' => 'sort-amount-asc']);
        $entries[] = new Entry('Termes de recherche', ['category' => 'articles', 'path' => 'article_search_terms', 'icon' => 'search', 'subscription' => 'search-terms']);
        $entries[] = new Entry('Codes ISBN', ['category' => 'articles', 'url' => '/pages/adm_isbn_codes', 'icon' => 'barcode']);
        $entries[] = new Entry('Catalogue', ['category' => 'articles', 'path' => 'article_admin_catalog', 'icon' => 'list-alt']);

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
        $entries[] = new Entry('Frais de port', ['category' => 'sales', 'path' => 'shipping_admin', 'icon' => 'envelope']);
        $entries[] = new Entry('Financement particip.', ['category' => 'sales', 'path' => 'cf_campaign_list', 'icon' => 'money']);

        $entries[] = new Entry('Ventes numériques', ['category' => 'ebooks', 'url' => '/pages/adm_ebooks', 'icon' => 'book']);
        $entries[] = new Entry('Envoyer livre num.', ['category' => 'ebooks', 'url' => '/pages/adm_ebooks_send', 'icon' => 'send']);

        $entries[] = new Entry('Pages', ['category' => 'content', 'url' => '/pages/adm_pages', 'icon' => 'file']);
        $entries[] = new Entry('Billets', ['category' => 'content', 'url' => '/pages/adm_posts', 'icon' => 'newspaper-o']);
        $entries[] = new Entry('Évènements', ['category' => 'content', 'url' => '/pages/log_events_admin', 'icon' => 'calendar']);
        $entries[] = new Entry('Médias', ['category' => 'content', 'url' => '/pages/adm_media', 'icon' => 'image']);
        $entries[] = new Entry('Envoyer newsletter', ['category' => 'content', 'url' => '/pages/adm_newsletter_send', 'icon' => 'send']);
        $entries[] = new Entry('Abonnés newsletter', ['category' => 'content', 'url' => '/pages/adm_newsletter_users', 'icon' => 'address-book']);

        $entries[] = new Entry('Chiffre d\'affaires', ['category' => 'stats', 'url' => '/pages/adm_revenue', 'icon' => 'money']);
        $entries[] = new Entry('Best-sellers', ['category' => 'stats', 'url' => '/pages/adm_stats_best-sellers', 'icon' => 'sort-amount-desc']);
        $entries[] = new Entry('Suivi des conversions', ['category' => 'stats', 'path' => 'orders_conversions', 'icon' => 'handshake-o']);
        $entries[] = new Entry('C.A. par éditeur', ['category' => 'stats', 'url' => '/pages/adm_stats_publishers', 'icon' => 'book']);
        $entries[] = new Entry('C.A. par fournisseur', ['category' => 'stats', 'path' => 'stats_suppliers', 'icon' => 'truck']);
        $entries[] = new Entry('C.A. par facture', ['category' => 'stats', 'url' => '/pages/adm_stats_invoices', 'icon' => 'file']);
        $entries[] = new Entry('Ventes par article', ['category' => 'stats', 'url' => '/pages/adm_sales_articles', 'icon' => 'book']);
        $entries[] = new Entry('Livres recherchés', ['category' => 'stats', 'url' => '/pages/adm_alerts', 'icon' => 'bell']);
        $entries[] = new Entry('Exemplaires perdus', ['category' => 'stats', 'path' => 'stats_lost', 'icon' => 'compass']);

        // Site
        $entries[] = new Entry('Administrateurs', ['category' => 'site', 'url' => '/pages/adm_admins', 'icon' => 'users']);

        $matomo = $config->get('matomo');
        if ($matomo) {
            $name = $matomo['name'] ?? 'Matomo Analytics';
            $loginUrl = isset($matomo['login']) && isset($matomo['md5pass']) ?
                'index.php?module=Login&action=logme&login='.$matomo['login'].'&password='.$matomo['md5pass'] :
                '';
            $entries[] = new Entry($name, [
                'category' => 'site',
                'url' => 'https://'.$matomo['domain'].'/'.$loginUrl,
                'target' => '_blank',
                'icon' => 'area-chart',
            ]);
        }

        $entries[] = new Entry('Options', ['category' => 'site', 'path' => 'site_options', 'icon' => 'cogs']);
        $entries[] = new Entry('Valeurs par défaut', ['category' => 'site', 'path' => 'site_default_values', 'icon' => 'pencil-square-o']);
        $entries[] = new Entry('Éditeur de thème', ['category' => 'site', 'path' => 'template_index', 'icon' => 'code']);
        $entries[] = new Entry('Tâches planifiées', ['category' => 'site', 'path' => 'crons_tasks', 'icon' => 'clock-o']);

        $entries[] = new Entry('Support Biblys', ['category' => 'biblys', 'path' => 'ticket_index', 'icon' => 'medkit']);
        $entries[] = new Entry('Documentation', ['category' => 'biblys', 'url' => 'https://www.biblys.fr/pages/doc_index', 'icon' => 'book']);
        $entries[] = new Entry('Mise à jour', ['category' => 'biblys', 'path' => 'maintenance_update', 'icon' => 'cloud-download', 'taskCount' => $updates]);
        $entries[] = new Entry('Composants', ['category' => 'biblys', 'path' => 'maintenance_composer', 'icon' => 'puzzle-piece']);

        return $entries;
    }

    public static function getCustomEntries()
    {
        global $site;

        $entries = json_decode($site->getOpt('admin_entries'));

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

    public static function findByCategory($category)
    {
        if ($category === 'custom') {
            return self::getCustomEntries();
        }

        $entries = self::findAll();

        return array_filter($entries, function ($entry) use ($category) {
            return $entry->getCategory() == $category;
        });
    }
}
