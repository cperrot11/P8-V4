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


use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public  function testActionBlocked(string $httpMethod, string $url)
    {
        //if the user is not login, redirect te login page
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $crawler = $client->getResponse();
        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );

        $crawler = $client->followRedirect();
        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="email"]')->count());
        static::assertSame(1, $crawler->filter('input[name="password"]')->count());
    }

    public function getUrlsForAnonymousUsers()
    {
        yield ['GET', '/tasks/2/edit'];
        yield ['GET', '/tasks/2/toggle'];
        yield ['GET', '/tasks/2/delete'];
    }
    public function testListAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');
        $this->assertGreaterThan(
            0,
            $crawler->filter('h4')->count(),
            'La liste affiche des tâches.'
        );
    }
    public function testCreateAction()
    {
        $client = static::createClient([],[
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW'   => '123456']);
        $client->followRedirects();
        $crawler = $client->request('GET','/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]']='Tache de test';
        $form['task[content]']='Lorem ipsum test';

        $crawler= $client->submit($form);

//      dernière tâche ajoutée
        $newTask = $crawler->filter('.caption')->last()->filter('div>p')->text();
        $this->assertSame('Lorem ipsum test', $newTask);
    }

}