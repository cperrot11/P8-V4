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

namespace App\tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testGetId()
    {
        $user = new User();
        static::assertEquals($user->getId(),null);
    }

    public function testGetSetPassword()
    {
        $user = new User();
        $user->setPassword('Test password');
        static::assertEquals($user->getPassword(), 'Test password');
    }

    public function testGetSetUsername()
    {
        $user = new User();
        $user->setUsername('Bill GATES');
        static::assertEquals($user->getUsername(), 'Bill GATES');
    }

    public function testGetSetEmail()
    {
        $user = new User();
        $user->setEmail('Test@email.com');
        static::assertEquals($user->getEmail(), 'Test@email.com');
    }

    public function testEraseCredentials()
    {
        $user = new User();
        static::assertEquals($user->eraseCredentials(), null);
    }


    public function testGetSetRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        static::assertEquals($user->getRoles(), ['ROLE_ADMIN', 'ROLE_USER']);
    }


    public function testGetSalt()
    {
        $user = new User();
        static::assertEquals($user->getSalt(), null);

    }

}
