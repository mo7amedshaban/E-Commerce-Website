<?php

namespace App\Models;

use App\Observers\Category;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_categories';

    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at'
    ];
    ## for observer
    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {

        return $query->select('id', 'translation_lang', 'name', 'slug', 'photo', 'active', 'translation_of');
    }

    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }

    public function getActive(){
        if($this->active === "1")
            return 'مفعل';
        else
           return 'غير مفعل';
      // return $this->active === "1"?'مفعل':'غيرمفعل';
 }



    public function categories()
    {
        # id-> many    translation_of -> one
        return $this->hasMany(self::class, 'translation_of');
    }


    public function vendors(){

        return $this -> hasMany('App\Models\Vendor','category_id','id');
    }


}
