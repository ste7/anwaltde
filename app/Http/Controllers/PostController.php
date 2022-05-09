<?php

namespace App\Http\Controllers;

 use Throwable;
use App\Models\Post;
use App\PostRepository;
 use Illuminate\Support\Arr;
use App\Http\Requests\PostRequest;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {
        try {
            $perPage = request('limit', 10);
            $from = (request('page', 1) - 1) * $perPage;

            $access = $this->postRepository->search([
                'userId' => request('userId'),
                'title' => request('title'),
                'body' => request('body')
            ], $perPage, $from);

            $ids = Arr::pluck($access['hits'], '_id');
            $posts = Post::findMany($ids)->sortBy(fn($post) => array_search($post->getKey(), $ids));

            $response = new LengthAwarePaginator(
                $posts,
                $access['total']['value'],
                $perPage,
                Paginator::resolveCurrentPage(),
                ['path' => Paginator::resolveCurrentPath()]);
            return response()->json($response, 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            return response()->json([
                'post' => $post
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function store(PostRequest $request)
    {
        try {
            return response()->json([
                'post' => Post::create($request->validated())
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Post $post, PostRequest $request)
    {
        try {
            return response()->json([
                'post' => tap($post)->update($request->validated())
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Internal server error',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}
