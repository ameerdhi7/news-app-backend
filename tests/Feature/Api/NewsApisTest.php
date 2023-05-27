<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class NewsApisTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testHomeApiRouteReturnsArticles(): void
    {
        $response = $this->get('/api/v1/news');

        $response->assertStatus(200);
        // Assert that the response structure matches the expected structure
        $response->assertJsonStructure([
            'data' => [
                'articles' => [
                    '*' => [
                    ]
                ]
            ]
        ]);
    }

    public function testPreferencesOptionsReturnsResults(): void
    {
        $response = $this->get('/api/v1/news/preferences/options');

        $response->assertStatus(200);
        // Assert that the response structure matches the expected structure
        $response->assertJsonStructure([
            'data' => [
                'preferences' => [
                    'category' => [
                    ],
                    'source' => [
                    ],
                    'author' => [
                    ]
                ]
            ]
        ]);
    }

}
