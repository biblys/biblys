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


namespace Biblys\Test;

use Biblys\Data\ArticleType;
use Biblys\Service\Slug\SlugService;
use DateTime;
use Model\Alert;
use Model\Article;
use Model\ArticleCategory;
use Model\AuthenticationMethod;
use Model\CountryQuery;
use Model\Customer;
use Model\Event;
use Model\File;
use Model\Gallery;
use Model\Image;
use Model\Invitation;
use Model\BookCollection;
use Model\Cart;
use Model\Country;
use Model\CrowdfundingCampaign;
use Model\CrowfundingReward;
use Model\MediaFile;
use Model\Option;
use Model\Link;
use Model\Order;
use Model\Page;
use Model\Payment;
use Model\People;
use Model\Post;
use Model\Publisher;
use Model\Right;
use Model\Role;
use Model\Session;
use Model\ShippingFee;
use Model\Site;
use Model\SpecialOffer;
use Model\Stock;
use Model\StockItemList;
use Model\Subscription;
use Model\User;
use Model\Vote;
use Model\Wish;
use Model\Wishlist;
use Propel\Runtime\Exception\PropelException;

class ModelFactory
{
    /**
     * @throws PropelException
     */
    public static function createArticle(
        string         $title = "Article",
        array          $authors = [],
        string         $ean = "9781234567890",
        string         $url = "author/article",
        int            $price = 999,
        int            $typeId = ArticleType::BOOK,
        string         $keywords = null,
        string         $lemoninkMasterId = null,
        Publisher      $publisher = null,
        BookCollection $collection = null,
        bool           $isPriceEditable = false,
        DateTime       $publicationDate = null,
        int            $availabilityDilicom = 1,
    ): Article
    {
        $article = new Article();
        $article->setTitle($title);
        $article->setEan($ean);
        $article->setUrl($url);
        $article->setPrice($price);
        $article->setKeywords($keywords ?? $title);
        $article->setTypeId($typeId);
        $article->setLemonInkMasterId($lemoninkMasterId);
        $article->setPriceEditable($isPriceEditable);
        $article->setPubdate($publicationDate);
        $article->setAvailabilityDilicom($availabilityDilicom);

        $publisher = $publisher ?? self::createPublisher();
        $article->setPublisherId($publisher->getId());
        $article->setPublisherName($publisher->getName());

        $collection = $collection ?? self::createCollection();
        $article->setCollectionId($collection->getId());
        $article->setCollectionName($collection->getName());

        $authorNames = array_map(function ($author) use ($article) {
            self::createContribution($article, $author);
            return $author->getFullName();
        }, $authors);
        $authorsString = implode(", ", $authorNames);
        $article->setAuthors($authorsString);

        $article->save();

        return $article;
    }

    /**
     * @throws PropelException
     */
    public static function createArticleCategory(
        Site   $site,
        string $name = "Rayon de lune",
    ): ArticleCategory
    {
        $category = new ArticleCategory();
        $category->setName($name);
        $category->setSite($site);
        $category->save();

        return $category;
    }

    /**
     * @throws PropelException
     */
    public static function createCart(
        Site   $site = null,
        User   $user = null,
        int    $axysAccountId = null,
        int    $sellerId = null,
        string $uniqueId = null,
        int    $amount = 0,
        int    $count = 0,
    ): Cart
    {
        $cart = new Cart();
        $cart->setUid($uniqueId ?? "cart-uid");
        $cart->setSite($site ?? self::createSite());
        $cart->setUser($user);
        $cart->setAxysAccountId($axysAccountId);
        $cart->setSellerId($sellerId); // axys_account_id
        $cart->setAmount($amount);
        $cart->setCount($count);
        $cart->save();

        return $cart;
    }

    /**
     * @throws PropelException
     */
    public static function createCollection(
        Publisher $publisher = null,
        string    $name = "La Blanche",
        int       $noosfereId = null,
    ): BookCollection
    {
        $slugService = new SlugService();

        $collection = new BookCollection();
        $collection->setName($name);
        $publisher = $publisher ?? self::createPublisher();
        $collection->setPublisherId($publisher->getId());
        $slug = $slugService->createForBookCollection(
            $collection->getName(),
            $publisher->getName()
        );
        $collection->setUrl($slug);
        $collection->setNoosfereId($noosfereId);
        $collection->save();

        return $collection;
    }

