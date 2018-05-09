<?php

namespace Contabilizate;

use Illuminate\Database\Eloquent\Model;

class Regimen extends Model
{
    protected $table = "regimens";

    protected $fillable = [
        'code_rf', 'description'
    ];

    public function contributors(){
    	return $this->hasMany('Contabilizate\Contributor');
    }
}
