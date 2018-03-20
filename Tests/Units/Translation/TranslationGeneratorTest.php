<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use PHPUnit\Framework\TestCase;
use Textmaster\Model\ProjectInterface;
use Worldia\Bundle\TextmasterBundle\Translation\TranslationGenerator;

class TranslationGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGenerateProject()
    {
        $translationManagerMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface');
        $translatableFinderMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface');
        $translatableMock = $this->createMock('Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface');
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

        $project = $generator->generate('code', [], 'languageFrom', 'name', 'category', 'briefing');

        $this->assertSame($projectMock, $project);
    }

    /**
     * @test
     */
    public function shouldGenerateProjectWithTranslationMemory()
    {
        $translationManagerMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface');
        $translatableFinderMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface');
        $translatableMock = $this->createMock('Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface');
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

        $project = $generator->generate('code', [], 'languageFrom', 'name', 'category', 'briefing');

        $this->assertSame($projectMock, $project);
    }

    /**
     * @test
     */
    public function shouldGenerateProjectWithLimit()
    {
        $translationManagerMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslationManagerInterface');
        $translatableFinderMock = $this->createMock('Worldia\Bundle\TextmasterBundle\Translation\TranslatableFinderInterface');
        $translatableMock = $this->createMock('Worldia\Bundle\ProductTestBundle\Entity\TranslatableInterface');
        $projectMock = $this->getMockBuilder('Textmaster\Model\Project')->disableOriginalConstructor()->getMock();

        $translatableFinderMock->expects($this->once())
            ->method('getCode')
            ->willReturn('code');
        $translatableFinderMock->expects($this->once())
            ->method('find')
            ->with('languageFrom', [], 5)
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

        $project = $generator->generate('code', [], 'languageFrom', 'name', 'category', 'briefing', null, [], ProjectInterface::ACTIVITY_TRANSLATION, null, true, 5);

        $this->assertSame($projectMock, $project);
    }
}
