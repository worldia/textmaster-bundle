<?php

namespace Worldia\Bundle\TextmasterBundle\Tests\Units\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Worldia\Bundle\TextmasterBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     * @test
     */
    public function assertValidConfiguration()
    {
        $this->assertConfigurationIsValid(
            [
                [
                    'templates' => [
                        'project' => [
                            'show' => 'Template:Project:show.html.twig',
                        ],
                    ],
                    'dsn' => 'http://api-key:api-secret@api.textmaster.com/v1',
                    'mapping_properties' => [
                        'Worldia\Bundle\ProductTestBundle\Entity\Product' => ['title', 'description'],
                    ],
                    'copywriting_word_count' => 200,
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function assertInvalidConfiguration()
    {
        $this->assertConfigurationIsInvalid(
            [
                [
                    'dsn' => null,
                ],
            ],
            'copywriting_word_count'
        );
    }
}
