<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checklist;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
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

    public function index()
    {
        try {
            $checklists = Checklist::paginate(10);
            return response()->json($checklists, 200);
        } catch (\Exception $e) {
            $this->serverError();
        }
    }

    public function show($checklistId)
    {
        try {
            $checklist = Checklist::find($checklistId);

            if ($checklist) {
                return response()->json([
                    'data' => [
                        'types' => 'checklists',
                        'id' => $checklist->id,
                        'attributes' => [
                            'object_domain' => $checklist->object_domain,
                            'object_id' => $checklist->object_id,
                            'description' => $checklist->description,
                            'is_completed' => $checklist->is_completed,
                            'due' => $checklist->due,
                            'urgency' => $checklist->urgency,
                            'completed_at' => $checklist->completed_at,
                            'last_updated_by' => $checklist->updated_by,
                            'update_at' => $checklist->updated_at,
                            'created_at' => $checklist->created_at,
                        ],
                        'links' => [
                            'self' => env('APP_URL') . '/checklist/' . $checklist->id
                         ]
                    ]
                ], 200);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all()['data']['attributes'];
        // print_r($data);

        try {
            $checklist = new Checklist;

            $columns = ['object_id', 'object_domain', 'description', 'urgency'];

            foreach ($columns as $col) {
                $checklist->{$col} = $data[$col];
            }

            $checklist->created_by = Auth::user()->id;

            $checklist->save();

            $response = [
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => [
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'task_id' => $checklist->task_id,
                        'description' => $checklist->description,
                        'is_completed' => $checklist->is_completed,
                        'due' => $checklist->due,
                        'urgency' => $checklist->urgency,
                        'completed_at' => $checklist->completed_at,
                        'updated_by' => $checklist->updated_by,
                        'created_by' => $checklist->created_by,
                        'created_at' => $checklist->created_at,
                        'updated_at' => $checklist->updated_at,
                    ],
                    'links' => [
                        'self' => env('APP_URL', 'http://localhost') . '/checklist/' . $checklist->id
                    ]
                ]
            ];
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function update(Request $request, $checklistId)
    {
        $data = $request->all();

        // print_r($data);

        try {
            $checklist = Checklist::find($checklistId);

            if (! $checklist) {
                return $this->notFound();
            }

            $columns = [
                'object_domain',
                'object_id',
                'description',
                'is_completed',
                'completed_at'
            ];
            foreach ($columns as $col) {
                $checklist->{$col} = $data['data']['attributes'][$col];
            }

            $checklist->updated_by = Auth::user()->id;
            $checklist->updated_at = date('Y-m-d H:i:s');
            $checklist->save();

            return response()->json([
                'data' => [
                    'type' => 'checklists',
                    'id' => $checklist->id,
                    'attributes' => [
                        'object_domain' => $checklist->object_domain,
                        'object_id' => $checklist->object_id,
                        'description' => $checklist->description,
                        'is_completed' => $checklist->is_completed,
                        'due' => $checklist->due,
                        'urgency' => $checklist->urgency,
                        'completed_at' => $checklist->completed_at,
                        'last_updated_by' => $checklist->updated_by,
                        'update_at' => $checklist->updated_at,
                        'created_at' => $checklist->created_at,
                    ],
                    'links' => [
                        'self' => env('APP_URL', 'http://localhost') . '/checklist/' . $checklist->id
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function destroy($checklistId)
    {
        try {
            $checklist = Checklist::find($checklistId);

            if ($checklist) {
                $checklist->delete();

                return response()->json([
                    'The 204 Response.'
                ], 204);
            } else {
                $this->notFound();
            }
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }
}
