<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'default' => 
  array (
    'tablesByName' => 
    array (
      'alerts' => '\\Model\\Map\\AlertTableMap',
      'articles' => '\\Model\\Map\\ArticleTableMap',
      'awards' => '\\Model\\Map\\AwardTableMap',
      'axys_apps' => '\\Model\\Map\\AxysAppTableMap',
      'bookshops' => '\\Model\\Map\\BookshopTableMap',
      'carts' => '\\Model\\Map\\CartTableMap',
      'categories' => '\\Model\\Map\\CategoryTableMap',
      'cf_campaigns' => '\\Model\\Map\\CrowdfundingCampaignTableMap',
      'cf_rewards' => '\\Model\\Map\\CrowfundingRewardTableMap',
      'collections' => '\\Model\\Map\\BookCollectionTableMap',
      'countries' => '\\Model\\Map\\CountryTableMap',
      'coupons' => '\\Model\\Map\\CouponTableMap',
      'cron_jobs' => '\\Model\\Map\\CronJobTableMap',
      'customers' => '\\Model\\Map\\CustomerTableMap',
      'cycles' => '\\Model\\Map\\CycleTableMap',
      'downloads' => '\\Model\\Map\\DownloadTableMap',
      'events' => '\\Model\\Map\\EventTableMap',
      'files' => '\\Model\\Map\\FileTableMap',
      'galleries' => '\\Model\\Map\\GalleryTableMap',
      'images' => '\\Model\\Map\\ImageTableMap',
      'inventory' => '\\Model\\Map\\InventoryTableMap',
      'inventory_item' => '\\Model\\Map\\InventoryItemTableMap',
      'jobs' => '\\Model\\Map\\JobTableMap',
      'langs' => '\\Model\\Map\\LangTableMap',
      'libraries' => '\\Model\\Map\\LibraryTableMap',
      'links' => '\\Model\\Map\\LinkTableMap',
      'lists' => '\\Model\\Map\\StockItemListTableMap',
      'mailing' => '\\Model\\Map\\MailingTableMap',
      'medias' => '\\Model\\Map\\MediaTableMap',
      'options' => '\\Model\\Map\\OptionTableMap',
      'orders' => '\\Model\\Map\\OrderTableMap',
      'pages' => '\\Model\\Map\\PageTableMap',
      'payments' => '\\Model\\Map\\PaymentTableMap',
      'people' => '\\Model\\Map\\PeopleTableMap',
      'permissions' => '\\Model\\Map\\PermissionTableMap',
      'posts' => '\\Model\\Map\\PostTableMap',
      'prices' => '\\Model\\Map\\PriceTableMap',
      'publishers' => '\\Model\\Map\\PublisherTableMap',
      'rayons' => '\\Model\\Map\\ArticleCategoryTableMap',
      'redirections' => '\\Model\\Map\\RedirectionTableMap',
      'rights' => '\\Model\\Map\\RightTableMap',
      'roles' => '\\Model\\Map\\RoleTableMap',
      'session' => '\\Model\\Map\\SessionTableMap',
      'shipping' => '\\Model\\Map\\ShippingFeeTableMap',
      'signings' => '\\Model\\Map\\SigningTableMap',
      'sites' => '\\Model\\Map\\SiteTableMap',
      'stock' => '\\Model\\Map\\StockTableMap',
      'subscriptions' => '\\Model\\Map\\SubscriptionTableMap',
      'suppliers' => '\\Model\\Map\\SupplierTableMap',
      'tags' => '\\Model\\Map\\TagTableMap',
      'users' => '\\Model\\Map\\UserTableMap',
      'votes' => '\\Model\\Map\\VoteTableMap',
      'wishes' => '\\Model\\Map\\WishTableMap',
      'wishlist' => '\\Model\\Map\\WishlistTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Alert' => '\\Model\\Map\\AlertTableMap',
      '\\Article' => '\\Model\\Map\\ArticleTableMap',
      '\\ArticleCategory' => '\\Model\\Map\\ArticleCategoryTableMap',
      '\\Award' => '\\Model\\Map\\AwardTableMap',
      '\\AxysApp' => '\\Model\\Map\\AxysAppTableMap',
      '\\BookCollection' => '\\Model\\Map\\BookCollectionTableMap',
      '\\Bookshop' => '\\Model\\Map\\BookshopTableMap',
      '\\Cart' => '\\Model\\Map\\CartTableMap',
      '\\Category' => '\\Model\\Map\\CategoryTableMap',
      '\\Country' => '\\Model\\Map\\CountryTableMap',
      '\\Coupon' => '\\Model\\Map\\CouponTableMap',
      '\\CronJob' => '\\Model\\Map\\CronJobTableMap',
      '\\CrowdfundingCampaign' => '\\Model\\Map\\CrowdfundingCampaignTableMap',
      '\\CrowfundingReward' => '\\Model\\Map\\CrowfundingRewardTableMap',
      '\\Customer' => '\\Model\\Map\\CustomerTableMap',
      '\\Cycle' => '\\Model\\Map\\CycleTableMap',
      '\\Download' => '\\Model\\Map\\DownloadTableMap',
      '\\Event' => '\\Model\\Map\\EventTableMap',
      '\\File' => '\\Model\\Map\\FileTableMap',
      '\\Gallery' => '\\Model\\Map\\GalleryTableMap',
      '\\Image' => '\\Model\\Map\\ImageTableMap',
      '\\Inventory' => '\\Model\\Map\\InventoryTableMap',
      '\\InventoryItem' => '\\Model\\Map\\InventoryItemTableMap',
      '\\Job' => '\\Model\\Map\\JobTableMap',
      '\\Lang' => '\\Model\\Map\\LangTableMap',
      '\\Library' => '\\Model\\Map\\LibraryTableMap',
      '\\Link' => '\\Model\\Map\\LinkTableMap',
      '\\Mailing' => '\\Model\\Map\\MailingTableMap',
      '\\Media' => '\\Model\\Map\\MediaTableMap',
      '\\Option' => '\\Model\\Map\\OptionTableMap',
      '\\Order' => '\\Model\\Map\\OrderTableMap',
      '\\Page' => '\\Model\\Map\\PageTableMap',
      '\\Payment' => '\\Model\\Map\\PaymentTableMap',
      '\\People' => '\\Model\\Map\\PeopleTableMap',
      '\\Permission' => '\\Model\\Map\\PermissionTableMap',
      '\\Post' => '\\Model\\Map\\PostTableMap',
      '\\Price' => '\\Model\\Map\\PriceTableMap',
      '\\Publisher' => '\\Model\\Map\\PublisherTableMap',
      '\\Redirection' => '\\Model\\Map\\RedirectionTableMap',
      '\\Right' => '\\Model\\Map\\RightTableMap',
      '\\Role' => '\\Model\\Map\\RoleTableMap',
      '\\Session' => '\\Model\\Map\\SessionTableMap',
      '\\ShippingFee' => '\\Model\\Map\\ShippingFeeTableMap',
      '\\Signing' => '\\Model\\Map\\SigningTableMap',
      '\\Site' => '\\Model\\Map\\SiteTableMap',
      '\\Stock' => '\\Model\\Map\\StockTableMap',
      '\\StockItemList' => '\\Model\\Map\\StockItemListTableMap',
      '\\Subscription' => '\\Model\\Map\\SubscriptionTableMap',
      '\\Supplier' => '\\Model\\Map\\SupplierTableMap',
      '\\Tag' => '\\Model\\Map\\TagTableMap',
      '\\User' => '\\Model\\Map\\UserTableMap',
      '\\Vote' => '\\Model\\Map\\VoteTableMap',
      '\\Wish' => '\\Model\\Map\\WishTableMap',
      '\\Wishlist' => '\\Model\\Map\\WishlistTableMap',
    ),
  ),
));
