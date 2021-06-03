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
        for ($i=0; $i < 9 ; $i++) { 
            $number = $i ;
            $response = $this->get('test-jenkin-plus/'.$number);
            $response->assertStatus(200);
            // dd($response);
            $response->assertSee($number +1);
        }
       
    }
}
