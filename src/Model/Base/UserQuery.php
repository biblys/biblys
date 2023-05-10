<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `users` table.
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildUserQuery orderByEmail($order = Criteria::ASC) Order by the Email column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the user_password column
 * @method     ChildUserQuery orderByKey($order = Criteria::ASC) Order by the user_key column
 * @method     ChildUserQuery orderByEmailKey($order = Criteria::ASC) Order by the email_key column
 * @method     ChildUserQuery orderByFacebookUid($order = Criteria::ASC) Order by the facebook_uid column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the user_screen_name column
 * @method     ChildUserQuery orderBySlug($order = Criteria::ASC) Order by the user_slug column
 * @method     ChildUserQuery orderByWishlistShip($order = Criteria::ASC) Order by the user_wishlist_ship column
 * @method     ChildUserQuery orderByTop($order = Criteria::ASC) Order by the user_top column
 * @method     ChildUserQuery orderByBiblio($order = Criteria::ASC) Order by the user_biblio column
 * @method     ChildUserQuery orderByAdresseIp($order = Criteria::ASC) Order by the adresse_ip column
 * @method     ChildUserQuery orderByRecaptchaScore($order = Criteria::ASC) Order by the recaptcha_score column
 * @method     ChildUserQuery orderByDateinscription($order = Criteria::ASC) Order by the DateInscription column
 * @method     ChildUserQuery orderByDateconnexion($order = Criteria::ASC) Order by the DateConnexion column
 * @method     ChildUserQuery orderByPublisherId($order = Criteria::ASC) Order by the publisher_id column
 * @method     ChildUserQuery orderByBookshopId($order = Criteria::ASC) Order by the bookshop_id column
 * @method     ChildUserQuery orderByLibraryId($order = Criteria::ASC) Order by the library_id column
 * @method     ChildUserQuery orderByCivilite($order = Criteria::ASC) Order by the user_civilite column
 * @method     ChildUserQuery orderByNom($order = Criteria::ASC) Order by the user_nom column
 * @method     ChildUserQuery orderByPrenom($order = Criteria::ASC) Order by the user_prenom column
 * @method     ChildUserQuery orderByAdresse1($order = Criteria::ASC) Order by the user_adresse1 column
 * @method     ChildUserQuery orderByAdresse2($order = Criteria::ASC) Order by the user_adresse2 column
 * @method     ChildUserQuery orderByCodepostal($order = Criteria::ASC) Order by the user_codepostal column
 * @method     ChildUserQuery orderByVille($order = Criteria::ASC) Order by the user_ville column
 * @method     ChildUserQuery orderByPays($order = Criteria::ASC) Order by the user_pays column
 * @method     ChildUserQuery orderByTelephone($order = Criteria::ASC) Order by the user_telephone column
 * @method     ChildUserQuery orderByPrefArticlesShow($order = Criteria::ASC) Order by the user_pref_articles_show column
 * @method     ChildUserQuery orderByFbId($order = Criteria::ASC) Order by the user_fb_id column
 * @method     ChildUserQuery orderByFbToken($order = Criteria::ASC) Order by the user_fb_token column
 * @method     ChildUserQuery orderByCountryId($order = Criteria::ASC) Order by the country_id column
 * @method     ChildUserQuery orderByPasswordResetToken($order = Criteria::ASC) Order by the user_password_reset_token column
 * @method     ChildUserQuery orderByPasswordResetTokenCreated($order = Criteria::ASC) Order by the user_password_reset_token_created column
 * @method     ChildUserQuery orderByUpdate($order = Criteria::ASC) Order by the user_update column
 * @method     ChildUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the user_created column
 * @method     ChildUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the user_updated column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupBySiteId() Group by the site_id column
 * @method     ChildUserQuery groupByEmail() Group by the Email column
 * @method     ChildUserQuery groupByPassword() Group by the user_password column
 * @method     ChildUserQuery groupByKey() Group by the user_key column
 * @method     ChildUserQuery groupByEmailKey() Group by the email_key column
 * @method     ChildUserQuery groupByFacebookUid() Group by the facebook_uid column
 * @method     ChildUserQuery groupByUsername() Group by the user_screen_name column
 * @method     ChildUserQuery groupBySlug() Group by the user_slug column
 * @method     ChildUserQuery groupByWishlistShip() Group by the user_wishlist_ship column
 * @method     ChildUserQuery groupByTop() Group by the user_top column
 * @method     ChildUserQuery groupByBiblio() Group by the user_biblio column
 * @method     ChildUserQuery groupByAdresseIp() Group by the adresse_ip column
 * @method     ChildUserQuery groupByRecaptchaScore() Group by the recaptcha_score column
 * @method     ChildUserQuery groupByDateinscription() Group by the DateInscription column
 * @method     ChildUserQuery groupByDateconnexion() Group by the DateConnexion column
 * @method     ChildUserQuery groupByPublisherId() Group by the publisher_id column
 * @method     ChildUserQuery groupByBookshopId() Group by the bookshop_id column
 * @method     ChildUserQuery groupByLibraryId() Group by the library_id column
 * @method     ChildUserQuery groupByCivilite() Group by the user_civilite column
 * @method     ChildUserQuery groupByNom() Group by the user_nom column
 * @method     ChildUserQuery groupByPrenom() Group by the user_prenom column
 * @method     ChildUserQuery groupByAdresse1() Group by the user_adresse1 column
 * @method     ChildUserQuery groupByAdresse2() Group by the user_adresse2 column
 * @method     ChildUserQuery groupByCodepostal() Group by the user_codepostal column
 * @method     ChildUserQuery groupByVille() Group by the user_ville column
 * @method     ChildUserQuery groupByPays() Group by the user_pays column
 * @method     ChildUserQuery groupByTelephone() Group by the user_telephone column
 * @method     ChildUserQuery groupByPrefArticlesShow() Group by the user_pref_articles_show column
 * @method     ChildUserQuery groupByFbId() Group by the user_fb_id column
 * @method     ChildUserQuery groupByFbToken() Group by the user_fb_token column
 * @method     ChildUserQuery groupByCountryId() Group by the country_id column
 * @method     ChildUserQuery groupByPasswordResetToken() Group by the user_password_reset_token column
 * @method     ChildUserQuery groupByPasswordResetTokenCreated() Group by the user_password_reset_token_created column
 * @method     ChildUserQuery groupByUpdate() Group by the user_update column
 * @method     ChildUserQuery groupByCreatedAt() Group by the user_created column
 * @method     ChildUserQuery groupByUpdatedAt() Group by the user_updated column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinSite($relationAlias = null) Adds a LEFT JOIN clause to the query using the Site relation
 * @method     ChildUserQuery rightJoinSite($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Site relation
 * @method     ChildUserQuery innerJoinSite($relationAlias = null) Adds a INNER JOIN clause to the query using the Site relation
 *
 * @method     ChildUserQuery joinWithSite($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Site relation
 *
 * @method     ChildUserQuery leftJoinWithSite() Adds a LEFT JOIN clause and with to the query using the Site relation
 * @method     ChildUserQuery rightJoinWithSite() Adds a RIGHT JOIN clause and with to the query using the Site relation
 * @method     ChildUserQuery innerJoinWithSite() Adds a INNER JOIN clause and with to the query using the Site relation
 *
 * @method     ChildUserQuery leftJoinCart($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cart relation
 * @method     ChildUserQuery rightJoinCart($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cart relation
 * @method     ChildUserQuery innerJoinCart($relationAlias = null) Adds a INNER JOIN clause to the query using the Cart relation
 *
 * @method     ChildUserQuery joinWithCart($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Cart relation
 *
 * @method     ChildUserQuery leftJoinWithCart() Adds a LEFT JOIN clause and with to the query using the Cart relation
 * @method     ChildUserQuery rightJoinWithCart() Adds a RIGHT JOIN clause and with to the query using the Cart relation
 * @method     ChildUserQuery innerJoinWithCart() Adds a INNER JOIN clause and with to the query using the Cart relation
 *
 * @method     ChildUserQuery leftJoinOption($relationAlias = null) Adds a LEFT JOIN clause to the query using the Option relation
 * @method     ChildUserQuery rightJoinOption($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Option relation
 * @method     ChildUserQuery innerJoinOption($relationAlias = null) Adds a INNER JOIN clause to the query using the Option relation
 *
 * @method     ChildUserQuery joinWithOption($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Option relation
 *
 * @method     ChildUserQuery leftJoinWithOption() Adds a LEFT JOIN clause and with to the query using the Option relation
 * @method     ChildUserQuery rightJoinWithOption() Adds a RIGHT JOIN clause and with to the query using the Option relation
 * @method     ChildUserQuery innerJoinWithOption() Adds a INNER JOIN clause and with to the query using the Option relation
 *
 * @method     ChildUserQuery leftJoinRight($relationAlias = null) Adds a LEFT JOIN clause to the query using the Right relation
 * @method     ChildUserQuery rightJoinRight($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Right relation
 * @method     ChildUserQuery innerJoinRight($relationAlias = null) Adds a INNER JOIN clause to the query using the Right relation
 *
 * @method     ChildUserQuery joinWithRight($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Right relation
 *
 * @method     ChildUserQuery leftJoinWithRight() Adds a LEFT JOIN clause and with to the query using the Right relation
 * @method     ChildUserQuery rightJoinWithRight() Adds a RIGHT JOIN clause and with to the query using the Right relation
 * @method     ChildUserQuery innerJoinWithRight() Adds a INNER JOIN clause and with to the query using the Right relation
 *
 * @method     ChildUserQuery leftJoinSession($relationAlias = null) Adds a LEFT JOIN clause to the query using the Session relation
 * @method     ChildUserQuery rightJoinSession($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Session relation
 * @method     ChildUserQuery innerJoinSession($relationAlias = null) Adds a INNER JOIN clause to the query using the Session relation
 *
 * @method     ChildUserQuery joinWithSession($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Session relation
 *
 * @method     ChildUserQuery leftJoinWithSession() Adds a LEFT JOIN clause and with to the query using the Session relation
 * @method     ChildUserQuery rightJoinWithSession() Adds a RIGHT JOIN clause and with to the query using the Session relation
 * @method     ChildUserQuery innerJoinWithSession() Adds a INNER JOIN clause and with to the query using the Session relation
 *
 * @method     ChildUserQuery leftJoinStock($relationAlias = null) Adds a LEFT JOIN clause to the query using the Stock relation
 * @method     ChildUserQuery rightJoinStock($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Stock relation
 * @method     ChildUserQuery innerJoinStock($relationAlias = null) Adds a INNER JOIN clause to the query using the Stock relation
 *
 * @method     ChildUserQuery joinWithStock($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Stock relation
 *
 * @method     ChildUserQuery leftJoinWithStock() Adds a LEFT JOIN clause and with to the query using the Stock relation
 * @method     ChildUserQuery rightJoinWithStock() Adds a RIGHT JOIN clause and with to the query using the Stock relation
 * @method     ChildUserQuery innerJoinWithStock() Adds a INNER JOIN clause and with to the query using the Stock relation
 *
 * @method     ChildUserQuery leftJoinWish($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wish relation
 * @method     ChildUserQuery rightJoinWish($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wish relation
 * @method     ChildUserQuery innerJoinWish($relationAlias = null) Adds a INNER JOIN clause to the query using the Wish relation
 *
 * @method     ChildUserQuery joinWithWish($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wish relation
 *
 * @method     ChildUserQuery leftJoinWithWish() Adds a LEFT JOIN clause and with to the query using the Wish relation
 * @method     ChildUserQuery rightJoinWithWish() Adds a RIGHT JOIN clause and with to the query using the Wish relation
 * @method     ChildUserQuery innerJoinWithWish() Adds a INNER JOIN clause and with to the query using the Wish relation
 *
 * @method     ChildUserQuery leftJoinWishlist($relationAlias = null) Adds a LEFT JOIN clause to the query using the Wishlist relation
 * @method     ChildUserQuery rightJoinWishlist($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Wishlist relation
 * @method     ChildUserQuery innerJoinWishlist($relationAlias = null) Adds a INNER JOIN clause to the query using the Wishlist relation
 *
 * @method     ChildUserQuery joinWithWishlist($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Wishlist relation
 *
 * @method     ChildUserQuery leftJoinWithWishlist() Adds a LEFT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildUserQuery rightJoinWithWishlist() Adds a RIGHT JOIN clause and with to the query using the Wishlist relation
 * @method     ChildUserQuery innerJoinWithWishlist() Adds a INNER JOIN clause and with to the query using the Wishlist relation
 *
 * @method     \Model\SiteQuery|\Model\CartQuery|\Model\OptionQuery|\Model\RightQuery|\Model\SessionQuery|\Model\StockQuery|\Model\WishQuery|\Model\WishlistQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser|null findOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser|null findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser|null findOneBySiteId(int $site_id) Return the first ChildUser filtered by the site_id column
 * @method     ChildUser|null findOneByEmail(string $Email) Return the first ChildUser filtered by the Email column
 * @method     ChildUser|null findOneByPassword(string $user_password) Return the first ChildUser filtered by the user_password column
 * @method     ChildUser|null findOneByKey(string $user_key) Return the first ChildUser filtered by the user_key column
 * @method     ChildUser|null findOneByEmailKey(string $email_key) Return the first ChildUser filtered by the email_key column
 * @method     ChildUser|null findOneByFacebookUid(int $facebook_uid) Return the first ChildUser filtered by the facebook_uid column
 * @method     ChildUser|null findOneByUsername(string $user_screen_name) Return the first ChildUser filtered by the user_screen_name column
 * @method     ChildUser|null findOneBySlug(string $user_slug) Return the first ChildUser filtered by the user_slug column
 * @method     ChildUser|null findOneByWishlistShip(boolean $user_wishlist_ship) Return the first ChildUser filtered by the user_wishlist_ship column
 * @method     ChildUser|null findOneByTop(boolean $user_top) Return the first ChildUser filtered by the user_top column
 * @method     ChildUser|null findOneByBiblio(boolean $user_biblio) Return the first ChildUser filtered by the user_biblio column
 * @method     ChildUser|null findOneByAdresseIp(string $adresse_ip) Return the first ChildUser filtered by the adresse_ip column
 * @method     ChildUser|null findOneByRecaptchaScore(double $recaptcha_score) Return the first ChildUser filtered by the recaptcha_score column
 * @method     ChildUser|null findOneByDateinscription(string $DateInscription) Return the first ChildUser filtered by the DateInscription column
 * @method     ChildUser|null findOneByDateconnexion(string $DateConnexion) Return the first ChildUser filtered by the DateConnexion column
 * @method     ChildUser|null findOneByPublisherId(int $publisher_id) Return the first ChildUser filtered by the publisher_id column
 * @method     ChildUser|null findOneByBookshopId(int $bookshop_id) Return the first ChildUser filtered by the bookshop_id column
 * @method     ChildUser|null findOneByLibraryId(int $library_id) Return the first ChildUser filtered by the library_id column
 * @method     ChildUser|null findOneByCivilite(string $user_civilite) Return the first ChildUser filtered by the user_civilite column
 * @method     ChildUser|null findOneByNom(string $user_nom) Return the first ChildUser filtered by the user_nom column
 * @method     ChildUser|null findOneByPrenom(string $user_prenom) Return the first ChildUser filtered by the user_prenom column
 * @method     ChildUser|null findOneByAdresse1(string $user_adresse1) Return the first ChildUser filtered by the user_adresse1 column
 * @method     ChildUser|null findOneByAdresse2(string $user_adresse2) Return the first ChildUser filtered by the user_adresse2 column
 * @method     ChildUser|null findOneByCodepostal(string $user_codepostal) Return the first ChildUser filtered by the user_codepostal column
 * @method     ChildUser|null findOneByVille(string $user_ville) Return the first ChildUser filtered by the user_ville column
 * @method     ChildUser|null findOneByPays(string $user_pays) Return the first ChildUser filtered by the user_pays column
 * @method     ChildUser|null findOneByTelephone(string $user_telephone) Return the first ChildUser filtered by the user_telephone column
 * @method     ChildUser|null findOneByPrefArticlesShow(string $user_pref_articles_show) Return the first ChildUser filtered by the user_pref_articles_show column
 * @method     ChildUser|null findOneByFbId(string $user_fb_id) Return the first ChildUser filtered by the user_fb_id column
 * @method     ChildUser|null findOneByFbToken(string $user_fb_token) Return the first ChildUser filtered by the user_fb_token column
 * @method     ChildUser|null findOneByCountryId(int $country_id) Return the first ChildUser filtered by the country_id column
 * @method     ChildUser|null findOneByPasswordResetToken(string $user_password_reset_token) Return the first ChildUser filtered by the user_password_reset_token column
 * @method     ChildUser|null findOneByPasswordResetTokenCreated(string $user_password_reset_token_created) Return the first ChildUser filtered by the user_password_reset_token_created column
 * @method     ChildUser|null findOneByUpdate(string $user_update) Return the first ChildUser filtered by the user_update column
 * @method     ChildUser|null findOneByCreatedAt(string $user_created) Return the first ChildUser filtered by the user_created column
 * @method     ChildUser|null findOneByUpdatedAt(string $user_updated) Return the first ChildUser filtered by the user_updated column
 *
 * @method     ChildUser requirePk($key, ?ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySiteId(int $site_id) Return the first ChildUser filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmail(string $Email) Return the first ChildUser filtered by the Email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $user_password) Return the first ChildUser filtered by the user_password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByKey(string $user_key) Return the first ChildUser filtered by the user_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByEmailKey(string $email_key) Return the first ChildUser filtered by the email_key column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByFacebookUid(int $facebook_uid) Return the first ChildUser filtered by the facebook_uid column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $user_screen_name) Return the first ChildUser filtered by the user_screen_name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneBySlug(string $user_slug) Return the first ChildUser filtered by the user_slug column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByWishlistShip(boolean $user_wishlist_ship) Return the first ChildUser filtered by the user_wishlist_ship column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByTop(boolean $user_top) Return the first ChildUser filtered by the user_top column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByBiblio(boolean $user_biblio) Return the first ChildUser filtered by the user_biblio column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAdresseIp(string $adresse_ip) Return the first ChildUser filtered by the adresse_ip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByRecaptchaScore(double $recaptcha_score) Return the first ChildUser filtered by the recaptcha_score column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByDateinscription(string $DateInscription) Return the first ChildUser filtered by the DateInscription column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByDateconnexion(string $DateConnexion) Return the first ChildUser filtered by the DateConnexion column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPublisherId(int $publisher_id) Return the first ChildUser filtered by the publisher_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByBookshopId(int $bookshop_id) Return the first ChildUser filtered by the bookshop_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByLibraryId(int $library_id) Return the first ChildUser filtered by the library_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCivilite(string $user_civilite) Return the first ChildUser filtered by the user_civilite column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByNom(string $user_nom) Return the first ChildUser filtered by the user_nom column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPrenom(string $user_prenom) Return the first ChildUser filtered by the user_prenom column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAdresse1(string $user_adresse1) Return the first ChildUser filtered by the user_adresse1 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByAdresse2(string $user_adresse2) Return the first ChildUser filtered by the user_adresse2 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCodepostal(string $user_codepostal) Return the first ChildUser filtered by the user_codepostal column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByVille(string $user_ville) Return the first ChildUser filtered by the user_ville column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPays(string $user_pays) Return the first ChildUser filtered by the user_pays column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByTelephone(string $user_telephone) Return the first ChildUser filtered by the user_telephone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPrefArticlesShow(string $user_pref_articles_show) Return the first ChildUser filtered by the user_pref_articles_show column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByFbId(string $user_fb_id) Return the first ChildUser filtered by the user_fb_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByFbToken(string $user_fb_token) Return the first ChildUser filtered by the user_fb_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCountryId(int $country_id) Return the first ChildUser filtered by the country_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordResetToken(string $user_password_reset_token) Return the first ChildUser filtered by the user_password_reset_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPasswordResetTokenCreated(string $user_password_reset_token_created) Return the first ChildUser filtered by the user_password_reset_token_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdate(string $user_update) Return the first ChildUser filtered by the user_update column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByCreatedAt(string $user_created) Return the first ChildUser filtered by the user_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUpdatedAt(string $user_updated) Return the first ChildUser filtered by the user_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|Collection find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildUser> find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 *
 * @method     ChildUser[]|Collection findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildUser> findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|Collection findBySiteId(int|array<int> $site_id) Return ChildUser objects filtered by the site_id column
 * @psalm-method Collection&\Traversable<ChildUser> findBySiteId(int|array<int> $site_id) Return ChildUser objects filtered by the site_id column
 * @method     ChildUser[]|Collection findByEmail(string|array<string> $Email) Return ChildUser objects filtered by the Email column
 * @psalm-method Collection&\Traversable<ChildUser> findByEmail(string|array<string> $Email) Return ChildUser objects filtered by the Email column
 * @method     ChildUser[]|Collection findByPassword(string|array<string> $user_password) Return ChildUser objects filtered by the user_password column
 * @psalm-method Collection&\Traversable<ChildUser> findByPassword(string|array<string> $user_password) Return ChildUser objects filtered by the user_password column
 * @method     ChildUser[]|Collection findByKey(string|array<string> $user_key) Return ChildUser objects filtered by the user_key column
 * @psalm-method Collection&\Traversable<ChildUser> findByKey(string|array<string> $user_key) Return ChildUser objects filtered by the user_key column
 * @method     ChildUser[]|Collection findByEmailKey(string|array<string> $email_key) Return ChildUser objects filtered by the email_key column
 * @psalm-method Collection&\Traversable<ChildUser> findByEmailKey(string|array<string> $email_key) Return ChildUser objects filtered by the email_key column
 * @method     ChildUser[]|Collection findByFacebookUid(int|array<int> $facebook_uid) Return ChildUser objects filtered by the facebook_uid column
 * @psalm-method Collection&\Traversable<ChildUser> findByFacebookUid(int|array<int> $facebook_uid) Return ChildUser objects filtered by the facebook_uid column
 * @method     ChildUser[]|Collection findByUsername(string|array<string> $user_screen_name) Return ChildUser objects filtered by the user_screen_name column
 * @psalm-method Collection&\Traversable<ChildUser> findByUsername(string|array<string> $user_screen_name) Return ChildUser objects filtered by the user_screen_name column
 * @method     ChildUser[]|Collection findBySlug(string|array<string> $user_slug) Return ChildUser objects filtered by the user_slug column
 * @psalm-method Collection&\Traversable<ChildUser> findBySlug(string|array<string> $user_slug) Return ChildUser objects filtered by the user_slug column
 * @method     ChildUser[]|Collection findByWishlistShip(boolean|array<boolean> $user_wishlist_ship) Return ChildUser objects filtered by the user_wishlist_ship column
 * @psalm-method Collection&\Traversable<ChildUser> findByWishlistShip(boolean|array<boolean> $user_wishlist_ship) Return ChildUser objects filtered by the user_wishlist_ship column
 * @method     ChildUser[]|Collection findByTop(boolean|array<boolean> $user_top) Return ChildUser objects filtered by the user_top column
 * @psalm-method Collection&\Traversable<ChildUser> findByTop(boolean|array<boolean> $user_top) Return ChildUser objects filtered by the user_top column
 * @method     ChildUser[]|Collection findByBiblio(boolean|array<boolean> $user_biblio) Return ChildUser objects filtered by the user_biblio column
 * @psalm-method Collection&\Traversable<ChildUser> findByBiblio(boolean|array<boolean> $user_biblio) Return ChildUser objects filtered by the user_biblio column
 * @method     ChildUser[]|Collection findByAdresseIp(string|array<string> $adresse_ip) Return ChildUser objects filtered by the adresse_ip column
 * @psalm-method Collection&\Traversable<ChildUser> findByAdresseIp(string|array<string> $adresse_ip) Return ChildUser objects filtered by the adresse_ip column
 * @method     ChildUser[]|Collection findByRecaptchaScore(double|array<double> $recaptcha_score) Return ChildUser objects filtered by the recaptcha_score column
 * @psalm-method Collection&\Traversable<ChildUser> findByRecaptchaScore(double|array<double> $recaptcha_score) Return ChildUser objects filtered by the recaptcha_score column
 * @method     ChildUser[]|Collection findByDateinscription(string|array<string> $DateInscription) Return ChildUser objects filtered by the DateInscription column
 * @psalm-method Collection&\Traversable<ChildUser> findByDateinscription(string|array<string> $DateInscription) Return ChildUser objects filtered by the DateInscription column
 * @method     ChildUser[]|Collection findByDateconnexion(string|array<string> $DateConnexion) Return ChildUser objects filtered by the DateConnexion column
 * @psalm-method Collection&\Traversable<ChildUser> findByDateconnexion(string|array<string> $DateConnexion) Return ChildUser objects filtered by the DateConnexion column
 * @method     ChildUser[]|Collection findByPublisherId(int|array<int> $publisher_id) Return ChildUser objects filtered by the publisher_id column
 * @psalm-method Collection&\Traversable<ChildUser> findByPublisherId(int|array<int> $publisher_id) Return ChildUser objects filtered by the publisher_id column
 * @method     ChildUser[]|Collection findByBookshopId(int|array<int> $bookshop_id) Return ChildUser objects filtered by the bookshop_id column
 * @psalm-method Collection&\Traversable<ChildUser> findByBookshopId(int|array<int> $bookshop_id) Return ChildUser objects filtered by the bookshop_id column
 * @method     ChildUser[]|Collection findByLibraryId(int|array<int> $library_id) Return ChildUser objects filtered by the library_id column
 * @psalm-method Collection&\Traversable<ChildUser> findByLibraryId(int|array<int> $library_id) Return ChildUser objects filtered by the library_id column
 * @method     ChildUser[]|Collection findByCivilite(string|array<string> $user_civilite) Return ChildUser objects filtered by the user_civilite column
 * @psalm-method Collection&\Traversable<ChildUser> findByCivilite(string|array<string> $user_civilite) Return ChildUser objects filtered by the user_civilite column
 * @method     ChildUser[]|Collection findByNom(string|array<string> $user_nom) Return ChildUser objects filtered by the user_nom column
 * @psalm-method Collection&\Traversable<ChildUser> findByNom(string|array<string> $user_nom) Return ChildUser objects filtered by the user_nom column
 * @method     ChildUser[]|Collection findByPrenom(string|array<string> $user_prenom) Return ChildUser objects filtered by the user_prenom column
 * @psalm-method Collection&\Traversable<ChildUser> findByPrenom(string|array<string> $user_prenom) Return ChildUser objects filtered by the user_prenom column
 * @method     ChildUser[]|Collection findByAdresse1(string|array<string> $user_adresse1) Return ChildUser objects filtered by the user_adresse1 column
 * @psalm-method Collection&\Traversable<ChildUser> findByAdresse1(string|array<string> $user_adresse1) Return ChildUser objects filtered by the user_adresse1 column
 * @method     ChildUser[]|Collection findByAdresse2(string|array<string> $user_adresse2) Return ChildUser objects filtered by the user_adresse2 column
 * @psalm-method Collection&\Traversable<ChildUser> findByAdresse2(string|array<string> $user_adresse2) Return ChildUser objects filtered by the user_adresse2 column
 * @method     ChildUser[]|Collection findByCodepostal(string|array<string> $user_codepostal) Return ChildUser objects filtered by the user_codepostal column
 * @psalm-method Collection&\Traversable<ChildUser> findByCodepostal(string|array<string> $user_codepostal) Return ChildUser objects filtered by the user_codepostal column
 * @method     ChildUser[]|Collection findByVille(string|array<string> $user_ville) Return ChildUser objects filtered by the user_ville column
 * @psalm-method Collection&\Traversable<ChildUser> findByVille(string|array<string> $user_ville) Return ChildUser objects filtered by the user_ville column
 * @method     ChildUser[]|Collection findByPays(string|array<string> $user_pays) Return ChildUser objects filtered by the user_pays column
 * @psalm-method Collection&\Traversable<ChildUser> findByPays(string|array<string> $user_pays) Return ChildUser objects filtered by the user_pays column
 * @method     ChildUser[]|Collection findByTelephone(string|array<string> $user_telephone) Return ChildUser objects filtered by the user_telephone column
 * @psalm-method Collection&\Traversable<ChildUser> findByTelephone(string|array<string> $user_telephone) Return ChildUser objects filtered by the user_telephone column
 * @method     ChildUser[]|Collection findByPrefArticlesShow(string|array<string> $user_pref_articles_show) Return ChildUser objects filtered by the user_pref_articles_show column
 * @psalm-method Collection&\Traversable<ChildUser> findByPrefArticlesShow(string|array<string> $user_pref_articles_show) Return ChildUser objects filtered by the user_pref_articles_show column
 * @method     ChildUser[]|Collection findByFbId(string|array<string> $user_fb_id) Return ChildUser objects filtered by the user_fb_id column
 * @psalm-method Collection&\Traversable<ChildUser> findByFbId(string|array<string> $user_fb_id) Return ChildUser objects filtered by the user_fb_id column
 * @method     ChildUser[]|Collection findByFbToken(string|array<string> $user_fb_token) Return ChildUser objects filtered by the user_fb_token column
 * @psalm-method Collection&\Traversable<ChildUser> findByFbToken(string|array<string> $user_fb_token) Return ChildUser objects filtered by the user_fb_token column
 * @method     ChildUser[]|Collection findByCountryId(int|array<int> $country_id) Return ChildUser objects filtered by the country_id column
 * @psalm-method Collection&\Traversable<ChildUser> findByCountryId(int|array<int> $country_id) Return ChildUser objects filtered by the country_id column
 * @method     ChildUser[]|Collection findByPasswordResetToken(string|array<string> $user_password_reset_token) Return ChildUser objects filtered by the user_password_reset_token column
 * @psalm-method Collection&\Traversable<ChildUser> findByPasswordResetToken(string|array<string> $user_password_reset_token) Return ChildUser objects filtered by the user_password_reset_token column
 * @method     ChildUser[]|Collection findByPasswordResetTokenCreated(string|array<string> $user_password_reset_token_created) Return ChildUser objects filtered by the user_password_reset_token_created column
 * @psalm-method Collection&\Traversable<ChildUser> findByPasswordResetTokenCreated(string|array<string> $user_password_reset_token_created) Return ChildUser objects filtered by the user_password_reset_token_created column
 * @method     ChildUser[]|Collection findByUpdate(string|array<string> $user_update) Return ChildUser objects filtered by the user_update column
 * @psalm-method Collection&\Traversable<ChildUser> findByUpdate(string|array<string> $user_update) Return ChildUser objects filtered by the user_update column
 * @method     ChildUser[]|Collection findByCreatedAt(string|array<string> $user_created) Return ChildUser objects filtered by the user_created column
 * @psalm-method Collection&\Traversable<ChildUser> findByCreatedAt(string|array<string> $user_created) Return ChildUser objects filtered by the user_created column
 * @method     ChildUser[]|Collection findByUpdatedAt(string|array<string> $user_updated) Return ChildUser objects filtered by the user_updated column
 * @psalm-method Collection&\Traversable<ChildUser> findByUpdatedAt(string|array<string> $user_updated) Return ChildUser objects filtered by the user_updated column
 *
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildUser> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\UserQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, site_id, Email, user_password, user_key, email_key, facebook_uid, user_screen_name, user_slug, user_wishlist_ship, user_top, user_biblio, adresse_ip, recaptcha_score, DateInscription, DateConnexion, publisher_id, bookshop_id, library_id, user_civilite, user_nom, user_prenom, user_adresse1, user_adresse2, user_codepostal, user_ville, user_pays, user_telephone, user_pref_articles_show, user_fb_id, user_fb_token, country_id, user_password_reset_token, user_password_reset_token_created, user_update, user_created, user_updated FROM users WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteId(1234); // WHERE site_id = 1234
     * $query->filterBySiteId(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterBySiteId(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @see       filterBySite()
     *
     * @param mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, ?string $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_SITE_ID, $siteId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the Email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE Email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE Email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE Email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE user_password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE user_password LIKE '%fooValue%'
     * $query->filterByPassword(['foo', 'bar']); // WHERE user_password IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $password The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPassword($password = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PASSWORD, $password, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_key column
     *
     * Example usage:
     * <code>
     * $query->filterByKey('fooValue');   // WHERE user_key = 'fooValue'
     * $query->filterByKey('%fooValue%', Criteria::LIKE); // WHERE user_key LIKE '%fooValue%'
     * $query->filterByKey(['foo', 'bar']); // WHERE user_key IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $key The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByKey($key = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($key)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_KEY, $key, $comparison);

        return $this;
    }

    /**
     * Filter the query on the email_key column
     *
     * Example usage:
     * <code>
     * $query->filterByEmailKey('fooValue');   // WHERE email_key = 'fooValue'
     * $query->filterByEmailKey('%fooValue%', Criteria::LIKE); // WHERE email_key LIKE '%fooValue%'
     * $query->filterByEmailKey(['foo', 'bar']); // WHERE email_key IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $emailKey The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmailKey($emailKey = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($emailKey)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_EMAIL_KEY, $emailKey, $comparison);

        return $this;
    }

    /**
     * Filter the query on the facebook_uid column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebookUid(1234); // WHERE facebook_uid = 1234
     * $query->filterByFacebookUid(array(12, 34)); // WHERE facebook_uid IN (12, 34)
     * $query->filterByFacebookUid(array('min' => 12)); // WHERE facebook_uid > 12
     * </code>
     *
     * @param mixed $facebookUid The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFacebookUid($facebookUid = null, ?string $comparison = null)
    {
        if (is_array($facebookUid)) {
            $useMinMax = false;
            if (isset($facebookUid['min'])) {
                $this->addUsingAlias(UserTableMap::COL_FACEBOOK_UID, $facebookUid['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($facebookUid['max'])) {
                $this->addUsingAlias(UserTableMap::COL_FACEBOOK_UID, $facebookUid['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_FACEBOOK_UID, $facebookUid, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_screen_name column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE user_screen_name = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE user_screen_name LIKE '%fooValue%'
     * $query->filterByUsername(['foo', 'bar']); // WHERE user_screen_name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $username The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUsername($username = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_SCREEN_NAME, $username, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE user_slug = 'fooValue'
     * $query->filterBySlug('%fooValue%', Criteria::LIKE); // WHERE user_slug LIKE '%fooValue%'
     * $query->filterBySlug(['foo', 'bar']); // WHERE user_slug IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $slug The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySlug($slug = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_SLUG, $slug, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_wishlist_ship column
     *
     * Example usage:
     * <code>
     * $query->filterByWishlistShip(true); // WHERE user_wishlist_ship = true
     * $query->filterByWishlistShip('yes'); // WHERE user_wishlist_ship = true
     * </code>
     *
     * @param bool|string $wishlistShip The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWishlistShip($wishlistShip = null, ?string $comparison = null)
    {
        if (is_string($wishlistShip)) {
            $wishlistShip = in_array(strtolower($wishlistShip), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(UserTableMap::COL_USER_WISHLIST_SHIP, $wishlistShip, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_top column
     *
     * Example usage:
     * <code>
     * $query->filterByTop(true); // WHERE user_top = true
     * $query->filterByTop('yes'); // WHERE user_top = true
     * </code>
     *
     * @param bool|string $top The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTop($top = null, ?string $comparison = null)
    {
        if (is_string($top)) {
            $top = in_array(strtolower($top), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(UserTableMap::COL_USER_TOP, $top, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_biblio column
     *
     * Example usage:
     * <code>
     * $query->filterByBiblio(true); // WHERE user_biblio = true
     * $query->filterByBiblio('yes'); // WHERE user_biblio = true
     * </code>
     *
     * @param bool|string $biblio The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBiblio($biblio = null, ?string $comparison = null)
    {
        if (is_string($biblio)) {
            $biblio = in_array(strtolower($biblio), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(UserTableMap::COL_USER_BIBLIO, $biblio, $comparison);

        return $this;
    }

    /**
     * Filter the query on the adresse_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByAdresseIp('fooValue');   // WHERE adresse_ip = 'fooValue'
     * $query->filterByAdresseIp('%fooValue%', Criteria::LIKE); // WHERE adresse_ip LIKE '%fooValue%'
     * $query->filterByAdresseIp(['foo', 'bar']); // WHERE adresse_ip IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $adresseIp The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAdresseIp($adresseIp = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($adresseIp)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ADRESSE_IP, $adresseIp, $comparison);

        return $this;
    }

    /**
     * Filter the query on the recaptcha_score column
     *
     * Example usage:
     * <code>
     * $query->filterByRecaptchaScore(1234); // WHERE recaptcha_score = 1234
     * $query->filterByRecaptchaScore(array(12, 34)); // WHERE recaptcha_score IN (12, 34)
     * $query->filterByRecaptchaScore(array('min' => 12)); // WHERE recaptcha_score > 12
     * </code>
     *
     * @param mixed $recaptchaScore The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRecaptchaScore($recaptchaScore = null, ?string $comparison = null)
    {
        if (is_array($recaptchaScore)) {
            $useMinMax = false;
            if (isset($recaptchaScore['min'])) {
                $this->addUsingAlias(UserTableMap::COL_RECAPTCHA_SCORE, $recaptchaScore['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($recaptchaScore['max'])) {
                $this->addUsingAlias(UserTableMap::COL_RECAPTCHA_SCORE, $recaptchaScore['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_RECAPTCHA_SCORE, $recaptchaScore, $comparison);

        return $this;
    }

    /**
     * Filter the query on the DateInscription column
     *
     * Example usage:
     * <code>
     * $query->filterByDateinscription('2011-03-14'); // WHERE DateInscription = '2011-03-14'
     * $query->filterByDateinscription('now'); // WHERE DateInscription = '2011-03-14'
     * $query->filterByDateinscription(array('max' => 'yesterday')); // WHERE DateInscription > '2011-03-13'
     * </code>
     *
     * @param mixed $dateinscription The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDateinscription($dateinscription = null, ?string $comparison = null)
    {
        if (is_array($dateinscription)) {
            $useMinMax = false;
            if (isset($dateinscription['min'])) {
                $this->addUsingAlias(UserTableMap::COL_DATEINSCRIPTION, $dateinscription['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateinscription['max'])) {
                $this->addUsingAlias(UserTableMap::COL_DATEINSCRIPTION, $dateinscription['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_DATEINSCRIPTION, $dateinscription, $comparison);

        return $this;
    }

    /**
     * Filter the query on the DateConnexion column
     *
     * Example usage:
     * <code>
     * $query->filterByDateconnexion('2011-03-14'); // WHERE DateConnexion = '2011-03-14'
     * $query->filterByDateconnexion('now'); // WHERE DateConnexion = '2011-03-14'
     * $query->filterByDateconnexion(array('max' => 'yesterday')); // WHERE DateConnexion > '2011-03-13'
     * </code>
     *
     * @param mixed $dateconnexion The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDateconnexion($dateconnexion = null, ?string $comparison = null)
    {
        if (is_array($dateconnexion)) {
            $useMinMax = false;
            if (isset($dateconnexion['min'])) {
                $this->addUsingAlias(UserTableMap::COL_DATECONNEXION, $dateconnexion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateconnexion['max'])) {
                $this->addUsingAlias(UserTableMap::COL_DATECONNEXION, $dateconnexion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_DATECONNEXION, $dateconnexion, $comparison);

        return $this;
    }

    /**
     * Filter the query on the publisher_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPublisherId(1234); // WHERE publisher_id = 1234
     * $query->filterByPublisherId(array(12, 34)); // WHERE publisher_id IN (12, 34)
     * $query->filterByPublisherId(array('min' => 12)); // WHERE publisher_id > 12
     * </code>
     *
     * @param mixed $publisherId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPublisherId($publisherId = null, ?string $comparison = null)
    {
        if (is_array($publisherId)) {
            $useMinMax = false;
            if (isset($publisherId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_PUBLISHER_ID, $publisherId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publisherId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_PUBLISHER_ID, $publisherId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_PUBLISHER_ID, $publisherId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bookshop_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookshopId(1234); // WHERE bookshop_id = 1234
     * $query->filterByBookshopId(array(12, 34)); // WHERE bookshop_id IN (12, 34)
     * $query->filterByBookshopId(array('min' => 12)); // WHERE bookshop_id > 12
     * </code>
     *
     * @param mixed $bookshopId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBookshopId($bookshopId = null, ?string $comparison = null)
    {
        if (is_array($bookshopId)) {
            $useMinMax = false;
            if (isset($bookshopId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_BOOKSHOP_ID, $bookshopId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookshopId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_BOOKSHOP_ID, $bookshopId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_BOOKSHOP_ID, $bookshopId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the library_id column
     *
     * Example usage:
     * <code>
     * $query->filterByLibraryId(1234); // WHERE library_id = 1234
     * $query->filterByLibraryId(array(12, 34)); // WHERE library_id IN (12, 34)
     * $query->filterByLibraryId(array('min' => 12)); // WHERE library_id > 12
     * </code>
     *
     * @param mixed $libraryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByLibraryId($libraryId = null, ?string $comparison = null)
    {
        if (is_array($libraryId)) {
            $useMinMax = false;
            if (isset($libraryId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_LIBRARY_ID, $libraryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($libraryId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_LIBRARY_ID, $libraryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_LIBRARY_ID, $libraryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_civilite column
     *
     * Example usage:
     * <code>
     * $query->filterByCivilite('fooValue');   // WHERE user_civilite = 'fooValue'
     * $query->filterByCivilite('%fooValue%', Criteria::LIKE); // WHERE user_civilite LIKE '%fooValue%'
     * $query->filterByCivilite(['foo', 'bar']); // WHERE user_civilite IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $civilite The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCivilite($civilite = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($civilite)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_CIVILITE, $civilite, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_nom column
     *
     * Example usage:
     * <code>
     * $query->filterByNom('fooValue');   // WHERE user_nom = 'fooValue'
     * $query->filterByNom('%fooValue%', Criteria::LIKE); // WHERE user_nom LIKE '%fooValue%'
     * $query->filterByNom(['foo', 'bar']); // WHERE user_nom IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nom The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNom($nom = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nom)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_NOM, $nom, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_prenom column
     *
     * Example usage:
     * <code>
     * $query->filterByPrenom('fooValue');   // WHERE user_prenom = 'fooValue'
     * $query->filterByPrenom('%fooValue%', Criteria::LIKE); // WHERE user_prenom LIKE '%fooValue%'
     * $query->filterByPrenom(['foo', 'bar']); // WHERE user_prenom IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $prenom The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrenom($prenom = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prenom)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PRENOM, $prenom, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_adresse1 column
     *
     * Example usage:
     * <code>
     * $query->filterByAdresse1('fooValue');   // WHERE user_adresse1 = 'fooValue'
     * $query->filterByAdresse1('%fooValue%', Criteria::LIKE); // WHERE user_adresse1 LIKE '%fooValue%'
     * $query->filterByAdresse1(['foo', 'bar']); // WHERE user_adresse1 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $adresse1 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAdresse1($adresse1 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($adresse1)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_ADRESSE1, $adresse1, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_adresse2 column
     *
     * Example usage:
     * <code>
     * $query->filterByAdresse2('fooValue');   // WHERE user_adresse2 = 'fooValue'
     * $query->filterByAdresse2('%fooValue%', Criteria::LIKE); // WHERE user_adresse2 LIKE '%fooValue%'
     * $query->filterByAdresse2(['foo', 'bar']); // WHERE user_adresse2 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $adresse2 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAdresse2($adresse2 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($adresse2)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_ADRESSE2, $adresse2, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_codepostal column
     *
     * Example usage:
     * <code>
     * $query->filterByCodepostal('fooValue');   // WHERE user_codepostal = 'fooValue'
     * $query->filterByCodepostal('%fooValue%', Criteria::LIKE); // WHERE user_codepostal LIKE '%fooValue%'
     * $query->filterByCodepostal(['foo', 'bar']); // WHERE user_codepostal IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $codepostal The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCodepostal($codepostal = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($codepostal)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_CODEPOSTAL, $codepostal, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_ville column
     *
     * Example usage:
     * <code>
     * $query->filterByVille('fooValue');   // WHERE user_ville = 'fooValue'
     * $query->filterByVille('%fooValue%', Criteria::LIKE); // WHERE user_ville LIKE '%fooValue%'
     * $query->filterByVille(['foo', 'bar']); // WHERE user_ville IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $ville The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVille($ville = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ville)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_VILLE, $ville, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_pays column
     *
     * Example usage:
     * <code>
     * $query->filterByPays('fooValue');   // WHERE user_pays = 'fooValue'
     * $query->filterByPays('%fooValue%', Criteria::LIKE); // WHERE user_pays LIKE '%fooValue%'
     * $query->filterByPays(['foo', 'bar']); // WHERE user_pays IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $pays The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPays($pays = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pays)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PAYS, $pays, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_telephone column
     *
     * Example usage:
     * <code>
     * $query->filterByTelephone('fooValue');   // WHERE user_telephone = 'fooValue'
     * $query->filterByTelephone('%fooValue%', Criteria::LIKE); // WHERE user_telephone LIKE '%fooValue%'
     * $query->filterByTelephone(['foo', 'bar']); // WHERE user_telephone IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $telephone The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTelephone($telephone = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($telephone)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_TELEPHONE, $telephone, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_pref_articles_show column
     *
     * Example usage:
     * <code>
     * $query->filterByPrefArticlesShow('fooValue');   // WHERE user_pref_articles_show = 'fooValue'
     * $query->filterByPrefArticlesShow('%fooValue%', Criteria::LIKE); // WHERE user_pref_articles_show LIKE '%fooValue%'
     * $query->filterByPrefArticlesShow(['foo', 'bar']); // WHERE user_pref_articles_show IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $prefArticlesShow The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrefArticlesShow($prefArticlesShow = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($prefArticlesShow)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PREF_ARTICLES_SHOW, $prefArticlesShow, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_fb_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFbId(1234); // WHERE user_fb_id = 1234
     * $query->filterByFbId(array(12, 34)); // WHERE user_fb_id IN (12, 34)
     * $query->filterByFbId(array('min' => 12)); // WHERE user_fb_id > 12
     * </code>
     *
     * @param mixed $fbId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFbId($fbId = null, ?string $comparison = null)
    {
        if (is_array($fbId)) {
            $useMinMax = false;
            if (isset($fbId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_FB_ID, $fbId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fbId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_FB_ID, $fbId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_FB_ID, $fbId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_fb_token column
     *
     * Example usage:
     * <code>
     * $query->filterByFbToken('fooValue');   // WHERE user_fb_token = 'fooValue'
     * $query->filterByFbToken('%fooValue%', Criteria::LIKE); // WHERE user_fb_token LIKE '%fooValue%'
     * $query->filterByFbToken(['foo', 'bar']); // WHERE user_fb_token IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fbToken The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFbToken($fbToken = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fbToken)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_FB_TOKEN, $fbToken, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCountryId(1234); // WHERE country_id = 1234
     * $query->filterByCountryId(array(12, 34)); // WHERE country_id IN (12, 34)
     * $query->filterByCountryId(array('min' => 12)); // WHERE country_id > 12
     * </code>
     *
     * @param mixed $countryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountryId($countryId = null, ?string $comparison = null)
    {
        if (is_array($countryId)) {
            $useMinMax = false;
            if (isset($countryId['min'])) {
                $this->addUsingAlias(UserTableMap::COL_COUNTRY_ID, $countryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($countryId['max'])) {
                $this->addUsingAlias(UserTableMap::COL_COUNTRY_ID, $countryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_COUNTRY_ID, $countryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_password_reset_token column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordResetToken('fooValue');   // WHERE user_password_reset_token = 'fooValue'
     * $query->filterByPasswordResetToken('%fooValue%', Criteria::LIKE); // WHERE user_password_reset_token LIKE '%fooValue%'
     * $query->filterByPasswordResetToken(['foo', 'bar']); // WHERE user_password_reset_token IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $passwordResetToken The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPasswordResetToken($passwordResetToken = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($passwordResetToken)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN, $passwordResetToken, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_password_reset_token_created column
     *
     * Example usage:
     * <code>
     * $query->filterByPasswordResetTokenCreated('2011-03-14'); // WHERE user_password_reset_token_created = '2011-03-14'
     * $query->filterByPasswordResetTokenCreated('now'); // WHERE user_password_reset_token_created = '2011-03-14'
     * $query->filterByPasswordResetTokenCreated(array('max' => 'yesterday')); // WHERE user_password_reset_token_created > '2011-03-13'
     * </code>
     *
     * @param mixed $passwordResetTokenCreated The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPasswordResetTokenCreated($passwordResetTokenCreated = null, ?string $comparison = null)
    {
        if (is_array($passwordResetTokenCreated)) {
            $useMinMax = false;
            if (isset($passwordResetTokenCreated['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, $passwordResetTokenCreated['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($passwordResetTokenCreated['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, $passwordResetTokenCreated['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_PASSWORD_RESET_TOKEN_CREATED, $passwordResetTokenCreated, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_update column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdate('2011-03-14'); // WHERE user_update = '2011-03-14'
     * $query->filterByUpdate('now'); // WHERE user_update = '2011-03-14'
     * $query->filterByUpdate(array('max' => 'yesterday')); // WHERE user_update > '2011-03-13'
     * </code>
     *
     * @param mixed $update The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdate($update = null, ?string $comparison = null)
    {
        if (is_array($update)) {
            $useMinMax = false;
            if (isset($update['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_UPDATE, $update['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($update['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_UPDATE, $update['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_UPDATE, $update, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE user_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE user_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE user_created > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the user_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE user_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE user_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE user_updated > '2011-03-13'
     * </code>
     *
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(UserTableMap::COL_USER_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USER_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Site object
     *
     * @param \Model\Site|ObjectCollection $site The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySite($site, ?string $comparison = null)
    {
        if ($site instanceof \Model\Site) {
            return $this
                ->addUsingAlias(UserTableMap::COL_SITE_ID, $site->getId(), $comparison);
        } elseif ($site instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(UserTableMap::COL_SITE_ID, $site->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBySite() only accepts arguments of type \Model\Site or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Site relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSite(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Site');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Site');
        }

        return $this;
    }

    /**
     * Use the Site relation Site object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SiteQuery A secondary query class using the current class as primary query
     */
    public function useSiteQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSite($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Site', '\Model\SiteQuery');
    }

    /**
     * Use the Site relation Site object
     *
     * @param callable(\Model\SiteQuery):\Model\SiteQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSiteQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSiteQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Site table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SiteQuery The inner query object of the EXISTS statement
     */
    public function useSiteExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT EXISTS query.
     *
     * @see useSiteExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT EXISTS statement
     */
    public function useSiteNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useExistsQuery('Site', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Site table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SiteQuery The inner query object of the IN statement
     */
    public function useInSiteQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Site table for a NOT IN query.
     *
     * @see useSiteInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SiteQuery The inner query object of the NOT IN statement
     */
    public function useNotInSiteQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SiteQuery */
        $q = $this->useInQuery('Site', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Cart object
     *
     * @param \Model\Cart|ObjectCollection $cart the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCart($cart, ?string $comparison = null)
    {
        if ($cart instanceof \Model\Cart) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $cart->getUserId(), $comparison);

            return $this;
        } elseif ($cart instanceof ObjectCollection) {
            $this
                ->useCartQuery()
                ->filterByPrimaryKeys($cart->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByCart() only accepts arguments of type \Model\Cart or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cart relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinCart(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cart');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Cart');
        }

        return $this;
    }

    /**
     * Use the Cart relation Cart object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\CartQuery A secondary query class using the current class as primary query
     */
    public function useCartQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCart($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cart', '\Model\CartQuery');
    }

    /**
     * Use the Cart relation Cart object
     *
     * @param callable(\Model\CartQuery):\Model\CartQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withCartQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useCartQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Cart table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\CartQuery The inner query object of the EXISTS statement
     */
    public function useCartExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT EXISTS query.
     *
     * @see useCartExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT EXISTS statement
     */
    public function useCartNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useExistsQuery('Cart', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Cart table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\CartQuery The inner query object of the IN statement
     */
    public function useInCartQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Cart table for a NOT IN query.
     *
     * @see useCartInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\CartQuery The inner query object of the NOT IN statement
     */
    public function useNotInCartQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\CartQuery */
        $q = $this->useInQuery('Cart', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Option object
     *
     * @param \Model\Option|ObjectCollection $option the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByOption($option, ?string $comparison = null)
    {
        if ($option instanceof \Model\Option) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $option->getUserId(), $comparison);

            return $this;
        } elseif ($option instanceof ObjectCollection) {
            $this
                ->useOptionQuery()
                ->filterByPrimaryKeys($option->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByOption() only accepts arguments of type \Model\Option or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Option relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinOption(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Option');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Option');
        }

        return $this;
    }

    /**
     * Use the Option relation Option object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\OptionQuery A secondary query class using the current class as primary query
     */
    public function useOptionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinOption($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Option', '\Model\OptionQuery');
    }

    /**
     * Use the Option relation Option object
     *
     * @param callable(\Model\OptionQuery):\Model\OptionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withOptionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useOptionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Option table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\OptionQuery The inner query object of the EXISTS statement
     */
    public function useOptionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT EXISTS query.
     *
     * @see useOptionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT EXISTS statement
     */
    public function useOptionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useExistsQuery('Option', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Option table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\OptionQuery The inner query object of the IN statement
     */
    public function useInOptionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Option table for a NOT IN query.
     *
     * @see useOptionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\OptionQuery The inner query object of the NOT IN statement
     */
    public function useNotInOptionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\OptionQuery */
        $q = $this->useInQuery('Option', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Right object
     *
     * @param \Model\Right|ObjectCollection $right the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRight($right, ?string $comparison = null)
    {
        if ($right instanceof \Model\Right) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $right->getUserId(), $comparison);

            return $this;
        } elseif ($right instanceof ObjectCollection) {
            $this
                ->useRightQuery()
                ->filterByPrimaryKeys($right->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByRight() only accepts arguments of type \Model\Right or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Right relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinRight(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Right');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Right');
        }

        return $this;
    }

    /**
     * Use the Right relation Right object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\RightQuery A secondary query class using the current class as primary query
     */
    public function useRightQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinRight($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Right', '\Model\RightQuery');
    }

    /**
     * Use the Right relation Right object
     *
     * @param callable(\Model\RightQuery):\Model\RightQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withRightQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useRightQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Right table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\RightQuery The inner query object of the EXISTS statement
     */
    public function useRightExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT EXISTS query.
     *
     * @see useRightExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT EXISTS statement
     */
    public function useRightNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useExistsQuery('Right', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Right table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\RightQuery The inner query object of the IN statement
     */
    public function useInRightQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Right table for a NOT IN query.
     *
     * @see useRightInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\RightQuery The inner query object of the NOT IN statement
     */
    public function useNotInRightQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\RightQuery */
        $q = $this->useInQuery('Right', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Session object
     *
     * @param \Model\Session|ObjectCollection $session the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySession($session, ?string $comparison = null)
    {
        if ($session instanceof \Model\Session) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $session->getUserId(), $comparison);

            return $this;
        } elseif ($session instanceof ObjectCollection) {
            $this
                ->useSessionQuery()
                ->filterByPrimaryKeys($session->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterBySession() only accepts arguments of type \Model\Session or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Session relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinSession(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Session');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Session');
        }

        return $this;
    }

    /**
     * Use the Session relation Session object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\SessionQuery A secondary query class using the current class as primary query
     */
    public function useSessionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSession($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Session', '\Model\SessionQuery');
    }

    /**
     * Use the Session relation Session object
     *
     * @param callable(\Model\SessionQuery):\Model\SessionQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withSessionQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useSessionQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Session table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\SessionQuery The inner query object of the EXISTS statement
     */
    public function useSessionExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT EXISTS query.
     *
     * @see useSessionExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT EXISTS statement
     */
    public function useSessionNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useExistsQuery('Session', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Session table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\SessionQuery The inner query object of the IN statement
     */
    public function useInSessionQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Session table for a NOT IN query.
     *
     * @see useSessionInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\SessionQuery The inner query object of the NOT IN statement
     */
    public function useNotInSessionQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\SessionQuery */
        $q = $this->useInQuery('Session', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Stock object
     *
     * @param \Model\Stock|ObjectCollection $stock the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStock($stock, ?string $comparison = null)
    {
        if ($stock instanceof \Model\Stock) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $stock->getUserId(), $comparison);

            return $this;
        } elseif ($stock instanceof ObjectCollection) {
            $this
                ->useStockQuery()
                ->filterByPrimaryKeys($stock->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByStock() only accepts arguments of type \Model\Stock or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Stock relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinStock(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Stock');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Stock');
        }

        return $this;
    }

    /**
     * Use the Stock relation Stock object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\StockQuery A secondary query class using the current class as primary query
     */
    public function useStockQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinStock($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Stock', '\Model\StockQuery');
    }

    /**
     * Use the Stock relation Stock object
     *
     * @param callable(\Model\StockQuery):\Model\StockQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withStockQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useStockQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Stock table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\StockQuery The inner query object of the EXISTS statement
     */
    public function useStockExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT EXISTS query.
     *
     * @see useStockExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT EXISTS statement
     */
    public function useStockNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useExistsQuery('Stock', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Stock table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\StockQuery The inner query object of the IN statement
     */
    public function useInStockQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Stock table for a NOT IN query.
     *
     * @see useStockInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\StockQuery The inner query object of the NOT IN statement
     */
    public function useNotInStockQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\StockQuery */
        $q = $this->useInQuery('Stock', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Wish object
     *
     * @param \Model\Wish|ObjectCollection $wish the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWish($wish, ?string $comparison = null)
    {
        if ($wish instanceof \Model\Wish) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $wish->getUserId(), $comparison);

            return $this;
        } elseif ($wish instanceof ObjectCollection) {
            $this
                ->useWishQuery()
                ->filterByPrimaryKeys($wish->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByWish() only accepts arguments of type \Model\Wish or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wish relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinWish(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wish');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Wish');
        }

        return $this;
    }

    /**
     * Use the Wish relation Wish object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\WishQuery A secondary query class using the current class as primary query
     */
    public function useWishQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWish($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wish', '\Model\WishQuery');
    }

    /**
     * Use the Wish relation Wish object
     *
     * @param callable(\Model\WishQuery):\Model\WishQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withWishQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useWishQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Wish table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\WishQuery The inner query object of the EXISTS statement
     */
    public function useWishExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useExistsQuery('Wish', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Wish table for a NOT EXISTS query.
     *
     * @see useWishExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\WishQuery The inner query object of the NOT EXISTS statement
     */
    public function useWishNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useExistsQuery('Wish', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Wish table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\WishQuery The inner query object of the IN statement
     */
    public function useInWishQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useInQuery('Wish', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Wish table for a NOT IN query.
     *
     * @see useWishInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\WishQuery The inner query object of the NOT IN statement
     */
    public function useNotInWishQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishQuery */
        $q = $this->useInQuery('Wish', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Wishlist object
     *
     * @param \Model\Wishlist|ObjectCollection $wishlist the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByWishlist($wishlist, ?string $comparison = null)
    {
        if ($wishlist instanceof \Model\Wishlist) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $wishlist->getUserId(), $comparison);

            return $this;
        } elseif ($wishlist instanceof ObjectCollection) {
            $this
                ->useWishlistQuery()
                ->filterByPrimaryKeys($wishlist->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByWishlist() only accepts arguments of type \Model\Wishlist or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Wishlist relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinWishlist(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Wishlist');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Wishlist');
        }

        return $this;
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\WishlistQuery A secondary query class using the current class as primary query
     */
    public function useWishlistQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinWishlist($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Wishlist', '\Model\WishlistQuery');
    }

    /**
     * Use the Wishlist relation Wishlist object
     *
     * @param callable(\Model\WishlistQuery):\Model\WishlistQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withWishlistQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useWishlistQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Wishlist table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\WishlistQuery The inner query object of the EXISTS statement
     */
    public function useWishlistExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT EXISTS query.
     *
     * @see useWishlistExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT EXISTS statement
     */
    public function useWishlistNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useExistsQuery('Wishlist', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Wishlist table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\WishlistQuery The inner query object of the IN statement
     */
    public function useInWishlistQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Wishlist table for a NOT IN query.
     *
     * @see useWishlistInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\WishlistQuery The inner query object of the NOT IN statement
     */
    public function useNotInWishlistQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\WishlistQuery */
        $q = $this->useInQuery('Wishlist', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildUser $user Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(UserTableMap::COL_USER_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_USER_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_USER_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(UserTableMap::COL_USER_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(UserTableMap::COL_USER_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(UserTableMap::COL_USER_CREATED);

        return $this;
    }

}
