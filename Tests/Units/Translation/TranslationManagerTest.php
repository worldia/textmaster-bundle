<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\Translation\TranslationManager;

class TranslationManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateProject()
    {
        $jobManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\EntityManager\JobManagerInterface');
        $clientMock = $this->getMock('Textmaster\Client', ['projects']);
        $routerMock = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')->disableOriginalConstructor()->getMock();
        $translatorMock = $this->getMockBuilder('Textmaster\Translator\Translator')->setMethods(['create'])->disableOriginalConstructor()->getMock();

        $translatableMock = $this->getMock('TranslatableInterface', ['getId']);
        $apiMock = $this->getMockBuilder('Textmaster\Api\Project')->disableOriginalConstructor()->getMock();
        $showValues = [
            'id' => '123456',
            'name' => 'Project 1',
            'status' => ProjectInterface::STATUS_IN_CREATION,
            'ctype' => ProjectInterface::ACTIVITY_TRANSLATION,
            'language_from' => 'en',
            'language_to' => 'fr',
            'category' => 'CO21',
            'project_briefing' => 'Lorem ipsum...',
            'options' => ['language_level' => 'premium'],
        ];
        $documentMock = $this->getMock('Textmaster\Model\DocumentInterface');

        $clientMock->expects($this->once())
            ->method('projects')
            ->willReturn($apiMock);

        $apiMock->expects($this->once())
            ->method('create')
            ->willReturn($showValues);

        $routerMock->expects($this->once())
            ->method('generate')
            ->willReturn('http://callback.url');

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $translatorMock->expects($this->once())
            ->method('create')
            ->willReturn($documentMock);

        $translationManager = new TranslationManager($jobManagerMock, $clientMock, $routerMock, $translatorMock);
        $project = $translationManager->create([$translatableMock], 'Project 1', 'en', 'fr', 'CO21', 'Lorem ipsum...', ['language_level' => 'premium']);

        $this->assertTrue(in_array('Textmaster\Model\ProjectInterface', class_implements($project)));
        $this->assertSame('Project 1', $project->getName());
    }
}
