<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Achievement;
use Illuminate\Support\Facades\Log;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();
        return response()->json($achievements);
    }

    public function showAchievementsPerUser($id, Request $request)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        try {
            $sortField = $request->query('sort');
            $validSortFields = ['naam', 'datum'];

            if (!in_array($sortField, $validSortFields)) {
                $sortField = 'id';
            }

            $query = Achievement::where('user_id', $id)
                ->with('exercise');

            Log::info($sortField);
            if ($sortField === 'naam') {
                $query = $query->join('exercises', 'achievements.exercise_id', '=', 'exercises.id')
                            ->orderBy('exercises.name');
            } elseif ($sortField === 'datum') {
                $query = $query->orderBy('start_time', 'desc');
            }

            $achievements = $query->get();

            $achievements = $achievements->map(function ($achievement) {
                $encodedAchievement = $achievement->toArray();
                if (isset($encodedAchievement['exercise'])) {
                    $encodedExercise = [];
                    foreach ($encodedAchievement['exercise'] as $key => $value) {
                        $encodedExercise[$key] = is_string($value) ? utf8_encode($value) : $value;
                    }
                    $encodedAchievement['exercise'] = $encodedExercise;
                }
                return $encodedAchievement;
            });

            return response()->json($achievements);
        } catch (\Exception $e) {
            Log::error('Error retrieving achievements: ' . $e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function createAchievement(Request $request, $userId, $exerciseId)
    {
        try {
            $validatedData = $request->validate([
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i:s',
                'end_time' => 'required|date_format:H:i:s',
                'quantity' => 'required|integer',
            ]);

            $validatedData['user_id'] = $userId;
            $validatedData['exercise_id'] = $exerciseId;

            Log::info('Validated Data: ', $validatedData);

            $existingAchievement = Achievement::where('user_id', $userId)
                ->where('exercise_id', $exerciseId)
                ->first();

            if ($existingAchievement) {
                Log::info("Achievement already exists. Achievement ID: {$existingAchievement->id}");

                return response()->json(['error' => 'Achievement already exists'], 409);
            }

            $achievement = Achievement::create($validatedData);

            Log::info("Achievement created successfully. Achievement ID: {$achievement->id}");

            return response()->json(['success' => 'Achievement created successfully', 'achievement' => $achievement], 200);
        } catch (\Exception $e) {
            Log::error("Exception occurred while creating achievement: " . $e->getMessage());

            return response()->json(['error' => 'Failed to create achievement'], 500);
        }
    }

    public function updateAchievement(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'date' => 'sometimes|required|date',
                'start_time' => 'sometimes|required|date_format:H:i:s',
                'end_time' => 'sometimes|required|date_format:H:i:s',
                'quantity' => 'sometimes|required|integer',
            ]);

            $achievement = Achievement::find($id);

            if (!$achievement) {
                Log::info("Achievement not found. ID: {$id}");
                return response()->json(['error' => 'Achievement not found'], 404);
            }

            $achievement->update($validatedData);

            Log::info("Achievement updated successfully. Achievement ID: {$achievement->id}");

            return response()->json(['success' => 'Achievement updated successfully', 'achievement' => $achievement], 200);
        } catch (\Exception $e) {
            Log::error("Exception occurred while updating achievement: " . $e->getMessage());

            return response()->json(['error' => 'Failed to update achievement'], 500);
        }
    }

    public function deleteAchievement($id)
    {
        try {
            $achievement = Achievement::find($id);
    
            if (!$achievement) {
                Log::info("Achievement not found. ID: {$id}");
                return response()->json(['error' => 'Achievement not found'], 404);
            }
    
            $achievement->delete();
    
            Log::info("Achievement deleted successfully. Achievement ID: {$id}");
    
            return response()->json(['success' => 'Achievement deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Exception occurred while deleting achievement: " . $e->getMessage());
    
            return response()->json(['error' => 'Failed to delete achievement'], 500);
        }
    }
}
