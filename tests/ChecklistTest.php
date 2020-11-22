<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{
    // use DatabaseMigrations;
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNeedAutorization()
    {
        $this->get('/checklists/');

        $this->assertEquals('Not Authorized.', $this->response->getContent());
        $this->assertEquals(401, $this->response->status());
    }

    public function testCreateChecklist()
    {
        $this->getToken();

        $this->json(
            'POST',
            '/checklists',
            json_decode('{"data": {"attributes": {"object_domain": "contact","object_id": "1","due": "2019-01-25T07:50:14+00:00","urgency": 1,"description": "Need to verify this guy house.","items": ["Visit his house","Capture a photo","Meet him on the house"],"task_id": "123"}}}', true),
            ['Content-Type' => 'application/json']
        )
        ->seeJson([
            'type' => 'checklists'
        ]);

        $this->seeInDatabase('checklists', [
            "object_domain" => "contact",
            "object_id" => "1",
            "urgency" => 1,
            "description" => "Need to verify this guy house.",
        ]);
        $this->assertEquals(201, $this->response->status());
    }

    public function testGetChecklist()
    {
        $checklist = $this->createChecklist();
        $this->getToken();

        $this->json(
            'GET',
            '/checklists/' . $checklist->id,
        )->seeJson([
            'id' => $checklist->id,
            'type' => 'checklists'
        ]);

        $this->assertEquals(200, $this->response->status());
    }

    public function testUpdateChecklist()
    {
        $checklist = $this->createChecklist();
        $this->getToken();

        $payload = '{
            "data": {
                "type": "checklists",
                "id": ' . $checklist->id . ',
                "attributes": {
                    "object_domain": "contact {edited}",
                    "object_id": "1",
                    "description": "Need to verify this guy house {edited}.",
                    "is_completed": true,
                    "completed_at": null,
                    "created_at": "2018-01-25T07:50:14+00:00"
                },
                "links": {
                    "self": "https://dev-kong.command-api.kw.com/checklists/50127"
                }
            }
        }';

        $this->json(
            'PATCH',
            '/checklists/' . $checklist->id,
            json_decode($payload, true),
            ['Content-Type' => 'application/json']
        )->seeJson([
            'id' => $checklist->id,
            'type' => 'checklists',
            'object_domain' => 'contact {edited}'
        ]);

        $this->seeInDatabase('checklists', [
            'object_domain' => 'contact {edited}',
            'object_id' => '1',
            'description' => 'Need to verify this guy house {edited}.',
            'is_completed' => true,
            'completed_at' => null,
        ]);
        $this->assertEquals(200, $this->response->status());
    }

    public function testDeleteChecklist()
    {
        $checklist = $this->createChecklist();
        $this->getToken();

        $this->json(
            'DELETE',
            '/checklists/' . $checklist->id,
        );

        $this->assertEquals(204, $this->response->status());

        // test redelete
        $this->json(
            'DELETE',
            '/checklists/' . $checklist->id,
        );

        $this->assertEquals(404, $this->response->status());
    }

    public function testGetAllChecklists()
    {

    }
}
