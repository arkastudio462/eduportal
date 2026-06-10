<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug) && $model->isFillable('slug')) {
                $model->slug = static::generateUniqueSlug($model->{$model->slugSource()});
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->slugSource()) && ! $model->isDirty('slug') && $model->isFillable('slug')) {
                $model->slug = static::generateUniqueSlug($model->{$model->slugSource()});
            }
        });
    }

    protected static function generateUniqueSlug(string $source): string
    {
        $slug = Str::slug($source);
        $base = $slug;
        $counter = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }

    protected function slugSource(): string
    {
        return 'judul';
    }
}
