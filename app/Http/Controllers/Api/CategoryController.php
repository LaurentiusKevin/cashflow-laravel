<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function data(Request $request): JsonResponse
    {
        $id = $request->get('id') ?? null;

        if ($id == null) {
            $data = Category::query()
                ->select([
                    "categories.id",
                    "categories.parent_id",
                    "categories.type",
                    "categories.name",
                    DB::raw("concat_ws(' | ', parent.name) AS parent"),
                    "categories.level",
                    "categories.created_at",
                    "categories.updated_at",
                    "categories.deleted_at"
                ])
                ->leftJoin(DB::raw("categories parent"), 'categories.parent_id', '=', 'parent.id')
                ->orderBy(
                    DB::raw(
                        "concat_ws('.',CASE WHEN categories.parent_id IS NULL THEN categories.id ELSE categories.parent_id END, categories.parent_id)"
                    )
                )
                ->paginate(10);
        } else {
            $data = [
                'data' => Category::find($id),
            ];
        }

        return response()->json($data);
    }

    public function parent(Request $request): JsonResponse
    {
        $data = Category::query()
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function child(Request $request): JsonResponse
    {
        $parent_id = $request->get('parent_id');
        $data = Category::query()
            ->where('parent_id','=',$parent_id)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $id        = $request->get('id') ?? null;
        $parent_id = $request->get('parent_id');
        $type      = $request->get('type');
        $name      = $request->get('name');
        $level     = $request->get('level');

        if ($id == null) {
            $data = new Category();
        } else {
            $data = Category::find($id);
        }

        $data->parent_id = $parent_id;
        $data->type      = $type;
        $data->name      = $name;
        if ($id == null && $parent_id == null) {
            $data->level = 1;
        } else {
            $parent      = Category::find($parent_id);
            $data->level = $parent->level + 1;
        }
        $data->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required',
        ]);

        $id = $request->get('id');

        Category::find($id)->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
