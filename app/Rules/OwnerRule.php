<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class OwnerRule implements Rule
{
    protected $namespace  = "App\\Models";

    protected $options;

    /**
     * Create a new rule instance.
     * OwnerRule constructor.
     * @param array $options
     *
     * * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options ?? [];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validateOwnerAttribute($attribute, $value, $this->options);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The entity belongs to another user.';
    }

    protected function validateOwnerAttribute($attribute, $value, array $options = [])
    {
        $attributeChunks = explode('_', $attribute);
        $column = $options['column'] ?? $attributeChunks[count($attributeChunks) - 1];
        $entity = $options['entity'] ?? Str::camel(str_replace("_{$column}", "", $attribute));
        $ownerColumn = $options['ownerColumn'] ?? 'user_id';

        $modelClass = Str::ucfirst($entity);
        if(substr($modelClass, 0, 1) != "\\")
            $modelClass = ($options['namespace'] ?? $this->namespace) . "\\" . $modelClass;
        $this->options = $options['options'] ?? [];

        $ref = $modelClass::where($column, $value)->first();
        if(isset($options['relation'])) {
            $relationChunks = explode('.', $options['relation']);
            foreach($relationChunks as $relationChunk) {
                $ref = optional($ref)->$relationChunk;
            }
        }
        return !isset($ref) || $ref->$ownerColumn === auth()->user()->id;
    }
}
