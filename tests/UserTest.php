<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "setUp.php";

class UserTest extends PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $um = new UserManager();

        $email = 'user'.rand(0,999).'@biblys.fr';

        $user = $um->create(array('user_email' => $email));

        $this->assertInstanceOf('User', $user);

        return $user;
    }

    /**
     * @depends testCreate
     */
    public function testGet(User $user)
    {
        $um = new UserManager();

        $get_user = $um->get(array('user_email' => $user->get('email')));

        $this->assertInstanceOf('User', $get_user);
        $this->assertEquals($user->get('id'), $get_user->get('id'));

        return $user;
    }

    /**
    * Test get and create customer
    * @depends testGet
    */
    public function testGetCustomerAndCreate(User $user)
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
    public function testGetCustomer(User $user, Customer $customer)
    {
        $customer2 = $user->getCustomer();

        $this->assertInstanceOf('Customer', $customer2);
        $this->assertEquals($customer->get('id'), $customer2->get('id'));
    }

    /**
    * Test get and create wishlist
    * @depends testGet
    */
    public function testGetWishlistOrCreate(User $user)
    {
        $wishlist = $user->getWishlist(true);

        $this->assertInstanceOf('Wishlist', $wishlist);
        $this->assertEquals($wishlist->get('user_id'), $user->get('id'));
        $this->assertEquals($wishlist->get('name'), "Ma liste d'envies Biblys");
        $this->assertEquals($wishlist->get('current'), 1);

        return $wishlist;
    }

    /**
    * Test get existing wishlist
    * @depends testGet
    * @depends testGetWishlistOrCreate
    */
    public function testGetWishlist(User $user, Wishlist $wishlist)
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
    public function testHasPurchased(User $user)
    {
        $am = new ArticleManager();
        $article = $am->create();

        $this->assertFalse($user->hasPurchased($article));

        $sm = new StockManager();
        $sm->create([
            "article_id" => $article->get('id'),
            "user_id" => $user->get('id')
        ]);

        $this->assertTrue($user->hasPurchased($article));
    }

    public function testAuthenticateWithCorrectEmail()
    {
        // given
        $um = new UserManager();
        $user = $um->create([
            "user_email" => "hackerman@example.net",
            "user_screen_name" => "hackerman",
            "user_new_password" => "password",
        ]);

        // when
        $user = $um->authenticate("hackerman@example.net", "password");

        // then
        $this->assertInstanceOf("User", $user, "it should return a user");
        $this->assertEquals(
            "hackerman",
            $user->get("screen_name"),
            "it should return the correct user"
        );
    }

    public function testAuthenticateWithCorrectUsername()
    {
        // given
        $um = new UserManager();
        $user = $um->create([
            "user_email" => "graceomalley@example.net",
            "user_screen_name" => "grace",
            "user_new_password" => "password",
        ]);

        // when
        $user = $um->authenticate("hackerman", "password");

        // then
        $this->assertInstanceOf("User", $user, "it should return a user");
        $this->assertEquals(
            "hackerman",
            $user->get("screen_name"),
            "it should return the correct user"
        );
    }

    public function testAuthenticateWithIncorrectEmail()
    {
        // given
        $um = new UserManager();
        $user = $um->create([
            "user_email" => "user@example.net",
            "user_screen_name" => "user",
            "user_new_password" => "password",
        ]);

        // when
        $user = $um->authenticate("youseur@example.net", "password");

        // then
        $this->assertEquals(
            false,
            $user,
            "it should return false"
        );
    }

    public function testAuthenticateWithIncorrectUsername()
    {
        // given
        $um = new UserManager();
        $user = $um->create([
            "user_email" => "user2@example.net",
            "user_screen_name" => "User Two",
            "user_new_password" => "password",
        ]);

        // when
        $user = $um->authenticate("Youzeur Tou", "password");

        // then
        $this->assertEquals(
            false,
            $user,
            "it should return false"
        );
    }

    public function testAuthenticateWithIncorrectPassword()
    {
        // given
        $um = new UserManager();
        $user = $um->create([
            "user_email" => "user3@example.net",
            "user_screen_name" => "User Three",
            "user_new_password" => "password",
        ]);

        // when
        $user = $um->authenticate("User Three", "passw0rd");

        // then
        $this->assertEquals(
            false,
            $user,
            "it should return false"
        );
    }
}
