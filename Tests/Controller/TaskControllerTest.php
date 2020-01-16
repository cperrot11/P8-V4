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


use App\Controller\BaseController;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends BaseController
{
    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testActionBlocked(string $httpMethod, string $url)
    {
        //if the user is not login, redirect te login page
        $this->connect();
        $this->client->request($httpMethod, $url);

        $crawler = $this->client->getResponse();
        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );

        $crawler = $this->client->followRedirect();
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
        $this->connect();
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertGreaterThan(
            0,
            $crawler->filter('h4')->count(),
            'La liste affiche des tâches.'
        );
    }

    public function testCreateAction()
    {
        $this->connectUser();
        $crawler = $this->client->request('GET', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]'] = 'Tache de test';
        $form['task[content]'] = 'Lorem ipsum test';

        $crawler = $this->client->submit($form);

        // Last task added
        $newTask = $crawler->filter('.caption')->last()->filter('div>p')->text();
        $this->assertSame('Lorem ipsum test', $newTask);
    }

    public function testEditAction()
    {
        $this->connectAdmin();
        $crawler = $this->client->request('GET', '/tasks/2/edit');
        $form = $crawler->selectButton('Modifier')->form();

        $form['task[content]'] = 'Update content';
        $crawler = $this->client->submit($form);

        // Test if success message is displayed
        static::assertContains("Superbe ! La tâche a bien été modifiée.", $crawler->filter('div.alert.alert-success')->text());
    }

    public function testDeleteAnonymTaskWithAdmin()
    {
        $this->connectAdmin();
        $crawler = $this->client->request('GET', '/tasks/3/delete');

        // Test if success message is displayed
        static::assertContains("Superbe ! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }

    //Test delete task by bad user
    public function testDeleteTaskWithBadUser()
    {
        $this->connectUser();
        $crawler = $this->client->request('GET', '/tasks/9/delete');

        // Test if danger message is displayed
        static::assertContains("Oops ! Seul le propriétaire", $crawler->filter('div.alert.alert-danger')->text());
    }

    //Test delete task by his author
    public function testDeleteTaskWithGoodUser()
    {
        $this->connectUser();
        $task = $this->client->getContainer()
            ->get('doctrine')
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'Tache de test']);
        $lien = '/tasks/' . $task->getId() . '/delete';

        $crawler = $this->client->request('GET', $lien);
        // Test if delete message is displayed
        static::assertContains("Superbe ! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }
}