    /**
     * @throws PropelException
     */
    public static function createCountry(
        string $name = "France",
        string $zone = "F",
    ): Country
    {
        $country = new Country();
        $country->setName($name);
        $country->setCode("FR");
        $country->setShippingZone($zone);
        $country->save();

        return $country;
    }

    /**
     * @throws PropelException
     */
    public static function createCrowdfundingReward($attributes = []): CrowfundingReward
    {
        $article = ModelFactory::createArticle();

        $reward = new CrowfundingReward();
        $reward->setContent("A beautiful reward");
        $reward->setArticles("[{$article->getId()}]");
        $reward->setQuantity($attributes["quantity"] ?? 1);
        $reward->setSiteId($attributes["site_id"] ?? 1);
        $reward->setLimited($attributes["limited"] ?? 1);

        if (!isset($attributes["campaign_id"])) {
            $campaign = ModelFactory::createCrowdfundingCampaign(
                ["site_id" => $attributes["site_id"] ?? 1]
            );
            $attributes["campaign_id"] = $campaign->getId();
        }
        $reward->setCampaignId($attributes["campaign_id"]);

        $reward->save();

        return $reward;
    }

    /**
     * @throws PropelException
     */
    public static function createCrowdfundingCampaign($attributes = []): CrowdfundingCampaign
    {
        $campaign = new CrowdfundingCampaign();
        $campaign->setTitle("A beautiful campaign");
        $campaign->setSiteId($attributes["site_id"] ?? 1);
        $campaign->setEnds($attributes["ends"] ?? "2030-01-01");
        $campaign->save();

        return $campaign;
    }

    /**
     * @throws PropelException
     */
    public static function createLink(
        Article         $article = null,
        ArticleCategory $articleCategory = null,
        Article         $bundleArticle = null,
    ): Link
    {
        $link = new Link();
        $link->setArticle($article);
        $link->setArticleCategory($articleCategory);
        $link->setBundleId($bundleArticle?->getId());
        $link->save();

        return $link;
    }

    /**
     * @throws PropelException
     */
    public static function createOrder(
        Site        $site = null,
        User        $user = null,
        Customer    $customer = null,
        ShippingFee $shippingFee = null,
        string      $axysAccountId = null,
        string      $slug = null,
        string      $firstName = "Silas",
        string      $lastName = "Coade",
        string      $address1 = "1 rue de la Fissure",
        string      $address2 = "Appartement 2",
        string      $postalCode = "33000",
        string      $city = "Bordeaux",
        Country     $country = null,
        string      $phone = "0601020304",
        string      $email = "silas.coade@example.net",
        string      $mondialRelayPickupPointCode = null,
    ): Order
    {
        $customer = $customer ?? ModelFactory::createCustomer($site, $user);
        $shipping = $shippingFee ?? ModelFactory::createShippingFee();
        $country = $country ?? CountryQuery::create()->findOneByCode("FR");

        $order = new Order();
        $order->setSite($site ?? ModelFactory::createSite());
        $order->setUser($user);
        $order->setShippingId($shipping->getId());
        $order->setShippingMode($shipping->getType());
        $order->setType("web");
        $order->setAxysAccountId($axysAccountId);
        $order->setSlug($slug ?? "order-slug");
        $order->setCustomerId($customer->getId());
        $order->setFirstname($firstName);
        $order->setLastname($lastName);
        $order->setAddress1($address1);
        $order->setAddress2($address2);
        $order->setPostalcode($postalCode);
        $order->setCity($city);
        $order->setCountryId($country->getId());
        $order->setPhone($phone);
        $order->setEmail($email);
        $order->setMondialRelayPickupPointCode($mondialRelayPickupPointCode);

        $order->save();

        return $order;
    }

