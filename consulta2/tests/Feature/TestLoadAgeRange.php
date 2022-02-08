<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestLoadAgeRange extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_load_age_range()
    {
        $response = $this->get('/statistics/loadAgeRange');

        $response->assertStatus(200);
    }
}
