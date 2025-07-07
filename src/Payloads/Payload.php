<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Payloads;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Payload
{
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    abstract public function rules(): array;

    public function toArray(bool $onlyFilled = false): array
    {
        $vars = get_object_vars($this);

        if ($onlyFilled) {
            return array_filter($vars, fn ($value) => !is_null($value));
        }

        return $vars;
    }

    protected function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    private function validate(): void
    {
        $validator = Validator::make($this->toArray(), $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
