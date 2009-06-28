<?php
class CustomerControllerTest extends ControllerTestCase
{
    public function login($email, $passwd)
    {
        $this->request->setMethod('POST')
                      ->setPost(array(
                          'email' => $email,
                          'passwd' => $passwd,
                      ));
        $this->dispatch('/customer/authenticate');
        $this->assertRedirectTo('/customer');
        $this->tearDown();
    }

    public function testUserCanAuthenticate()
    {
        $this->login('me@me.com','123456');
    }

    public function testFailedLogin()
    {
        $this->request->setMethod('POST')
                      ->setPost(array(
                          'email' => 'me@me.com',
                          'passwd' => 'asdasdasdasdasd',
                      ));
        $this->dispatch('/customer/authenticate');
        $this->assertQueryContentContains('.error', 'Login failed, please try again.');
    }

    public function testUserHasProfileAccessWhenLoggedIn()
    {
        $this->login('me@me.com','123456');
        $this->dispatch('/customer');
        $this->assertQuery('form');
        $this->assertXpath('/html/body/div[2]/div[2]/form');
    }

    public function testAdminAreaRoute()
    {
        //authenticate admin user
        $this->login('me@me.com','123456');
        $this->dispatch('/admin');
        $this->assertRoute('admin');
    }

    public function testUnauthenticatedUserCannotAccessAdmin()
    {
        $this->dispatch('/admin');
        $this->assertQueryContentContains('p','Access Denied');
    }
}