    /**
     * @throws PropelException
     */
    public static function createPage(array $attributes = []): Page
    {
        $page = new Page();
        $page->setTitle($attributes["page_title"] ?? "Conditions Générales de Vente");
        $page->setUrl($attributes["page_url"] ?? "cgv");
        $page->setSiteId($attributes["site_id"] ?? 1);
        $page->setStatus($attributes["status"] ?? 1);
        $page->setContent($attributes["content"] ?? "Veuillez lire attentivement le texte suivant.");
        $page->save();

        return $page;
    }

    /**
     * @throws PropelException
     */
    public static function createPayment(
        array  $attributes,
        Site   $site,
        ?Order $order = null
    ): Payment
    {
        $payment = new Payment();
        $payment->setSite($site);
        $payment->setOrder($order ?? self::createOrder());
        $payment->setAmount($attributes["amount"] ?? 10000);
        $payment->setMode($attributes["mode"] ?? "stripe");
        $payment->setExecuted(
            array_key_exists("executed", $attributes) ? $attributes["executed"] : new DateTime()
        );
        $payment->save();

        return $payment;
    }

    /**
     * @throws PropelException
     *
     * @deprecated ModelFactory::createPeople is deprecated.
     *             Use ModelFactory::createContributor instead
     */
    public static function createPeople(array $attributes = []): People
    {
        $people = new People();
        $people->setFirstName($attributes["first_name"] ?? "Lili");
        $people->setLastName($attributes["last_name"] ?? "Raton");
        $people->setGender($attributes["gender"] ?? "N");
        $people->setUrl($attributes["slug"] ?? "slug");
        $people->save();

        return $people;
    }

    /**
     * @throws PropelException
     */
    public static function createContributor(
        string $firstName = "Lili",
        string $lastName = "Raton",
        string $gender = "N",
        string $url = "lili-raton",
    ): People
    {
        $contributor = new People();
        $contributor->setFirstName($firstName);
        $contributor->setLastName($lastName);
        $fullName = trim($contributor->getFirstName() . " " . $contributor->getLastName());
        $contributor->setName($fullName);
        $contributor->setGender($gender);
        $contributor->setUrl($url);
        $contributor->save();

        return $contributor;
    }

    /**
     * @throws PropelException
     */
    public static function createStockItem(
        ?Site    $site = null,
        ?Article $article = null,
        ?User    $user = null,
        ?Cart    $cart = null,
        Order    $order = null,
        int      $sellingPrice = 0,
        DateTime $sellingDate = null,
        DateTime $returnDate = null,
        DateTime $lostDate = null,
        int      $weight = null,
        string   $lemoninkTransactionId = null,
        string   $lemoninkTransactionToken = null,
        string   $axysAccountId = null,
    ): Stock
    {
        $stock = new Stock();
        $stock->setSite($site ?? self::createSite());
        $stock->setArticle($article ?? self::createArticle());
        $stock->setUser($user);
        $stock->setCart($cart);
        $stock->setCondition("Neuf");
        $stock->setOrderId($order?->getId());
        $stock->setSellingPrice($sellingPrice);
        $stock->setSellingDate($sellingDate);
        $stock->setReturnDate($returnDate);
        $stock->setLostDate($lostDate);
        $stock->setWeight($weight);
        $stock->setLemonInkTransactionId($lemoninkTransactionId);
        $stock->setLemonInkTransactionToken($lemoninkTransactionToken);
        $stock->setAxysAccountId($axysAccountId);
        $stock->save();

        return $stock;
    }

    /**
     * @throws PropelException
     */
    public static function createUserSession(User $user = null): Session
    {
        $user = $user ?? ModelFactory::createUser();

        $session = new Session();
        $session->setUser($user);
        $session->setSite($user->getSite());
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('tomorrow'));
        $session->save();

