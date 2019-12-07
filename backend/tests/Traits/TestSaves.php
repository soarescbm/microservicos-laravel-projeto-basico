<?php


namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{
    public function assertStore($sendData, array  $testDatabase,  array $testJsonData = null) : TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStore(), $sendData);

        if($response->status() !== 201){
            throw new \Exception("Response status must be 201, given {$response->status()}: {$response->content()}");
        }
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;

    }

    public function assertUpdate($sendData, array  $testDatabase,  array $testJsonData = null) : TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);

        if($response->status() !== 200){
            throw new \Exception("Response status must be 201, given {$response->status()}: {$response->content()}");
        }
        $this->assertInDatabase($response, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;

    }


    private function assertInDatabase(TestResponse $response, array $testDatabase)
    {
        $model = $this->model();
        $table =  (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + [ 'id' =>  $this->getIdFromResponse($response)]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatabase, array $testJsonData = null)
    {
        $testReponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testReponse + [ 'id' => $this->getIdFromResponse($response)]);
    }

    private function getIdFromResponse(TestResponse $response)
    {
        return $response->json('id') ?? $response->json('data.id');
    }
}
