<?php

namespace App\Observers;

use App\Jobs\SendPostUpdatedJob;
use App\Post;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        // After updated user, update author & send mail
        foreach ($post->authors as $author) {
            $wordsCount = 0;
            foreach ($author->posts as $post) {
                $wordsCount += str_word_count($post->post_text);
            }
            $author->words_count = $wordsCount;
            $author->save();
        }
        // send mail
        dispatch(new SendPostUpdatedJob($post)); // async
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }
}
