<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Certificate extends Model
{
    protected $guarded =[];

    protected function valid(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->checkValidity(),
        );
    }

    protected function scadenza(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->verificaSeInScadenza(),
        );
    }

    protected function verificaSeInScadenza(): bool
    {
        if ($this->valid_to >= Carbon::today()->addDay(20))
            return true;
        else
            return false;
    }

    protected function checkValidity(): bool
    {
        if ($this->valid_to >= Carbon::today())
            return true;
        else
            return false;
    }

}