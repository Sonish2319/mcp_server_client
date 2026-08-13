<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_articles_can_be_listed(): void
    {
        Article::factory()->count(3)->create();

        $response = $this->getJson('/api/articles');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_article_can_be_created(): void
    {
        $response = $this->postJson('/api/articles', [
            'title' => 'Test Article',
            'content' => 'Test content.',
            'author' => 'Test Author',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath(
                'data.title',
                'Test Article'
            );

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
        ]);
    }

    public function test_article_validation_works(): void
    {
        $response = $this->postJson('/api/articles', []);

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors([
            'title',
            'content',
            'author',
        ]);
    }

    public function test_article_can_be_viewed(): void
    {
        $article = Article::factory()->create();

        $response = $this->getJson(
            "/api/articles/{$article->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.id',
                $article->id
            );
    }

    public function test_article_can_be_updated(): void
    {
        $article = Article::factory()->create();

        $response = $this->putJson(
            "/api/articles/{$article->id}",
            [
                'title' => 'Updated Title',
            ]
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.title',
                'Updated Title'
            );

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_article_can_be_deleted(): void
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson(
            "/api/articles/{$article->id}"
        );

        $response->assertOk();

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    public function test_articles_can_be_searched(): void
    {
        Article::factory()->create([
            'title' => 'Kubernetes Fundamentals',
            'content' => 'Learn Kubernetes.',
            'author' => 'Admin',
        ]);

        Article::factory()->create([
            'title' => 'Laravel Fundamentals',
            'content' => 'Learn Laravel.',
            'author' => 'Admin',
        ]);

        $response = $this->getJson(
            '/api/articles/search?q=Kubernetes'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath(
                'data.0.title',
                'Kubernetes Fundamentals'
            );
    }
}