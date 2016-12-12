<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use Worldia\Bundle\TextmasterBundle\Translation\TranslationGenerator;

class TranslationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldGenerateProject()
    {
        $translationManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface');
        $translatableFinderMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface');
        $translatableMock = $this->getMock('TranslatableInterface');
        $projectMock = $this->getMockBuilder('Textmaster\Model\Project')->disableOriginalConstructor()->getMock();

        $translatableFinderMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');
        $translatableFinderMock->expects($this->once())
            ->method('find')
            ->willReturn([$translatableMock]);

        $translationManagerMock->expects($this->once())
            ->method('create')
            ->willReturn($projectMock);

        $projectMock->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);
        $projectMock->expects($this->once())
            ->method('launch')
            ->willReturn($projectMock);

        $generator = new TranslationGenerator($translationManagerMock);
        $generator->addTranslatableFinder($translatableFinderMock);

        $project = $generator->generate('code', [], 'languageFrom', 'languageTo', 'name', 'category', 'briefing');

        $this->assertSame($projectMock, $project);
    }

    /**
     * @test
     */
    public function shouldGenerateProjectWithTranslationMemory()
    {
        $translationManagerMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface');
        $translatableFinderMock = $this->getMock('Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface');
        $translatableMock = $this->getMock('TranslatableInterface');
        $projectMock = $this->getMockBuilder('Textmaster\Model\Project')->disableOriginalConstructor()->getMock();

        $translatableFinderMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');
        $translatableFinderMock->expects($this->once())
            ->method('find')
            ->willReturn([$translatableMock]);

        $translationManagerMock->expects($this->once())
            ->method('create')
            ->willReturn($projectMock);

        $projectMock->expects($this->once())
            ->method('getOptions')
            ->willReturn(['translation_memory' => true]);

        $generator = new TranslationGenerator($translationManagerMock);
        $generator->addTranslatableFinder($translatableFinderMock);

        $project = $generator->generate('code', [], 'languageFrom', 'languageTo', 'name', 'category', 'briefing');

        $this->assertSame($projectMock, $project);
    }
}
