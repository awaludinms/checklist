<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TemplateTest extends TestCase
{
    use DatabaseTransactions;

    public function testNeedAutorization()
    {
        $this->get('/checklists/templates');

        $this->assertEquals('Not Authorized.', $this->response->getContent());
        $this->assertEquals(401, $this->response->status());
    }

    public function testCreateChecklistTemplate()
    {
        $this->getToken();

        $body_json = '
        {
            "data": {
                "attributes": {
                  "name": "foo template",
                  "checklist": {
                    "description": "my checklist",
                    "due_interval": 3,
                    "due_unit": "hour"
                  },
                  "items": [
                    {
                      "description": "my foo item",
                      "urgency": 2,
                      "due_interval": 40,
                      "due_unit": "minute"
                    },
                    {
                      "description": "my bar item",
                      "urgency": 3,
                      "due_interval": 30,
                      "due_unit": "minute"
                    }
                  ]
                }
            }
        }
        ';

        $body = json_decode($body_json, true);

        $header = [
            'Content-Type' => 'application/json'
        ];

        $this->json(
            'POST',
            '/checklists/templates',
            $body,
            $header,
        )->seeJson([
            'name' => "foo template"
        ]);

        $this->seeInDatabase('templates', [
            'name' => 'foo template',
            'checklist' => json_encode($body['data']['attributes']['checklist']),
            'items' => json_encode($body['data']['attributes']['items'])
        ]);
        $this->assertEquals(201, $this->response->status());
    }

    public function createChecklistTemplate()
    {
        return App\Models\Template::Factory()->create();
    }

    public function testGetChecklistTemplate()
    {
        $this->getToken();

        $template = $this->createChecklistTemplate();
        $this->json(
            'GET',
            '/checklists/templates/' . $template->id,
        )->seeJson(
            ['type' => 'templates']
        );

        $this->assertEquals(200, $this->response->status());
    }

    public function testUpdateChecklistTemplate()
    {
        $this->getToken();

        $template = $this->createChecklistTemplate();

        $body_json = '
        {
          "data": {
            "name": "foo template",
            "checklist": {
              "description": "my checklist",
              "due_interval": 3,
              "due_unit": "hour"
            },
            "items": [
              {
                "description": "my foo item",
                "urgency": 2,
                "due_interval": 40,
                "due_unit": "minute"
              },
              {
                "description": "my bar item",
                "urgency": 3,
                "due_interval": 30,
                "due_unit": "minute"
              }
            ]
          }
        }';

        $body = json_decode($body_json, true);

        $header = [
            'Content-Type' => 'application/json'
        ];

        $this->json(
            'PATCH',
            '/checklists/templates/' . $template->id,
            $body,
            $header
        )
        ->seeJson([
            'data' => [
                'id' => $template->id,
                'attributes' => $body['data']
            ]
        ]);

        $this->assertEquals(200, $this->response->status());
    }
}
