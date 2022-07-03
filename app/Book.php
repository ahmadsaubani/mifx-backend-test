<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'isbn',
        'title',
        'description',
        'published_year'
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    public function getReviewAverage() {
        return (int) round($this->reviews->avg('review'));
    }

    public function countReview() {
        return (int) $this->reviews->count();
    }

    public function savingBook(array $data)
    {
        $this->isbn = $data["isbn"];
        $this->title = $data["title"];
        $this->description = $data["description"];
        $this->published_year = $data["published_year"];
        $this->save();

        $this->authors()->attach($data["authors"]);

        return $this;
    }
    
}
