<?php

namespace Contabilizate;

use Illuminate\Database\Eloquent\Model;

class FileCer extends Model
{
    protected $table = "files";

    protected $fillable = [
        'name', 'contributor_id'
    ];

    public function contributors(){
    	return $this->belongsTo('Contabilizate\Contributor');
    }

    
}
