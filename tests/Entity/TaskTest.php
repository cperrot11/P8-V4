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
 */

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{

    public function testGetSetCreatedAt()
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTime);
        static::assertInstanceOf(\DateTime::class, $task->getCreatedAt());
    }

    public function testGetSetTitle()
    {
        $task = new Task();
        $task->setTitle('Test title');
        static::assertEquals($task->getTitle(), 'Test title');
    }

    public function testGetSetContent()
    {
        $task = new Task();
        $task->setContent('Test content');
        static::assertEquals($task->getContent(), 'Test content');
    }

    public function testToggleIsDone()
    {
        $task = new Task();
        $task->toggle(true);
        static::assertEquals($task->isDone(), true);
    }
}
