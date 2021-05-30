<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_jenkin()
    // {
    //     $response = $this->get('/test-jenkins');
    //     $response->assertStatus(200);
    // }
    public function test_plus()
    {
        $response = $this->get('test-jenkin-plus/5');
        $response->assertStatus(200);
        // dd($response);
        $response->assertSee(3);
    }
}
