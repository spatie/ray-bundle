<?php

namespace Spatie\RayBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spatie\Ray\Settings\Settings;
use Spatie\RayBundle\Ray;
use Spatie\RayBundle\Tests\TestClasses\FakeClient;
use Spatie\RayBundle\Tests\TestClasses\Kernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RayTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    private LoggerInterface $logger;

    private Settings $settings;

    private array $defaultSettings;

    private FakeClient $client;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->client = new FakeClient();

        $this->settings = new Settings($container->getParameter('spatie_ray.settings'));

        //$this->entityManager = $kernel->getContainer()
        //    ->get('doctrine')
        //    ->getManager();

        //$this->logger = $kernel->getContainer()->get('logger');
    }

    /** @test */
    public function when_disabled_nothing_will_be_sent_to_ray()
    {
        $this->defaultSettings['enable'] = false;

        $this->settings->setDefaultSettings($this->defaultSettings);

        ray('test');

        // re-enable for next tests
        ray()->enable();

        $this->assertCount(0, $this->client->sentPayloads());
    }

    /** @test */
    public function when_enabled_nothing_will_be_sent_to_ray()
    {
        ray('test');

        // re-enable for next tests
        ray()->enable();

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_will_send_logs_to_ray_by_default()
    {
        $this->logger->info('hey');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_will_not_send_logs_to_ray_when_disabled()
    {
        $this->defaultSettings['send_log_calls_to_ray'] = false;
        $this->settings->setDefaultSettings($this->defaultSettings);

        $this->logger->info('hey');

        $this->assertCount(0, $this->client->sentPayloads());
    }

    /** @test */
    public function it_will_not_blow_up_when_not_passing_anything()
    {
        ray();

        $this->assertCount(0, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_be_disabled()
    {
        ray()->disable();
        ray('test');
        $this->assertCount(0, $this->client->sentPayloads());

        ray()->enable();
        ray('not test');
        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_check_enabled_status()
    {
        ray()->disable();
        $this->assertEquals(false, ray()->enabled());

        ray()->enable();
        $this->assertEquals(true, ray()->enabled());
    }

    /** @test */
    public function it_can_check_disabled_status()
    {
        ray()->disable();
        $this->assertEquals(true, ray()->disabled());

        ray()->enable();
        $this->assertEquals(false, ray()->disabled());
    }

    /** @test */
    public function it_can_replace_the_remote_path_with_the_local_one()
    {
        $this->defaultSettings['remote_path'] = __DIR__;
        $this->defaultSettings['local_path'] = 'local_tests';
        $this->settings->setDefaultSettings($this->defaultSettings);


        ray('test');

        $this->assertStringContainsString(
            'local_tests',
            \Arr::get($this->client->sentPayloads(), '0.payloads.0.origin.file')
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
