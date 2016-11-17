<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\EventListener;

use Worldia\Bundle\TextmasterBundle\EventListener\ProjectListener;
use Worldia\Bundle\TextmasterBundle\Events;

class ProjectListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectListener
     */
    protected $listener;

    protected $jobManagerMock;

    public function setUp()
    {
        $this->jobManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface');
        $this->listener = new ProjectListener($this->jobManagerMock);
    }

    /**
     * @test
     */
    public function shouldGetSubscribedEvents()
    {
        $events = [
            Events::PROJECT_IN_PROGRESS => 'onCallbackProjectInProgress',
        ];

        $this->assertSame(ProjectListener::getSubscribedEvents(), $events);
    }

    /**
     * @test
     */
    public function shouldStartJobsWhenProjectInProgress()
    {
        $eventMock = $this->getMockBuilder('Textmaster\Event\CallbackEvent')->disableOriginalConstructor()->getMock();
        $projectMock = $this->getMock('Textmaster\Model\ProjectInterface');

        $eventMock->expects($this->once())
            ->method('getSubject')
            ->willReturn($projectMock);

        $this->jobManagerMock->expects($this->once())
            ->method('startJobs');

        $this->listener->onCallbackProjectInProgress($eventMock);
    }
}
