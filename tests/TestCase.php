<?php

namespace Spatie\RayBundle\Tests;

use Spatie\RayBundle\Tests\TestClasses\FakeClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestCase extends KernelTestCase
{
    protected FakeClient $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();

        self::bootKernel();
    }
}
