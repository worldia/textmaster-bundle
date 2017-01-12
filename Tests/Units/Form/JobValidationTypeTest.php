<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use Symfony\Component\Form\Test\TypeTestCase;
use Textmaster\Model\DocumentInterface;
use Worldia\Bundle\TextmasterBundle\Form\JobValidationType;

class JobValidationTypeTest extends TypeTestCase
{
    /**
     * @test
     */
    public function testAcceptForm()
    {
        $formData = [
            'satisfaction' => DocumentInterface::SATISFACTION_NEUTRAL,
        ];

        $form = $this->factory->create(JobValidationType::class, null, ['accept' => true]);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->getConfig()->getOption('accept'));
        $this->assertEquals('textmaster', $form->getConfig()->getOption('translation_domain'));

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @test
     */
    public function testRejectForm()
    {
        $formData = [
            'message' => 'Rejection message',
        ];

        $form = $this->factory->create(JobValidationType::class, null, ['accept' => false]);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->getConfig()->getOption('accept'));
        $this->assertEquals('textmaster', $form->getConfig()->getOption('translation_domain'));

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
