<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];

    public function getRouteKeyName()
    {
      return 'identifier';
    }

     public function user()
     {
         return $this->belongsTo(User::class);
     }

   public function file()
   {
       return $this->belongsTo(File::class);
   }

}
