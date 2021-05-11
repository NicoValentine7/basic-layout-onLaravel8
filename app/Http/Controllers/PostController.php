<?php

namespace App\Http\Controllers;

use StoreAction;
use App\Models\Post;
use App\Models\Community;
use Illuminate\Http\Request;
use PostLimitExceededException;
use App\Http\Resources\PostResource;
use App\Http\Requests\Post\StoreRequest;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, StoreAction $action): PostResource
    {
        // $user->is_mail; // booleanで<true>
        // $user->attributes['is_mail']; // tinyint: 0 or 1
        // $comment->user->name;

        // 認可 + フォーマットバリデーション + 埋める処理
        // $post = new Post($request->validated());
        // $post->fill($request->validated());
        $user = $request->user();
        $community = $request->community();
        $post = $request->makePost();

        try {
            // ドメインバリデーションを呼び出す
            return new PostResource($action($user, $community, $post));
        } catch (PostLimitExceededException $e) {
            // 捕まえた例外はスタックトレースに積む
            throw new TooManyRequestsHttpException(null, $e->getMessage(), $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * 
     * /post/{post}/update -> /post/1/update
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
