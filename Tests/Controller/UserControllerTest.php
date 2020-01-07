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


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
{
    public function testListActionBlocked()
    {
        $client = static::createClient();
        $client->request('GET','/admin/users');
        $response = $client->getResponse();

        //refused 302 for anonymous
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }
    public function testListActionOK()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => '123456']);
        $client->request('GET','/admin/users');
        $response = $client->getResponse();
        //accepted for ROLE_ADMIN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        // select the form that contains this button
        $form = $buttonCrawlerNode->form();

        $form['user[username]']='UserTest1';
        $form['user[password][first]']='123456';
        $form['user[password][second]']='123456';
        $form['user[email]']='UserTest1@gmail.com';
        $form['user[roles][0]']->tick();
        //redirect soon
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        //redirect ok
        $crawler = $client->followRedirect();
        $response = $client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());

        $this->assertContains('Superbe ! L\'utilisateur a bien été ajouté.', $response->getContent());
    }

    public function testEditAction()
    {

    }

}
