<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Template;
use App\Models\TemplateChecklist;
use App\Models\TemplateItem;


class TemplateController extends Controller
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

    public function store(Request $request)
    {
        try {
            $data = $request->all()['data'];

            $template = new Template;
            $template->name = $data['attributes']['name'];
            $template->checklist = json_encode($data['attributes']['checklist']);
            $template->items = json_encode($data['attributes']['items'], true);
            $template->save();

            return response()->json([
                'data' => [
                    'id' => $template->id,
                    'attributes' => $data['attributes']
                ]
            ],201);
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function show($templateId)
    {
        try {
            $template = Template::find($templateId);
            if ($template) {
                return $this->responseJson($template);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }

    public function update(Request $request, $templateId)
    {
        try {
            $data = $request->all()['data'];

            $template = Template::find($templateId);

            if ($template) {
                $template->name = $data['name'];
                $template->checklist = json_encode($data['checklist']);
                $template->items = json_encode($data['items'], true);
                $template->save();

                return response()->json([
                    'data' => [
                        'id' => $template->id,
                        'attributes' => $data
                    ]
                ],200);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
            return $this->serverError();
        }
    }

    private function responseJson($template)
    {
        return response()->json([
            'data' => [
                'type' => 'templates',
                'id' => $template->id,
                'attributes' => [
                    'name' => $template->name,
                    'items' => json_decode($template->items, true),
                    'checklist' => json_decode($template->checklist, true)
                ],
                'links' => [
                    'self' => env('APP_URL') . '/templates/' . $template->id
                ]
            ]
        ], 200);
    }

    public function destroy($templateId)
    {
        try {
            $template = Template::find($templateId);

            if ($template) {
                $template->delete();

                return response()->json([
                    'The 204 Response.'
                ], 204);
            } else {
                return $this->notFound();
            }
        } catch (\Exception $e) {
            return $this->serverError();
        }
    }
}