        return $session;
    }

    /**
     * @throws PropelException
     */
    public static function createSiteOption($site, $key, $value): void
    {
        $option = new Option();
        $option->setSite($site);
        $option->setKey($key);
        $option->setValue($value);
        $option->save();
    }

    /**
     * @throws PropelException
     */
    public static function createShippingFee(
        Site    $site = null,
        string  $type = "normal",
        Country $country = null,
        string  $mode = "Lettre verte",
        string  $info = null,
        int     $fee = 100,
        int     $maxWeight = 1000,
        int     $minAmount = 0,
        int     $maxAmount = 2000,
        int     $maxArticles = 10,
        bool    $isArchived = false,
    ): ShippingFee
    {
        $shippingFee = new ShippingFee();
        $shippingFee->setSiteId($site?->getId() ?? 1);
        $shippingFee->setZone($country?->getShippingZone() ?? "ALL");
        $shippingFee->setType($type);
        $shippingFee->setMode($mode);
        $shippingFee->setFee($fee);
        $shippingFee->setMinAmount($minAmount);
        $shippingFee->setMaxWeight($maxWeight);
        $shippingFee->setMaxAmount($maxAmount);
        $shippingFee->setMaxArticles($maxArticles);
        $shippingFee->setInfo($info);
        $shippingFee->setArchivedAt($isArchived ? new DateTime() : null);
        $shippingFee->save();

        return $shippingFee;
    }

    /**
     * @throws PropelException
     */
    public static function createPublisher(
        string $name = "Les Éditions Paronymie",
        string $url = "les-editions-paronymie.com",
        string $noosfereId = null,
    ): Publisher
    {
        $publisher = new Publisher();
        $publisher->setName($name);
        $publisher->setUrl($url);
        $publisher->setNoosfereId($noosfereId);
        $publisher->save();

        return $publisher;
    }

    /**
     * @throws PropelException
     */
    public static function createSite(
        string $title = "Éditions Paronymie",
        string $domain = "paronymie.fr",
        string $contact = "contact@paronymie.fr",
    ): Site
    {
        $site = new Site();
        $site->setName("paronymie");
        $site->setTitle($title);
        $site->setDomain($domain);
        $site->setContact($contact);
        $site->setTag("PAR");
        $site->save();

        return $site;
    }

    /**
     * @throws PropelException
     */
    public static function createContribution(Article $article, People $contributor): void
    {
        $contribution = new Role();
        $contribution->setArticle($article);
        $contribution->setPeople($contributor);
        $contribution->setJobId(1);
        $contribution->save();
    }

    /**
     * @throws PropelException
     */
    public static function createInvitation(
        Site     $site = null,
        array    $articles = [],
        string   $email = "invited-user@biblys.fr",
        string   $code = "ABCD1234",
        bool     $allowsPreDownload = false,
        DateTime $expiresAt = null,
        DateTime $consumedAt = null,
    ): Invitation
    {


        $invitation = new Invitation();
        $invitation->setSite($site ?? self::createSite());
        $invitation->setEmail($email);
        $invitation->setCode($code);
        $invitation->setAllowsPreDownload($allowsPreDownload);
        $invitation->setExpiresAt($expiresAt ?? strtotime("+1 month"));
        $invitation->setConsumedAt($consumedAt);

        if (count($articles) === 0) {
            $articles = [self::createArticle()];
        }
        foreach ($articles as $article) {
            $invitation->addArticle($article);
        }

        $invitation->save();

        return $invitation;
    }

    /**
     * @throws PropelException
     */
    public static function createUser(
        Site   $site = null,
        string $email = "user@biblys.fr",
    ): User
    {
        $user = new User();
        $user->setSite($site ?? self::createSite());
        $user->setEmail($email);
        $user->save();

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createAuthenticationMethod(
        Site   $site = null,
        User   $user = null,
        string $identityProvider = "axys",
        string $externalId = "AXYS1234",
    ): AuthenticationMethod
    {
        $authenticationMethod = new AuthenticationMethod();
        $authenticationMethod->setSite($site ?? self::createSite());
        $authenticationMethod->setUser($user ?? self::createUser());
        $authenticationMethod->setIdentityProvider($identityProvider);
        $authenticationMethod->setExternalId($externalId);
        $authenticationMethod->save();

        return $authenticationMethod;
    }

    /**
     * @throws PropelException
     */
    public static function createAdminUser(Site $site = null): User
    {
        $site = $site ?? self::createSite();

        $user = self::createUser($site);
        self::createRight(user: $user, site: $site, isAdmin: true);

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createPublisherUser(
        Site      $site = null,
        Publisher $publisher = null,
    ): User
    {
        $site = $site ?? self::createSite();
        $publisher = $publisher ?? self::createPublisher();

        $user = self::createUser($site);
        self::createRight(user: $user, site: $site, publisher: $publisher);

        return $user;
    }

    /**
     * @throws PropelException
     */
    public static function createRight(
        ?User     $user = null,
        ?Site     $site = null,
        Publisher $publisher = null,
        bool      $isAdmin = false,
        string    $axysAccountId = null,
    ): Right
    {
        $right = new Right();
        $right->setUser($user);
        $right->setSite($site);
        $right->setPublisher($publisher);
        $right->setIsAdmin($isAdmin);
        $right->setAxysAccountId($axysAccountId);
        $right->save();

        return $right;
    }

    /**
     * @throws PropelException
     */
    public static function createCustomer(
        Site   $site = null,
        User   $user = null,
        string $axysAccountId = null,
    ): Customer
    {
        $customer = new Customer();

        $customer->setSite($site);
        $customer->setUser($user);
        $customer->setAxysAccountId($axysAccountId);
        $customer->save();

        return $customer;
    }

    /**
     * @throws PropelException
     */
    public static function createStockItemList(
        Site   $site,
        string $axysAccountId = null,
    ): StockItemList
    {
        $stockItemList = new StockItemList();

        $stockItemList->setSite($site);
        $stockItemList->setAxysAccountId($axysAccountId);
        $stockItemList->save();

        return $stockItemList;
    }

    /**
     * @throws PropelException
     */
    public static function createUserOption(
        Site   $site,
        string $axysAccountId = null
    ): Option
    {
        $option = new Option();

        $option->setSite($site);
        $option->setAxysAccountId($axysAccountId);
        $option->save();

        return $option;
    }

    /**
     * @throws PropelException
     */
    public static function createPost(
        Site     $site = null,
        string   $title = "Une actualité",
        bool     $status = Post::STATUS_ONLINE,
        DateTime $date = new DateTime(),
        string   $axysAccountId = null,
    ): Post
    {
        $slugService = new SlugService();

        $post = new Post();

        $post->setSite($site ?? self::createSite());
        $post->setTitle($title);
        $post->setUrl($slugService->slugify($title));
        $post->setStatus($status);
        $post->setDate($date);
        $post->setCreatedAt(new DateTime());
        $post->setUpdatedAt(new DateTime());
        $post->setAxysAccountId($axysAccountId);
        $post->save();

        return $post;
    }

    /**
     * @throws PropelException
     */
    public static function createSubscription(
        Site   $site,
        string $axysAccountId = null
    ): Subscription
    {
        $subscription = new Subscription();

        $subscription->setSite($site);
        $subscription->setAxysAccountId($axysAccountId);
        $subscription->save();

        return $subscription;
    }

    /**
     * @throws PropelException
     */
    public static function createAlert(
        Site    $site = null,
        User    $user = null,
        Article $article = null,
        string  $axysAccountId = null): Alert
    {
        $alert = new Alert();

        $alert->setSite($site);
        $alert->setUser($user);
        $alert->setArticleId($article?->getId());
        $alert->setAxysAccountId($axysAccountId);
        $alert->save();

        return $alert;
    }

    /**
     * @throws PropelException
     */
    public static function createVote(string $axysAccountId = null): Vote
    {
        $vote = new Vote();

        $vote->setAxysAccountId($axysAccountId);
        $vote->save();

        return $vote;
    }

    /**
     * @throws PropelException
     */
    public static function createWishlist(
        Site   $site = null,
        User   $user = null,
        string $axysAccountId = null,
    ): Wishlist
    {
        $wishlist = new Wishlist();

        $wishlist->setSite($site);
        $wishlist->setUser($user);
        $wishlist->setAxysAccountId($axysAccountId);
        $wishlist->save();

        return $wishlist;
    }

    /**
     * @throws PropelException
     */
    public static function createWish(
        Wishlist $wishlist = null,
        Article  $article = null,
        string   $axysAccountId = null,
    ): Wish
    {
        $wish = new Wish();

        $article = $article ?? ModelFactory::createArticle();

        $wish->setSiteId($wishlist->getSiteId());
        $wish->setUser($wishlist->getUser());
        $wish->setWishlistId($wishlist->getId());
        $wish->setArticleId($article->getId());
        $wish->setAxysAccountId($axysAccountId);
        $wish->save();

        return $wish;
    }

    /**
     * @throws PropelException
     */
    public static function createSpecialOffer(
        Site           $site,
        string         $name = "Offre spéciale",
        BookCollection $targetCollection = null,
        Article        $freeArticle = null,
        int            $targetQuantity = 2,
        DateTime       $startDate = new DateTime("- 1 day"),
        DateTime       $endDate = new DateTime("+ 1 day"),
    ): SpecialOffer
    {
        $specialOffer = new SpecialOffer();
        $specialOffer->setSite($site);
        $specialOffer->setName($name);
        $specialOffer->setTargetCollection($targetCollection ?? self::createCollection());
        $specialOffer->setTargetQuantity($targetQuantity);
        $specialOffer->setFreeArticle($freeArticle ?? ModelFactory::createArticle());
        $specialOffer->setStartDate($startDate);
        $specialOffer->setEndDate($endDate);
        $specialOffer->save();

        return $specialOffer;
    }

    /**
     * @throws PropelException
     */
    public static function createImage(
        Article   $article = null,
        Stock     $stockItem = null,
        Post      $post = null,
        Publisher $publisher = null,
        People    $contributor = null,
        Event     $event = null,
        Site      $site = null,
        string    $type = null,
        string    $filePath = "/images/",
        string    $fileName = "image.jpg",
        int       $fileSize = 100,
        int       $version = 1,
    ): Image
    {
        $image = new Image();
        $image->setType($type);
        $image->setArticle($article);
        $image->setStockItem($stockItem);
        $image->setPost($post);
        $image->setPublisher($publisher);
        $image->setContributor($contributor);
        $image->setEvent($event);
        $image->setSite($site);
        $image->setFilePath($filePath);
        $image->setFileName($fileName);
        $image->setFileSize($fileSize);
        $image->setVersion($version);
        $image->save();

        return $image;
    }

    /**
     * @throws PropelException
     */
    public static function createDownloadableFile(
        Article $article = null,
        int     $fileSize = 100,
    ): File
    {
        $file = new File();
        $file->setArticleId($article->getId());
        $file->setSize($fileSize);
        $file->save();

        return $file;
    }

    /**
     * @throws PropelException
     */
    public static function createMediaFile(
        Site   $site = null,
        string $directory = "medias",
        int    $fileSize = 100,
    ): MediaFile
    {
        $mediaFile = new MediaFile();
        $mediaFile->setSiteId($site ? $site->getId() : self::createSite()->getId());
        $mediaFile->setDir($directory);
        $mediaFile->setFileSize($fileSize);
        $mediaFile->save();

        return $mediaFile;
    }

    /**
     * @throws PropelException
     */
    public static function createEvent(
        Site      $site,
        Publisher $publisher = null,
        bool      $isPublished = true,
    ): Event
    {
        $event = new Event();
        $event->setSiteId($site->getId());
        $event->setTitle("Event");
        $event->setUrl("event");
        $event->setStart(new DateTime());
        $event->setEnd(new DateTime("+1 day"));
        $event->setLocation("Paris");
        $event->setStatus($isPublished);
        $event->setPublisherId($publisher?->getId());

        $event->save();

        return $event;
    }

    /**
     * @param string $mediaDir
     * @param $title = "Galerie"
     * @return Gallery
     * @throws PropelException
     */
    public static function createGallery(
        string $title = "Galerie",
        string $mediaDir = "galerie"
    ): Gallery
    {
        $gallery = new Gallery();
        $gallery->setTitle($title);
        $gallery->setMediaDir($mediaDir);
        $gallery->save();

        return $gallery;
    }

}
