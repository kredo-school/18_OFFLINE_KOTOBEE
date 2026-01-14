<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\VocabQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminVocabController extends Controller
{
    public function create(Group $group)
    {
        return view('admin.vocab_create', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $request->validate([
            'game_id' => 'required|integer|in:2,3',
            'questions' => 'required|array|size:5',
            'questions.*.word' => 'required|string|max:100',
            'questions.*.note' => 'nullable|string|max:100',
            'questions.*.part_of_speech' => 'required|integer|between:1,6',
            'questions.*.image' => 'required|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            $stageId = VocabQuestion::where('game_id', $request->game_id)
                ->max('stage_id');
            $stageId = $stageId ? $stageId + 1 : 1;

            foreach ($request->questions as $q) {

                $filename = Str::uuid() . '.' . $q['image']->getClientOriginalExtension();

                $path = $q['image']->storeAs(
                    'images/game_images/vocabulary',
                    $filename,
                    'public'
                );

                $imageUrl = 'storage/' . $path;

                VocabQuestion::create([
                    'game_id' => $request->game_id,
                    'stage_id' => $stageId,
                    'note' => $q['note'] ?? null,
                    'word' => $q['word'],
                    'image_url' => $imageUrl,
                    'part_of_speech' => $q['part_of_speech'],
                    'created_by_admin_id' => auth()->id(),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Stage questions saved!');
    }
}
