<?php
declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestValidations
{
    protected function assertValidationStoreAction(
        array $data,
        string $rule,
        $ruleParams = []
    )
    {
        $response = $this->json('POST', $this->routeStore(), $data);
        $fields = array_keys($data);
        $this->assertValidationFields($response, $fields, $rule, $ruleParams);
    }
    protected function assertValidationUpdateAction(
        array $data,
        string $rule,
        $ruleParams = []
    )
    {
        $response = $this->json('PUT', $this->routeUpdate(), $data);
        $fields = array_keys($data);
        $this->assertValidationFields($response, $fields, $rule, $ruleParams);
    }
    protected function assertValidationFields(
        TestResponse $response,
        $fields,
        $rules,
        $rulesParams = []
    )
    {
        $response->assertStatus(422)
            ->assertJsonValidationErrors($fields);

        foreach ($fields as $field){
            $fieldName = str_replace('_', ' ', $field);

            $response->assertJsonFragment(
                [
                    \Lang::get("validation.{$rules}", ['attribute' => $fieldName] + $rulesParams)
                ]
            );
        }
    }
}
