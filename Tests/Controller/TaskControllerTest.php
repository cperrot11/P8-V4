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
        $crawler = $client->request($httpMethod, $url);

        $response = $client->getResponse();
        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    public function getUrlsForAnonymousUsers()
    {
        yield ['GET', '/tasks/2/edit'];
        yield ['GET', '/tasks/2/toggle'];
        yield ['GET', '/tasks/2}/delete'];
    }
}