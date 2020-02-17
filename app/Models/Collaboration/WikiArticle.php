<?php

namespace App\Models\Collaboration;

use App\Support\Traits\HasTags;
use App\Util\Collaboration\ArticleFormat;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

use OwenIt\Auditing\Contracts\Auditable;

class WikiArticle extends Model implements Auditable
{
    use HasTags;
    use Sluggable;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'kb_articles';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        static::creating(function ($model) {
            $model->search = self::createSearchString($model);
        });

        static::updating(function ($model) {
            $model->search = self::createSearchString($model);
        });
        parent::boot();
    }

    private static function createSearchString($model)
    {
        return strip_tags(ArticleFormat::formatContent($model->content));
    }

    /**
     * Get the article's view counter.
     */
    public function views()
    {
        return $this->morphOne(ArticleView::class, 'viewable');
    }

    /**
     * Gets the number of views, formatted in k-notation
     */
    public function getViewCountAttribute()
    {
        $views = $this->views;
        return format_number_in_k_notation($views != null ? $views->value : 0);
    }

    public function setViewed() {
        if ($this->views == null) {
            $this->views()->create([
                'value' => 1,
            ]);
        } else {
            $this->views->value++;
            $this->views->save();
        }
    }

}