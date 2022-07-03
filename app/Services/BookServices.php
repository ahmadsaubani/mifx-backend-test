<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Exception;
use Illuminate\Support\Facades\DB;

class BookServices
{

    public function query(Builder $builder, $filter)
    {
        $qb = $builder;

        $sortDirection = "DESC";
        if (array_key_exists("sortDirection", $filter) && !empty(@$filter["sortDirection"])) {
            $sortDirection = strtoupper($filter["sortDirection"]);
        }

        if (array_key_exists("sortColumn", $filter) && !empty(@$filter["sortColumn"])) {
            $sortColumnArray = ["title", "avg_review", "published_year"];
            if (! in_array(strtolower($filter["sortColumn"]), $sortColumnArray)) {
                throw new Exception("sortColumn must be type of title, avg_review or published_year");
            }

            if ($filter["sortColumn"] == "avg_review") {
                $qb = $qb->withCount(['reviews as average_review' => function($query) {
                    $query->select(DB::raw('coalesce(avg(review),0)'));
                }])->orderBy('average_review', $sortDirection);
            } else {
                $qb = $qb->orderBy($filter["sortColumn"], $sortDirection);
            }
            
        }

        if (array_key_exists("title", $filter) && !empty(@$filter["title"])) {
            $qb = $qb->where("title", "LIKE", "%" . $filter["title"] . "%");
        }

        if (array_key_exists("authors", $filter) && !empty(@$filter["authors"])) {
            $explodeIds = explode(",", $filter["authors"]);
            if (count($explodeIds) >= 1) {
                $qb = $qb->whereHas('authors', function ($q) use ($explodeIds) {
                    $q->whereIn("id", $explodeIds);
                });
            }
        }
        
        return $qb;
    }
    
}
