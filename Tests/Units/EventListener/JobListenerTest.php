<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EventListener;

use Doctrine\ORM\Events;
use Worldia\Bundle\TextmasterBundle\EventListener\JobListener;

class JobListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var JobListener
     */
    protected $listener;

    protected $eventMock;

    public function setUp()
    {
        $this->eventMock = $this
            ->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->listener = new JobListener();
    }

    /**
     * @test
     */
    public function shouldGetSubscribedEvents()
    {
        $events = [
            Events::postLoad,
        ];

        $this->assertSame($this->listener->getSubscribedEvents(), $events);
    }

    /**
     * @test
     */
    public function shouldSetTranslatableOnPostLoad()
    {
        $jobMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Entity\JobInterface');
        $translatableMock = $this->getMock('TranslatableInterface');
        $objectManagerMock = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $objectRepositoryMock = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->eventMock->expects($this->once())
            ->method('getEntity')
            ->willReturn($jobMock);

        $jobMock->expects($this->once())
            ->method('getTranslatableClass')
            ->willReturn('TranslatableInterface');

        $this->eventMock->expects($this->once())
            ->method('getObjectManager')
            ->willReturn($objectManagerMock);

        $objectManagerMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($objectRepositoryMock);

        $objectRepositoryMock->expects($this->once())
            ->method('find')
            ->willReturn($translatableMock);

        $this->listener->postLoad($this->eventMock);
    }
}
