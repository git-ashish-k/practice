<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;


class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name', 'discription', 'brand_id', 'price'
    ];
    public function images()
    {
     return $this->hasMany('App\Image', 'product_id');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaCollection('product-image')
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg';
        });
    }
    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
