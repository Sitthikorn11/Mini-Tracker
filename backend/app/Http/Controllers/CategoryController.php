<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        if (empty($validated['color'])) {
            $validated['color'] = $validated['type'] === 'income' ? '#10B981' : '#EF4444';
        }

        $category = $request->user()->categories()->create($validated);

        return response()->json($category, 201);
    }

    public function show(Request $request, Category $category)
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:income,expense',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(Request $request, Category $category)
    {
        if ($category->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent deletion if transactions exist to avoid data loss (cascade delete)
        if ($category->transactions()->exists()) {
            return response()->json([
                'message' => 'ไม่สามารถลบได้ เนื่องจากมีการบันทึกรายรับ/รายจ่ายในหมวดหมู่นี้อยู่'
            ], 422);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
