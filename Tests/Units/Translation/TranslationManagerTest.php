<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use Worldia\Bundle\TextmasterBundle\Translation\TranslationManager;

class TranslationManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateProject()
    {
        $textmasterManagerMock = $this->getMockBuilder('Textmaster\Manager')->disableOriginalConstructor()->getMock();
        $routerMock = $this->getMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $translatorMock = $this->getMockBuilder('Textmaster\Translator\Translator')->setMethods(['create'])->disableOriginalConstructor()->getMock();

        $projectMock = $this->getMockBuilder('Textmaster\Model\Project')->disableOriginalConstructor()->getMock();
        $translatableMock = $this->getMock('TranslatableInterface', ['getId']);
        $documentMock = $this->getMock('Textmaster\Model\DocumentInterface');

        $textmasterManagerMock->expects($this->once())
            ->method('getProject')
            ->willReturn($projectMock);

        $projectMock->expects($this->once())
            ->method('setName')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setActivity')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setLanguageFrom')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setLanguageTo')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setCategory')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setBriefing')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setOptions')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setCallback')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('setWorkTemplate')
            ->willReturn($projectMock);
        $projectMock->expects($this->once())
            ->method('save')
            ->willReturn($projectMock);

        $routerMock->expects($this->exactly(2))
            ->method('generate')
            ->willReturn('http://callback.url');

        $translatableMock->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $translatorMock->expects($this->once())
            ->method('create')
            ->willReturn($documentMock);

        $translationManager = new TranslationManager($textmasterManagerMock, $translatorMock, $routerMock, 150);
        $project = $translationManager->create([$translatableMock], 'Project 1', 'en', 'fr', 'CO21', 'Lorem ipsum...', ['language_level' => 'premium']);

        $this->assertTrue(in_array('Textmaster\Model\ProjectInterface', class_implements($project)));
        $this->assertSame($projectMock, $project);
    }
}
