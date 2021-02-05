<?php

namespace Spatie\RayBundle\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Spatie\RayBundle\Tests\TestClasses\User;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\HttpKernel\Log\Logger;

class RayTest extends TestCase
{
    use MatchesSnapshots;

    private LoggerInterface $logger;

    private ?EntityManagerInterface $entityManager = null;

    public function setUp(): void
    {
        $this->logger = new Logger();

        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /** @test */
    public function when_disabled_nothing_will_be_sent_to_ray()
    {
        $this->entityManager->getRepository(User::class)->find(1);
        ray()->settings->enabled = false;

        ray('test');

        // re-enable for next tests
        ray()->enable();

        $this->assertCount(0, $this->client->sentPayloads());
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
        //app(Settings::class)->send_log_calls_to_ray = false;

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
    public function it_can_start_logging_queries()
    {
        ray()->showQueries();

        //DB::table('users')->get('id');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_start_logging_queries_using_alias()
    {
        ray()->queries();

        //DB::table('users')->get('id');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_stop_logging_queries()
    {
        ray()->showQueries();

        //DB::table('users')->get('id');
        //DB::table('users')->get('id');
        $this->assertCount(2, $this->client->sentPayloads());

        ray()->stopShowingQueries();
        //DB::table('users')->get('id');
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function calling_log_queries_twice_will_not_log_all_queries_twice()
    {
        ray()->showQueries();
        ray()->showQueries();

        //DB::table('users')->get('id');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_log_all_queries_in_a_callable()
    {
        ray()->showQueries(function () {
            // will be logged
            //DB::table('users')->where('id', 1)->get();
        });
        $this->assertCount(1, $this->client->sentPayloads());

        // will not be logged
        //DB::table('users')->get('id');
        $this->assertCount(1, $this->client->sentPayloads());
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
    public function it_can_log_dumps()
    {
        dump('test');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_one_model_to_ray()
    {
        $this->markTestSkipped('Models do not need special treatment in Symfony.');
    }

    /** @test */
    public function it_can_send_multiple_models_to_ray()
    {
        $this->markTestSkipped('Models do not need special treatment in Symfony.');
    }

    /** @test */
    public function it_can_send_a_single_models_to_ray_using_models()
    {
        $this->markTestSkipped('Models do not need special treatment in Symfony.');
    }

    /** @test */
    public function it_can_send_a_collection_of_models_to_ray_using_models()
    {
        $this->markTestSkipped('Models do not need special treatment in Symfony.');
    }

    /** @test */
    public function it_has_a_chainable_collection_macro_to_send_things_to_ray()
    {
        $this->markTestSkipped("Don't think Symfony has a collection alternative.");
    }

    /** @test */
    public function it_can_send_the_mailable_payload()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_send_a_logged_mailable()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_send_a_class_based_event_to_ray()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_send_a_string_based_event_to_ray()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_will_not_send_any_events_if_it_is_not_enabled()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function the_show_events_function_accepts_a_callable()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_replace_the_remote_path_with_the_local_one()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_render_and_send_markdown()
    {
        $this->markTestSkipped("TO DO");
    }

    protected function assertMatchesOsSafeSnapshot($data)
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_send_a_json_test_response_to_ray()
    {
        $this->markTestSkipped("TO DO");
    }

    /** @test */
    public function it_can_send_a_regular_test_response_to_ray()
    {
        $this->markTestSkipped("TO DO");
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
