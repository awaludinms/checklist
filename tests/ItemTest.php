<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Models\Item;
use App\Models\Checklist;

class ItemTest extends TestCase
{
    use DatabaseTransactions;

    public function testNeedAutorization()
    {
        $this->get('/checklists/1/items');

        $this->assertEquals('Not Authorized.', $this->response->getContent());
        $this->assertEquals(401, $this->response->status());
    }

    public function testCreateChecklistItem()
    {
        $this->getToken();

        $body = '{
          "data": {
            "attribute": {
              "description": "Need to verify this guy house.",
              "due": "2019-01-19 18:34:51",
              "urgency": "2",
              "assignee_id": 123
            }
          }
        }';

        $header = ['Content-Type' => 'application/json' ];

        $checklist = $this->createChecklist();
        $this->json(
            'POST',
            '/checklists/' . $checklist->id . '/items/',
            json_decode($body, true),
            $header
        );

        $this->seeInDatabase('items', [
            'assignee_id' => 123,
            'description' => 'Need to verify this guy house.',
            'due' => '2019-01-19 18:34:51',
            'urgency' => 2
        ]);

        $this->assertEquals(200, $this->response->status());
    }

    public function testGetChecklistItem()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(1), 'items')
            ->create();

        $item = $checklist->items;

        $this->json(
            'GET',
            '/checklists/' . $checklist->id . '/items/' . $item[0]->id,
        )->seeJson([
            'type' => 'checklists',
            'id' => $item[0]->id
        ]);

        $this->assertEquals(200, $this->response->status());
    }

    public function testUpdateChecklistItem()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(1), 'items')
            ->create();

        $item = $checklist->items;

        $body = '
        {
            "data": {
                "attribute": {
                    "description": "Need to verify this guy house.",
                    "due": "2019-01-19 18:34:51",
                    "urgency": "3",
                    "assignee_id": 1234
                }
            }
        }';

        $header = ['Content-Type' => 'application/json' ];
        $this->json(
            'PATCH',
            '/checklists/' . $checklist->id . '/items/' . $item[0]->id,
            json_decode($body, true),
            $header
        )->seeJson([
            'type' => 'checklists',
            'id' => $item[0]->id,
        ]);

        $this->assertEquals(200, $this->response->status());
    }

    public function testGetAllItemFromChecklistId()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(10), 'items')
            ->create();

        $item = $checklist->items;

        $header = ['Content-Type' => 'application/json' ];
        $this->json(
            'GET',
            '/checklists/' . $checklist->id . '/items',
        )->seeJson([
            'type' => 'checklists',
            'id' => $checklist->id,
        ]);

        $this->assertEquals(200, $this->response->status());
    }

    public function testCompleteItem()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(10), 'items')
            ->create();

        $item = $checklist->items;

        $body = '
        {
            "data": [
            {
                "item_id": ' . $item[0]->id . '
            },
            {
                "item_id": ' . $item[5]->id . '
            },
            {
                "item_id": ' . $item[7]->id .'
            },
            {
                "item_id": ' . $item[9]->id . '
            }
          ]
        }
        ';
        $header = ['Content-Type' => 'application/json' ];
        $this->json(
            'POST',
            '/checklists/complete',
            json_decode($body, true),
            $header
        )   ;

        $this->assertEquals(200, $this->response->status());
    }

    public function testIncompleteItem()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(10), 'items')
            ->create();

        $item = $checklist->items;

        $body = '
        {
            "data": [
            {
                "item_id": ' . $item[0]->id . '
            },
            {
                "item_id": ' . $item[5]->id . '
            },
            {
                "item_id": ' . $item[7]->id .'
            },
            {
                "item_id": ' . $item[9]->id . '
            }
          ]
        }
        ';
        $header = ['Content-Type' => 'application/json' ];
        $this->json(
            'POST',
            '/checklists/incomplete',
            json_decode($body, true),
            $header
        )   ;

        $this->assertEquals(200, $this->response->status());
    }

    public function testUpdateBulkChecklist()
    {
        $this->getToken();

        $checklist = Checklist::Factory()->has(Item::Factory()->count(10), 'items')
            ->create();

        $item = $checklist->items;

        $body = '
        {
            "data": [
            {
                  "id": "' . $item[4]->id . '",
                  "action": "update",
                  "attributes": {
                    "description": "",
                    "due": "2019-01-19 18:34:51",
                    "urgency": "2"
                  }
            },
            {
                  "id": "205",
                  "action": "update",
                  "attributes": {
                    "description": "{{data.attributes.description}}",
                    "due": "2019-01-19 18:34:51",
                    "urgency": "2"
                  }
            }
        ]}';

        $header = ['Content-Type' => 'application/json' ];

        $this->json(
            'POST',
            '/checklists/' . $checklist->id . '/items/_bulk',
            json_decode($body, true),
            $header
        )   ;

        $this->assertEquals(200, $this->response->status());

    }
}
