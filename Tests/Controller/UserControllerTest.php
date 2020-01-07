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
        $client->submit($form);

        //redirect ok
        $this->assertTrue($client->getResponse()->isRedirect());

        $crawler = $client->followRedirect();
        //Message ok
        $this->assertContains('Superbe', $client->getResponse()->getContent());
    }

    public function testEditAction()
    {
        $newUserEmail = 'admin_jane@symfony.com';
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => '123456']);
        $crawler = $client->request('GET', '/admin/users/2/edit');

        //test page display
        static::assertSame(200, $client->getResponse()->getStatusCode());
        //test Form field
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[password][second]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        static::assertSame(2, $crawler->filter('input[name="user[roles][]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'user';
        $form['user[password][first]'] = '123457';
        $form['user[password][second]'] = '123457';
        $form['user[email]'] = $newUserEmail;
        $form['user[roles][0]']->tick();
        $client->submit($form);

        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Superbe', $client->getResponse()->getContent());

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([
            'email' => $newUserEmail,
        ]);
        //test User change Email OK
        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());
    }

    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url)
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);
        $response = $client->getResponse();
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
