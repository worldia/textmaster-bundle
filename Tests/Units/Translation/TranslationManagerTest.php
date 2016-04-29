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
        $clientMock = $this->getMock('Textmaster\Client', ['projects']);
        $routerMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
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

        $clientMock->expects($this->exactly(2))
            ->method('projects')
            ->willReturn($apiMock);

        $apiMock->expects($this->once())
            ->method('create')
            ->willReturn($showValues);

        $apiMock->expects($this->once())
            ->method('launch')
            ->willReturn($showValues);

        $routerMock->expects($this->exactly(2))
            ->method('generate')
            ->willReturn('http://callback.url');

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $translatorMock->expects($this->once())
            ->method('create')
            ->willReturn($documentMock);

        $translationManager = new TranslationManager($clientMock, $translatorMock, $routerMock);
        $project = $translationManager->create([$translatableMock], 'Project 1', 'en', 'fr', 'CO21', 'Lorem ipsum...', ['language_level' => 'premium']);

        $this->assertTrue(in_array('Textmaster\Model\ProjectInterface', class_implements($project)));
        $this->assertSame('Project 1', $project->getName());
    }
}
