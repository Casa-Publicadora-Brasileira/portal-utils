<?php

namespace CasaPublicadoraBrasileira\PortalUtils\Payloads;

use BadMethodCallException;
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
        $this->validate($attributes);
        $this->fill($attributes);
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
    public function __call(string $method, array $arguments): mixed
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
     * Preenche as propriedades do Payload.
     *
     * Os campos são filtrados de acordo com as rules().
     */
    protected function fill(array $data): void
    {
        $data = $this->filterByRules($data);

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Valida os dados conforme as rules().
     *
     *
     * @throws ValidationException
     */
    protected function validate(array $data): void
    {
        $validator = Validator::make(
            $data,
            $this->rules()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Filtra os dados utilizando as regras do Payload.
     */
    private function filterByRules(array $data): array
    {
        $rules = array_keys($this->rules());

        return $this->filterRecursive(
            $data,
            $rules
        );
    }

    /**
     * Filtra recursivamente os dados conforme as regras.
     *
     * Uma regra de container, como:
     *
     * avaliacao => array
     *
     * não autoriza automaticamente todos os campos internos.
     *
     * Os campos internos precisam existir explicitamente nas rules().
     */
    private function filterRecursive(
        array $data,
        array $rules
    ): array {
        $result = [];

        foreach ($data as $key => $value) {
            $key = (string) $key;

            $exactRule = $this->hasExactRule(
                $key,
                $rules
            );

            $nestedRules = $this->getNestedRules(
                $key,
                $rules
            );

            /*
             * Se existe uma regra específica para o campo
             * e não existem regras para seus filhos, o campo
             * é permitido diretamente.
             */
            if ($exactRule && empty($nestedRules)) {
                $result[$key] = $value;

                continue;
            }

            /*
             * Se existem regras para os campos internos,
             * precisamos continuar a filtragem.
             */
            if (!empty($nestedRules) && is_array($value)) {
                $result[$key] = $this->filterNestedValue(
                    $value,
                    $nestedRules
                );
            }
        }

        return $result;
    }

    /**
     * Filtra um valor que possui regras internas.
     *
     * Também trata regras com wildcard (*).
     */
    private function filterNestedValue(
        array $value,
        array $rules
    ): array {
        /*
         * Verifica se existem regras que começam com "*".
         *
         * Exemplo:
         *
         * avaliacao_disciplinas.*.padrao_disciplina_id
         */
        $wildcardRules = array_values(
            array_filter(
                $rules,
                fn ($rule) => $rule === '*'
                    || str_starts_with($rule, '*.')
            )
        );

        if (!empty($wildcardRules)) {
            return collect($value)
                ->map(
                    fn ($item) => is_array($item)
                        ? $this->filterRecursive(
                            $item,
                            array_map(
                                fn ($rule) => ltrim(
                                    $rule,
                                    '*.'
                                ),
                                $wildcardRules
                            )
                        )
                        : $item
                )
                ->toArray();
        }

        return $this->filterRecursive(
            $value,
            $rules
        );
    }

    /**
     * Verifica se existe uma regra exata.
     */
    private function hasExactRule(
        string $key,
        array $rules
    ): bool {
        return in_array($key, $rules, true)
            || in_array('*', $rules, true);
    }

    /**
     * Obtém as regras referentes aos filhos de um campo.
     *
     * Exemplo:
     *
     * Campo:
     * avaliacao
     *
     * Rules:
     * avaliacao.titulo
     * avaliacao.pais_id
     * avaliacao.avaliacao_disciplinas.*
     *
     * Resultado:
     *
     * titulo
     * pais_id
     * avaliacao_disciplinas.*
     */
    private function getNestedRules(
        string $key,
        array $rules
    ): array {
        $prefix = $key . '.';

        return collect($rules)
            ->filter(
                fn ($rule) => str_starts_with($rule, $prefix)
            )
            ->map(
                fn ($rule) => substr(
                    $rule,
                    strlen($prefix)
                )
            )
            ->values()
            ->all();
    }
}
