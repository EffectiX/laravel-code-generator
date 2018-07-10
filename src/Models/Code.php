<?php
namespace Effectix\CodeGen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Code extends Model
{
    protected $table;
    
    public $guarded = [];
    
    protected $casts = [
        'properties' => 'collection',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('laravel-code-generator.table_name');
        parent::__construct($attributes);
    }

    public function codeFor(): MorphTo
    {
        if (config('laravel-code-generator.generatedFor_returns_soft_deleted_models')) {
            return $this->morphTo()->withTrashed();
        }
        return $this->morphTo();
    }

    public function codeBy(): MorphTo
    {
        return $this->morphTo();
    }

    public function getExtraProperty(string $property_name)
    {
        return array_get($this->properties->toArray(), $property_name);
    }

    public function scopeWhereCodeNames(Builder $query, ...$code_names): Builder
    {
        if (is_array($code_names[0])) {
            $code_names = $code_names[0];
        }
        return $query->whereIn('code_name', $code_names);
    }

    public function scopeGeneratedBy(Builder $query, Model $code_by): Builder
    {
        return $query
            ->where('code_by_type', $code_by->getMorphClass())
            ->where('code_by_id', $code_by->getKey());
    }

    public function scopeGeneratedFor(Builder $query, Model $code_for): Builder
    {
        return $query
            ->where('code_for_type', $code_for->getMorphClass())
            ->where('code_for_id', $code_for->getKey());
    }
}
