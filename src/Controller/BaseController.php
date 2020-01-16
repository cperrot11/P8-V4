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

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseController extends WebTestCase
{

    protected $client;

    public function connect()
    {
        $this->client = static::createClient();
    }

    public function connectAdmin()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin@gmail.com',
            'PHP_AUTH_PW' => '123456']);
        $this->client->followRedirects();
    }

    public function connectUser()
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'user@gmail.com',
            'PHP_AUTH_PW' => '123456']);
        $this->client->followRedirects();
    }
}