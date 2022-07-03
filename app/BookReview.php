<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function savingBookReview(User $user , Book $book , array $data)
    {
        $this->review     = $data["review"];
        $this->comment    = $data["comment"];
        $this->user_id    = $user->id;
        $this->book_id    = $book->id;
        $this->save();

        return $this;
    }
}
