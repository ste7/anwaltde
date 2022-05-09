<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Observers\TestModelObserver;
use Illuminate\Foundation\Testing\WithFaker;

class PostTest extends TestCase
{
    use WithFaker;

    public function test_get_posts_is_return_http_200_status_when_no_params_are_set()
    {
        $this->get('api/posts')
            ->assertStatus(200);
    }

    public function test_get_posts_is_return_http_200_status_when_params_are_set()
    {
        $this->get('api/posts?limit=10&page=1&title=Autem&body=Modi&userId=10')
            ->assertStatus(200);
    }

    public function test_get_post()
    {
        $id = 1;
        $this->get('api/posts/' . $id, [
            'post' => Post::find($id)
        ])
            ->assertStatus(200);
    }

    public function test_get_post_is_return_http_status_404_when_post_does_not_exists()
    {
        $id = Post::count() + 1;
        $this->get('api/posts/' . $id, [
            'post' => Post::find($id)
        ])
            ->assertStatus(404);
    }

    public function test_create_post()
    {
        $post = new Post();
        $post->observe(TestModelObserver::class);

        $factory = Post::factory()->create();

        $this->post('api/posts', $factory->toArray())
            ->assertStatus(200);
    }

    public function test_create_post_required_fields()
    {
        $this->json('POST', 'api/posts', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The user id field is required. (and 2 more errors)',
                'errors' => [
                    'userId' => ['The user id field is required.'],
                    'title' => ['The title field is required.'],
                    'body' => ['The body field is required.'],
                ]
            ]);
    }

    public function test_update_post()
    {
        $id = 1;
        $post = new Post();
        $post->observe(TestModelObserver::class);

        $factory = Post::factory()->create();

        $this->put('api/posts/' . $id, $factory->toArray())
            ->assertStatus(200);
    }

    public function test_update_post_required_fields()
    {
        $id = 1;
        $this->json('PUT', 'api/posts/' . $id, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The user id field is required. (and 2 more errors)',
                'errors' => [
                    'userId' => ['The user id field is required.'],
                    'title' => ['The title field is required.'],
                    'body' => ['The body field is required.'],
                ]
            ]);
    }
}
