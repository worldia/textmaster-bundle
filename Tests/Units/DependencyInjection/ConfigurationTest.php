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
                    'credentials' => [
                        'api_key' => 'My API key',
                        'api_secret' => 'My API secret',
                    ],
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
                    'credentials' => [
                        'api_key' => 'My API key',
                    ],
                ],
            ],
            'api_secret'
        );
    }
}
