<?php

namespace Tests\Unit;

use App\Book;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateBookUnauthorized()
    {
        $response = $this->postJson('/api/books', [
            'title'  => 'Tom Sawyer',
            'categories' => [
                ['name' => 'romane'],
                ['name' => 'folk'],
            ]
        ]);

        $response->assertStatus(401);
    }

    public function testCreateBook()
    {
        $user = factory(User::class)->create();

        auth()->guard('api')
            ->login(User::whereEmail($user->email)->first());

        $response = $this
            ->postJson('/api/books', [
            'title'  => 'Tom Sawyer',
            'categories' => [
                ['name' => 'romane'],
                ['name' => 'folk'],
            ]
        ]);

        $response->assertStatus(201);
    }

    public function testGetBook()
    {
        $user = factory(User::class)->create();
        factory(Book::class)->create()->each(function($q) {
            $q->categories()->create(['name' => 'TTT']);
        });
        auth()->guard('api')
            ->login(User::whereEmail($user->email)->first());

        $response = $this
            ->getJson('/api/books/1', []);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'title','description','categories'
        ]);
    }
}
