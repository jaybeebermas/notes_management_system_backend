<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

class NoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('notes')->orderByDesc('updated_at');

        if ($request->filled('search')) {
            $search = (string) $request->query('search');
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('category')) {
            $query->where('category', (string) $request->query('category'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $id = DB::table('notes')->insertGetId([
                ...$validated,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $note = DB::table('notes')->where('id', $id)->first();
            DB::commit();

            return response()->json($note, 201);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to create note'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $note = DB::table('notes')->where('id', $id)->first();

        if (! $note) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json($note);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
        ]);

        DB::beginTransaction();

        try {
            $updated = DB::table('notes')
                ->where('id', $id)
                ->update([
                    ...$validated,
                    'updated_at' => now(),
                ]);

            if ($updated === 0 && ! DB::table('notes')->where('id', $id)->exists()) {
                DB::rollBack();

                return response()->json(['message' => 'Not Found'], 404);
            }

            $updatedNote = DB::table('notes')->where('id', $id)->first();
            DB::commit();

            return response()->json($updatedNote);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to update note'], 500);
        }
    }

    public function destroy(int $id): Response|JsonResponse
    {
        DB::beginTransaction();

        try {
            $deleted = DB::table('notes')->where('id', $id)->delete();

            if ($deleted === 0) {
                DB::rollBack();

                return response()->json(['message' => 'Not Found'], 404);
            }

            DB::commit();

            return response()->noContent();
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => 'Failed to delete note'], 500);
        }
    }

    public function categories(): JsonResponse
    {
        $categories = DB::table('notes')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return response()->json($categories->values());
    }
}
