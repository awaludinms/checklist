<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\Factories\Factory;


class ChecklistTest extends TestCase
{
    // use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNeedCredentials()
    {
        $this->post('/checklists/');

        $this->assertEquals('Not Authorized.', $this->response->getContent());
    }

    public function testCreateChecklist()
    {
        $token = $this->getToken();

        $this->json(
            'POST',
            '/checklists',
            json_decode('{"data": {"attributes": {"object_domain": "contact","object_id": "1","due": "2019-01-25T07:50:14+00:00","urgency": 1,"description": "Need to verify this guy house.","items": ["Visit his house","Capture a photo","Meet him on the house"],"task_id": "123"}}}', true),
            [ 'Authorized' => 'Bearer ' . $token ]
        )
        ->seeJson([
            'type' => 'checklists'
        ]);

        $this->assertEquals(201, $this->response->status());
    }

    private function createCheckList()
    {
        
    }

    public function testGetChecklist()
    {
        $token = $this->getToken();
        $this->createCheckList();

        $this->json(
            'GET',
            '/checklist/'
        );
    }
}
