<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrammarQuestion;
use App\Models\GrammarQuestionBlock;
use App\Models\GrammarWrongAnswer;
use Illuminate\Support\Facades\DB;

class AdminGrammarController extends Controller
{
    public function create()
    {
        return view('admin.grammar_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'questions' => 'required|array|size:5',

            'questions.*.note' => 'nullable|string|max:100',
            'questions.*.image' => 'required|image',
            'questions.*.correct_sentence' => 'required|string',

            'questions.*.blocks' => 'required|array',
            'questions.*.blocks.*.block_text' => 'required|string|max:100',
            'questions.*.blocks.*.part_of_speech' => 'required|integer|between:1,6',
            'questions.*.blocks.*.order_number' => 'required|integer|min:0',

            'questions.*.wrong_answers' => 'nullable|array',
            'questions.*.wrong_answers.*.wrong_order' => 'required|string|max:50',
            'questions.*.wrong_answers.*.wrong_sentence' => 'required|string',
            'questions.*.wrong_answers.*.wrong_image' => 'nullable|image',
        ]);

        DB::transaction(function () use ($request) {

            // One stage = 5 questions
            $latestStage = GrammarQuestion::where('game_id', 3)->max('stage_id');
            $stageId = $latestStage ? $latestStage + 1 : 1;

            foreach ($request->questions as $qIndex => $q) {

                /* =========================
               Save main image
            ========================= */
                $filename = time() . "_{$qIndex}_" . $q['image']->getClientOriginalName();

                $q['image']->storeAs(
                    'images/game_images/grammar',
                    $filename,
                    'public'
                );

                $imageUrl = 'images/game_images/grammar/' . $filename;

                // Create grammar question
                $question = GrammarQuestion::create([
                    'game_id' => 3,
                    'stage_id' => $stageId,
                    'note' => $q['note'] ?? null,
                    'image_url' => $imageUrl,
                    'correct_sentence' => $q['correct_sentence'],
                    'created_by_admin_id' => auth()->id(),
                ]);

                /* =========================
               Save blocks
            ========================= */
                foreach ($q['blocks'] as $block) {
                    GrammarQuestionBlock::create([
                        'question_id' => $question->id,
                        'block_text' => $block['block_text'],
                        'part_of_speech' => $block['part_of_speech'],
                        'order_number' => $block['order_number'],
                    ]);
                }

                /* =========================
               Save wrong answers (optional)
            ========================= */
                if (!empty($q['wrong_answers'])) {
                    foreach ($q['wrong_answers'] as $wIndex => $wrong) {

                        $wrongImagePath = null;

                        if (!empty($wrong['wrong_image'])) {
                            $wrongFilename = time() . "_wrong_{$qIndex}_{$wIndex}_" . $wrong['wrong_image']->getClientOriginalName();

                            $wrong['wrong_image']->storeAs(
                                'images/game_images/grammar/wrong_images',
                                $wrongFilename,
                                'public'
                            );

                            $wrongImagePath = 'images/game_images/grammar/wrong_images/' . $wrongFilename;
                        }

                        GrammarWrongAnswer::create([
                            'question_id' => $question->id,
                            'wrong_order' => $wrong['wrong_order'],
                            'wrong_sentence' => $wrong['wrong_sentence'],
                            'wrong_image_url' => $wrongImagePath,
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Stage (5 questions) has been successfully created.');
    }
}
