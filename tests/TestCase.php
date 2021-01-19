<?php

namespace Spatie\RayBundle\Tests;

use \PHPUnit\Framework\TestCase as BaseTestCase;
use Spatie\RayBundle\Tests\TestClasses\FakeClient;

class TestCase extends BaseTestCase
{
    protected FakeClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();
    }
}
