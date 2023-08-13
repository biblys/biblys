<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

use Biblys\Test\EntityFactory;
use Propel\Runtime\Exception\PropelException;

require_once "setUp.php";

class UserTest extends PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $um = new AxysUserManager();

        $email = 'user'.rand(0,999).'@biblys.fr';

        $user = $um->create(array('user_email' => $email));

        $this->assertInstanceOf(AxysUser::class, $user);

        return $user;
    }

    /**
     * @depends testCreate
     */
    public function testGet(AxysUser $user)
    {
        $um = new AxysUserManager();

        $get_user = $um->get(array('user_email' => $user->get('email')));

        $this->assertInstanceOf(AxysUser::class, $get_user);
        $this->assertEquals($user->get('id'), $get_user->get('id'));

        return $user;
    }

    /**
    * Test get and create customer
    * @depends testGet
    */
    public function testGetCustomerAndCreate(AxysUser $user)
    {
        $customer = $user->getCustomer(true);

        $this->assertInstanceOf('Customer', $customer);

        return $customer;
    }

    /**
    * Test get existing customer
    * @depends testGet
    * @depends testGetCustomerAndCreate
    */
    public function testGetCustomer(AxysUser $user, Customer $customer)
    {
        $customer2 = $user->getCustomer();

        $this->assertInstanceOf('Customer', $customer2);
        $this->assertEquals($customer->get('id'), $customer2->get('id'));
    }

    /**
    * Test get and create wishlist
    * @depends testGet
    */
    public function testGetWishlistOrCreate(AxysUser $user)
    {
        $wishlist = $user->getWishlist(true);

        $this->assertInstanceOf('Wishlist', $wishlist);
        $this->assertEquals($wishlist->get('axys_user_id'), $user->get('id'));
        $this->assertEquals($wishlist->get('name'), "Ma liste d'envies Biblys");
        $this->assertEquals($wishlist->get('current'), 1);

        return $wishlist;
    }

    /**
    * Test get existing wishlist
    * @depends testGet
    * @depends testGetWishlistOrCreate
    */
    public function testGetWishlist(AxysUser $user, Wishlist $wishlist)
    {
        $wishlist2 = $user->getWishlist();

        $this->assertInstanceOf('Wishlist', $wishlist);
        $this->assertEquals($wishlist->get('id'), $wishlist2->get('id'));
        $this->assertEquals($wishlist->get('current'), 1);
    }

    /**
     * Test if user has purchased a book
     * @depends testCreate
     */
    public function testHasPurchased(AxysUser $user)
    {
        $am = new ArticleManager();
        $article = EntityFactory::createArticle();

        $this->assertFalse($user->hasPurchased($article));

        $sm = new StockManager();
        $sm->create([
            "article_id" => $article->get('id'),
            "axys_user_id" => $user->get('id')
        ]);

        $this->assertTrue($user->hasPurchased($article));
    }

    public function testAddToLibrary()
    {
        global $_SITE;

        // given
        $publisher = EntityFactory::createPublisher();
        $_SITE->set("publisher_id", $publisher->get("id"));
        $um = new AxysUserManager();
        $user = $um->create(["user_email" => "customer@biblys.fr"]);
        $am = new ArticleManager();
        $article = $am->create([
            "type_id" => 2,
            "publisher_id" => $publisher->get("id"),
        ]);
        $sm = new StockManager();

        // when
        $um->addToLibrary($user, [$article]);

        // then
        $copy = $sm->get([
            "article_id" => $article->get("id"),
            "axys_user_id" => $user->get("id"),
        ]);
        $this->assertInstanceOf(
            "Stock",
            $copy,
            "it should have created a new copy"
        );
    }
}
