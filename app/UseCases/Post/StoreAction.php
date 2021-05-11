<?php

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Community;
use PostLimitExceededException;

class StoreAction
{
    //todo
    public function __invoke(User $user, Community $community, Post $post): Post
    {
        // バグを防ぐために簡易的にアサーションを書く
        assert($user->exists);
        assert($community->exists);
        assert(!$post->exists);

        $userPostsCountToday = $user
            ->posts()
            ->where('community_id', $community->id)
            ->where('created_at', '>=', Carbon::midnight())
            ->count();
        if ($userPostsCountToday >= 200) {
            throw new PostLimitExceededException('本日の投稿可能な回数を超えました。');
        }

        $post->save();
        return $post;
    }
}
