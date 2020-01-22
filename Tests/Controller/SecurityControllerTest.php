<?php
/**
 * Short description
 *
 * PHP version 7.2
 *
 * @category
 * @package
 * @author Christophe PERROTIN
 * @copyright 2018
 * @license MIT License
 * @link http://wwww.perrotin.eu
 */

namespace App\Tests\Controller;


use App\Controller\SecurityController;

class SecurityControllerTest extends BaseController
{
    public function testLoginFailed()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email'] = 'admin@gmail.com';
        $form['password'] = '12345';

        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        // Test if success message is displayed
        static::assertContains("Invalid credentials.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testLoginOk()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email'] = 'admin@gmail.com';
        $form['password'] = '123456';

        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        // Test if success message is displayed
        static::assertContains(" Bonjour admin", $crawler->filter('span.pull-right.cpp-1')->text());
    }

    public function testLogout()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email'] = 'admin@gmail.com';
        $form['password'] = '123456';

        $crawler = $this->client->submit($form);
        $link = $crawler->selectLink('Se déconnecter')->link();
        $crawler = $this->client->click($link);
        static::assertContains("Se connecter", $crawler->filter('a.btn.btn-success')->text());
    }
}