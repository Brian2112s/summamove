<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'nl');

        try {
            $exercises = Exercise::all()->map(function ($exercise) use ($lang) {
                $exercise->description = $lang === 'en' ? $exercise->vertaling_en : $exercise->description;

                $exercise->image = asset($exercise->image);

                return $exercise;
            });

            return response()->json($exercises);
        } catch (\Exception $e) {
            Log::error("Error fetching exercises: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch exercises'], 500);
        }
    }

    public function createExercise(Request $request)
    {

        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'vertaling_en' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            Log::info($validatedData);

            $existingExercise = Exercise::where('name', $validatedData['name'])->first();

            if ($existingExercise) {
                Log::info("Exercise already exists. Exercise ID: {$existingExercise->id}");

                return response()->json(['error' => 'Exercise already exists'], 409);
            }

            $exercise = Exercise::create($validatedData);

            Log::info("Exercise created successfully. Exercise ID: {$exercise->id}");

            return response()->json(['success' => 'Exercise created successfully', 'exercise' => $exercise], 201);
        } catch (\Exception $e) {
            Log::error("Exception occurred while creating exercise: " . $e->getMessage());

            return response()->json(['error' => 'Failed to create exercise'], 500);
        }
    }

    public function deleteExercise($id)
    {

        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        try {
            $exercise = Exercise::find($id);
    
            if (!$exercise) {
                Log::error("Exercise with ID {$id} not found.");
                return response()->json(['error' => 'Exercise not found'], 404);
            }
    
            $exercise->delete();
    
            Log::info("Exercise with ID {$id} deleted successfully.");
    
            return response()->json(['success' => 'Exercise deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error("Exception occurred while deleting exercise with ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete exercise'], 500);
        }
    }

    public function updateExercise(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            Log::warning('Unauthorized attempt to create exercise by user ID: ' . Auth::id());
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        try {
           $validatedData = $request->validate([
                'name' => 'string|max:255',
                'description' => 'string',
                'vertaling_en' => 'string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            $exercise = Exercise::find($id);
    
            if (!$exercise) {
                Log::error("Exercise with ID {$id} not found.");
                return response()->json(['error' => 'Exercise not found'], 404);
            }
    
            $exercise->update($validatedData);
    
            Log::info("Exercise with ID {$id} updated successfully.");
    
            return response()->json(['success' => 'Exercise updated successfully'], 200);
        }  catch (\Exception $e) {

            Log::error("Exception occurred while updating exercise with ID {$id}: " . $e->getMessage());
            
            return response()->json(['error' => 'Failed to update exercise'], 500);
        }
    }    
}
