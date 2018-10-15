<?php
namespace Effectix\CodeGen\Traits;

use Effectix\CodeGen\CodeGeneratorServiceProvider;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait GeneratesCodes
{
    public function codes(): MorphMany
    {
        return $this->morphMany(CodeGeneratorServiceProvider::determineActivityModel(), 'causer');
    }
}
