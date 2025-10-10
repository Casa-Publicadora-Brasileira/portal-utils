<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Payloads;

use http\Exception\BadMethodCallException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class Payload
{
    /**
     * @description Construtor da classe. Preenche e valida os atributos.
     *
     * @param  array  $attributes  Atributos para preencher o payload.
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
        $this->validate();
    }

    /**
     * @description Define as regras de validação para o payload.
     *
     * @param void
     * @return array Regras de validação do Laravel.
     */
    abstract public function rules(): array;

    /**
     * @description Converte o objeto do payload em um array.
     *
     * @param  bool  $onlyFilled  Se true, retorna apenas as propriedades preenchidas (não nulas).
     * @return array Propriedades do payload como um array associativo.
     */
    public function toArray(bool $onlyFilled = false): array
    {
        $vars = get_object_vars($this);

        if ($onlyFilled) {
            return array_filter($vars, fn ($value) => !is_null($value));
        }

        return $vars;
    }

    /**
     * @description Lida com chamadas de método dinâmicas para getters e setters.
     *
     * @param  string  $method  O nome do método sendo chamado (ex: 'getNome').
     * @param  array  $arguments  Os argumentos passados para o método.
     * @return mixed O valor da propriedade para um 'get' ou a instância do payload para um 'set'.
     */
    public function __call(string $method, array $arguments)
    {
        $action = substr($method, 0, 3);
        $camelCaseProperty = substr($method, 3);

        $property = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $camelCaseProperty));

        if (!property_exists($this, $property)) {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()', static::class, $method
            ));
        }

        if ($action === 'get') {
            return $this->{$property};
        }

        if ($action === 'set') {
            $this->{$property} = $arguments[0];

            return $this;
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    /**
     * @description Preenche as propriedades do payload a partir de um array.
     *
     * @param  array  $data  Dados para preencher as propriedades.
     */
    protected function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @description Valida os dados do payload usando as regras definidas.
     *
     * @param void
     *
     * @throws Illuminate\Validation\ValidationException Se a validação falhar.
     */
    protected function validate(): void
    {
        $validator = Validator::make($this->toArray(), $this->rules());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
