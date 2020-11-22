<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //
    public function index($checklistId)
    {
        try {
            $checklist = Checklist::where('id', $checklistId)
                ->with('items')->get();

            $response = [
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklistId,
                    'attributes' => $checklist
                ],
                'link' => [
                    'self' => env('APP_URL') . '/checklists/' . $checklistId
                ]
            ];
            return response()->json($response,200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }

    public function store(Request $request, $checklistId)
    {
        try {
            $checklist = Checklist::find($checklistId);

            if ($checklist) {
                $data = $request->all()['data']['attribute'];
                $item = new Item;
                foreach($data as $key => $value) {
                    $item->{$key} = $data[$key];
                }
                $item->due = date('Y-m-d H:i:s', strtotime($data['due']));
                $item->checklist_id = $checklistId;
                $item->task_id = null;
                $item->save();
                return response()->json([
                    'data' => [
                        'type' => 'checklists',
                        'id' => $item->id,
                        'attributes' => [
                            'description' => $item->description,
                            'is_completed' => $item->is_completed,
                            'completed_at' => $item->completed_at,
                            'due' => $item->due,
                            'urgency' => $item->urgency,
                            'updated_by' => $item->updated_by,
                            'updated_at' => $item->updated_at,
                            'created_at' => $item->created_at,
                        ],
                        'links' => [
                            'self' => env('APP_URL') . '/checklists/' . $checklistId
                        ]
                    ]
                ], 200);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }

    public function show($checklistId, $itemId)
    {
        try {
            $item = Item::where([
                [ 'checklist_id', '=', $checklistId ],
                [ 'id', '=', $itemId ]
            ])->first();

            if ($item) {
                return response()->json([
                    'data' => [
                        'type' => 'checklists',
                        'id' => $item->id,
                        'attributes' => [
                            'description' => $item->description,
                            'is_completed' => $item->is_completed,
                            'completed_at' => $item->completed_at,
                            'due' => $item->due,
                            'urgency' => $item->urgency,
                            'created_by' => $item->created_by,
                            'updated_by' => $item->updated_by,
                            'updated_at' => $item->updated_at,
                            'created_at' => $item->created_at,
                        ]
                    ]
                ], 200);
            } else {
                return $this->notFound();         }

        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function update(Request $request, $checklistId, $itemId)
    {
        try {
            $item = Item::where([
                [ 'checklist_id', '=', $checklistId ],
                [ 'id', '=', $itemId ]
            ])->first();

            if ($item) {
                $data = $request->all()['data']['attribute'];
                foreach($data as $key => $value) {
                    $item->{$key} = $data[$key];
                }
                $item->due = date('Y-m-d H:i:s', strtotime($data['due']));
                $item->save();

                return response()->json([
                    'data' => [
                        'type' => 'checklists',
                        'id' => $item->id,
                        'attributes' => [
                            'description' => $item->description,
                            'is_completed' => $item->is_completed,
                            'due' => $item->due,
                            'urgency' => $item->urgency,
                            'assignee_id' => $item->assignee_id,
                            'completed_at' => $item->completed_at,
                            'updated_by' => $item->updated_by,
                            'updated_at' => $item->updated_at,
                            'created_at' => $item->created_at,
                        ]
                    ]
                ], 200);

            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }

    public function destroy($checklistId, $itemId)
    {
        try {
            $item = Item::where([
                [ 'checklist_id', '=', $checklistId ],
                [ 'id', '=', $itemId ]
            ])->first();

            if ($item) {
                $item->delete();
                return response()->json([], 200);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function complete(Request $request)
    {
        try {
            $data = $request->all()['data'];

            $items = [];
            foreach($data as $dt) {
                $item = Item::find($dt['item_id']);
                if ($item) {
                    $item->is_completed = true;
                    $item->save();
                    $items[] = [
                        'id' => $item->id,
                        'item_id' => $item->id,
                        'is_completed' => true,
                        'checklist_id' => $item->checklist_id
                    ];
                } else {
                    return $this->notFound();
                }
            }

            return response()->json([
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }

    public function incomplete(Request $request)
    {
        try {
            $data = $request->all()['data'];

            $items = [];
            foreach($data as $dt) {
                $item = Item::find($dt['item_id']);
                if ($item) {
                    $item->is_completed = false;
                    $item->save();
                    $items[] = [
                        'id' => $item->id,
                        'item_id' => $item->id,
                        'is_completed' => false,
                        'checklist_id' => $item->checklist_id
                    ];
                } else {
                    return $this->notFound();
                }
            }

            return response()->json([
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function summaries()
    {
    }

    public function all()
    {
    }

    public function bulk(Request $request, $checklistId)
    {
        try {
            $data = $request->all()['data'];
            $response = [];
            foreach($data as $dt) {
                $action = $dt['action'];
                $item = 0;
                switch ($action) {
                    case 'update':
                        $item = Item::where([
                            ['id', '=', $dt['id'] ],
                            ['checklist_id', '=', $checklistId]
                        ])->first();
                        break;
                }

                if ($item) {
                    $attributes = $dt['attributes'];
                    foreach($attributes as $key => $value) {
                        $item->{$key} = $value;
                    }

                    if ($item->save()) {
                        $status = 200;
                    } else {
                        $status = 403;
                    }

                } else {
                    $status = 404;
                }

                $response[] = [
                    'id' => $dt['id'],
                    'action' => $action,
                    'status' => $status
                ];
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }
}
