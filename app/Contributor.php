<?php

namespace Contabilizate;

use Illuminate\Database\Eloquent\Model;

class Contributor extends Model
{
	protected $table = "contributors";

    protected $fillable = [
        'name', 'rfc', 'regimen_id', 'pass_key', 'num_serie', 'start_date', 'end_date'
    ];


    public function regimen(){
    	return $this->belongsTo('Contabilizate\Regimen');
    }

    public function files(){
    	return $this->hasMany('Contabilizate\File');
    }
}
