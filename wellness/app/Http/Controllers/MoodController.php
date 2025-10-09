<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mood;
use Carbon\Carbon;

class MoodController extends Controller
{
    // 🟢 List moods with optional filters
    public function index(Request $request)
    {
        $query = Mood::query();

        if ($request->user_name) {
            $query->where('user_name', $request->user_name);
        }

        if ($request->filter === '7days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($request->filter === '30days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        if ($request->mood) {
            $query->where('mood', $request->mood);
        }

        return response()->json($query->latest()->get());
    }

    // 🟩 Add mood + Real-Life Integration Tool advice
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:50',
            'mood' => 'required|in:happy,motivated,relaxed,calm,tired,okay,sad,angry,stressed',
            'note' => 'nullable|string|max:255',
        ]);

        $mood = Mood::create($validated);

        $advice = [
            'happy' => ["Share your joy with a friend", "Celebrate a small success", "Do a random act of kindness"],
            'motivated' => ["Start the next task", "Plan a small reward", "Focus on a creative task"],
            'relaxed' => ["Meditate for 5 min", "Take a slow walk", "Listen to calming music"],
            'calm' => ["Do gentle stretches", "Read a chapter", "Write thoughts for clarity"],
            'tired' => ["Take a 15-min power nap", "Hydrate and stretch", "Listen to energizing music"],
            'okay' => ["Plan next task", "5-min breathing exercise", "Organize workspace"],
            'sad' => ["Write down feelings", "Talk to a friend", "Take a short walk"],
            'angry' => ["10 deep breaths", "Short break and stretch", "Listen to calming music"],
            'stressed' => ["10-sec breathing break", "Stand, stretch, and listen to 3-min audio", "Write a reset checklist"],
        ];

        $randomAdvice = $advice[$validated['mood']] ?? [];

        return response()->json([
            'message' => 'Mood saved successfully!',
            'data' => $mood,
            'actionable_advice' => $randomAdvice
        ]);
    }
// ✏️ Update mood
public function update(Request $request, $id)
{
    $mood = Mood::find($id);

    if (!$mood) {
        return response()->json(['message' => 'Mood not found.'], 404);
    }

    $validated = $request->validate([
        'mood' => 'sometimes|in:happy,motivated,relaxed,calm,tired,okay,sad,angry,stressed',
        'note' => 'nullable|string|max:100',
    ]);

    $mood->update($validated);

    return response()->json(['message' => 'Mood updated successfully!', 'data' => $mood]);
}

// 🗑️ Delete mood
public function destroy($id)
{
    $mood = Mood::find($id);

    if (!$mood) {
        return response()->json(['message' => 'Mood not found.'], 404);
    }

    $mood->delete();

    return response()->json(['message' => 'Mood deleted successfully!']);
}
    // 📊 Summary: most frequent mood + total counts
    public function summary()
    {
        $summary = Mood::selectRaw('mood, COUNT(*) as count')
                        ->groupBy('mood')
                        ->orderByDesc('count')
                        ->get();

        $mostFrequent = $summary->first();

        return response()->json([
            'summary' => $summary,
            'most_frequent' => $mostFrequent?->mood,
        ]);
    }
}
