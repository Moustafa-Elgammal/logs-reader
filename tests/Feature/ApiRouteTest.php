<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiRouteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_status_read_file_work()
    {
        $response = $this->get('/api/file');

        $response->assertStatus(200);
    }

    public function test_file_not_exist_error()
    {
        $response = $this->get('/api/file?file_path=tesst.log');
        $this->assertStringContainsString($response->decodeResponseJson()['errors'][0],'file does not exist');

    }

    public function test_status_head_api_file_work()
    {
        $response = $this->get('/api/head');
        $response->assertStatus(200);
    }

    public function test_head_api_file_error_exist()
    {
        $response = $this->get('/api/head?file_path=tesst.log');
        $this->assertStringContainsString($response->decodeResponseJson()['errors'][0],'file does not exist');
    }

    public function test_status_tail_api_file_work()
    {
        $response = $this->get('/api/tail');

        $response->assertStatus(200);
    }

    public function test_tail_api_file_not_exists()
    {
        $response = $this->get('/api/tail?file_path=tesst.log');
        $this->assertStringContainsString($response->decodeResponseJson()['errors'][0],'file does not exist');
    }


    public function test_status_next_api_file_work()
    {
        $response = $this->get('/api/next');

        $response->assertStatus(200);
    }

    public function test_status_previous_api_file_work()
    {
        $response = $this->get('/api/previous');

        $response->assertStatus(200);
    }
}
