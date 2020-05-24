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
    protected $fillable = ['slug', 'resume', 'title', 'content', 'image', 'thumbnail', 'status', 'author_id', 'category_id', 'featured', 'date', 'extras', 'published_at', 'expired_at'];
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
    public static function first()
    {
        return self::published()->orderBy('published_at', 'asc')
                     ->first();
    }
    public function previous()
    {
        return self::published()->where('published_at', '<', $this->published_at)
                     ->orderBy('published_at', 'desc')
                     ->first();
    }
    public function next()
    {
        return self::published()->where('published_at', '>', $this->published_at)
                     ->orderBy('published_at', 'asc')
                     ->first();
    }
    public static function latest()
    {
        return self::published()->orderBy('published_at', 'desc')
                     ->first();
    }

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

    public function sections()
    {
        return $this->morphToMany('Backpack\NewsCRUD\app\Models\Section', 'sectionable');
    }
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED')
            ->where('published_at', '<=', date('Y-m-d H:i:s'))
            ->where(function($query) {
                $query->where('expired_at', '>', date('Y-m-d H:i:s') )
                        ->orWhereNull('expired_at');
        });
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

    public function getImageAttribute($image)
    {
        return asset($image);
    }

    public function getThumbnailAttribute($thumbnail)
    {
        return asset($thumbnail);
    }

    public function getThumbOrImageAttribute()
    {
        if ( $this->getOriginal('thumbnail') != '' ) {
            return $this->thumbnail;
        }

        return $this->image;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
