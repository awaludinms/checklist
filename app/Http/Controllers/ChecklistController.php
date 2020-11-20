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
                'type' => 'checklist',
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
            ];
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
