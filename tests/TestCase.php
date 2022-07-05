<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $baseUrl = 'http://sellit.test';

    protected string $apiBaseUrl;

    protected function setup(): void
    {
        parent::setUp();
        $this->apiBaseUrl = $this->baseUrl . '/api/v1';
        $this->faker = \Faker\Factory::create();
    }
}
