<?php

namespace Backpack\NewsCRUD\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use CrudTrait;
    use Sluggable, SluggableScopeHelpers;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'articles';
    protected $primaryKey = 'id';
    public $timestamps = true;
    // protected $guarded = ['id'];
    protected $fillable = ['slug', 'title', 'lead', 'content', 'image', 'thumbnail', 'status', 'category_id', 'featured', 'date', 'published_at', 'expired_at', 'top', 'recommended'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'featured'  => 'boolean',
        'date'      => 'date',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'slug_or_title',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo('Backpack\NewsCRUD\app\Models\Category', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany('Backpack\NewsCRUD\app\Models\Tag', 'article_tag');
    }

    public function author()
    {
        return $this->belongsto('App\User', 'author_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED')
                    // ->where('date', '<=', date('Y-m-d'))
                    ->where('published_at', '<=', date('Y-m-d H:i:s'))
                    ->where(function($query) {
                        $query->where('expired_at', '>', date('Y-m-d H:i:s') )
                                ->orWhereNull('expired_at');
                    });
                    // ->orderBy('published_at', 'DESC');
    }

    public function previous()
    {
        return self::where('status', 'PUBLISHED')
                     ->where('published_at', '<=', date('Y-m-d H:i:s'))
                     ->where(function($query) {
                        $query->where('expired_at', '>', date('Y-m-d H:i:s') )
                                ->orWhereNull('expired_at');
                     })
                     ->where('id', '<', $this->id)
                     ->orderBy('id', 'desc')
                     ->first();
    }
    public function next()
    {
        return self::where('status', 'PUBLISHED')
                     ->where('published_at', '<=', date('Y-m-d H:i:s'))
                     ->where(function($query) {
                        $query->where('expired_at', '>', date('Y-m-d H:i:s') )
                                ->orWhereNull('expired_at');
                     })
                     ->where('id', '>', $this->id)
                     ->orderBy('id', 'asc')
                     ->first();
    }
    public static function latest()
    {
        return self::where('status', 'PUBLISHED')
                     ->where('published_at', '<=', date('Y-m-d H:i:s'))
                     ->where(function($query) {
                        $query->where('expired_at', '>', date('Y-m-d H:i:s') )
                                ->orWhereNull('expired_at');
                     })
                     ->orderBy('published_at', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    // The slug is created automatically from the "title" field if no slug exists.
    public function getSlugOrTitleAttribute()
    {
        if ($this->slug != '') {
            return $this->slug;
        }

        return $this->title;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
