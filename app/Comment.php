<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /*
     * Get all replies
     */
    public function replies()
    {
        return $this->hasMany('App\Comment', 'parent_id', 'id')->get();
    }

    /*
    * Get parent reply
    */
    public function parent()
    {
        return $this->hasOne('App\Comment', 'id', 'parent_id');
    }

    /*
    * Get level of comment
    */
    public function getParentsAttribute()
    {
        $parent = $this->parent;
        $level=1;
        while ($parent && $parent->parent_id!=0 && !is_null($parent)) {
            $parent = $parent->parent;
            $level++;
        }
        return $level;
    }


}
