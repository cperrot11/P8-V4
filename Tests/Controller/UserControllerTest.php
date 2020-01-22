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


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends BaseController
{
    public function testListActionBlocked()
    {
        $this->connect();
        $this->client->request('GET','/admin/users');
        $response = $this->client->getResponse();

        //refused ->302 for anonymous
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }
    public function testListActionOK()
    {
        $this->connectAdmin();
        $this->client->request('GET','/admin/users');
        $response = $this->client->getResponse();
        //accepted for ROLE_ADMIN
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->connect();
        $this->client->followRedirects();
        $crawler = $this->client->request('GET','/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        // select the form that contains this button
        $form = $buttonCrawlerNode->form();

        $form['user[username]']='UserTest1';
        $form['user[password][first]']='123456';
        $form['user[password][second]']='123456';
        $form['user[email]']='UserTest1@gmail.com';
        $form['user[roles][0]']->tick();
        $crawler= $this->client->submit($form);


        // Test if success message is displayed
        static::assertContains("Superbe ! L'utilisateur a bien été ajouté.", $crawler->filter('div.alert.alert-success')->text());
        // Let the Database clean
        $this->deleteUser('UserTest1');
    }

    public function testEditAction()
    {
        $this->connectAdmin();
        /** @var User $user */
        $user = $this->client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([
            'id' => 2,
        ]);
        $newUserEmail = 'admin_jane@symfony.com';

        $crawler = $this->client->request('GET', '/admin/users/2/edit');

        //test page display
        $response = $this->client->getResponse();
        static::assertSame(200, $response->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = $user->getUsername();
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';
        $form['user[email]'] = $newUserEmail;
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        $response = $this->client->getResponse();
        static::assertSame(200, $response->getStatusCode());
        $this->assertContains('Superbe', $response->getContent());


        //test User change Email OK
        $user = $this->client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([
            'id' => 2,
        ]);
        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());
    }

    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url)
    {
        $this->connect();
        $this->client->request($httpMethod, $url);
        $response = $this->client->getResponse();
        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }
    public function getUrlsForAnonymousUsers()
    {
        yield ['GET', '/admin/users'];
        yield ['GET', '/admin/users/2/edit'];
    }

}
