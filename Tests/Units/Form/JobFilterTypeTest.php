<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\Translation;

use Symfony\Component\Form\Test\TypeTestCase;
use Worldia\Bundle\TextmasterBundle\Entity\JobInterface;
use Worldia\Bundle\TextmasterBundle\Form\Filter\JobFilterType;

class JobFilterTypeTest extends TypeTestCase
{
    /**
     * @test
     */
    public function testForm()
    {
        $formData = [
            'status' => JobInterface::STATUS_STARTED,
        ];

        $form = $this->factory->create(JobFilterType::class);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('textmaster', $form->getConfig()->getOption('translation_domain'));

        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
