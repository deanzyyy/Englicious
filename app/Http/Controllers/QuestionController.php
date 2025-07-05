<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function destroy(Question $question)
    {
        try {
            Log::info('Attempting to delete question: ' . $question->id);

            // Delete associated files if they exist
            if ($question->image_path) {
                if (Storage::disk('public')->exists($question->image_path)) {
                    Storage::disk('public')->delete($question->image_path);
                    Log::info('Associated image deleted: ' . $question->image_path);
                }
            }

            if ($question->audio_path) {
                if (Storage::disk('public')->exists($question->audio_path)) {
                    Storage::disk('public')->delete($question->audio_path);
                    Log::info('Associated audio deleted: ' . $question->audio_path);
                }
            }

            // Delete the question
            $deleted = $question->delete();
            Log::info('Question deletion result: ' . ($deleted ? 'success' : 'failed'));

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Question $question)
    {
        try {
            Log::info('Attempting to delete image for question: ' . $question->id);
            
            if ($question->image_path) {
                $imagePath = $question->image_path;
                Log::info('Image path to delete: ' . $imagePath);

                // Delete the file from storage
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    Log::info('Image file deleted successfully');
                } else {
                    Log::warning('Image file not found in storage: ' . $imagePath);
                }
                
                // Update the database
                $updated = $question->update(['image_path' => null]);
                Log::info('Database update result: ' . ($updated ? 'success' : 'failed'));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }
            
            Log::warning('No image path found for question: ' . $question->id);
            return response()->json([
                'success' => false,
                'message' => 'No image found'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAudio(Question $question)
    {
        try {
            Log::info('Attempting to delete audio for question: ' . $question->id);
            
            if ($question->audio_path) {
                $audioPath = $question->audio_path;
                Log::info('Audio path to delete: ' . $audioPath);

                // Delete the file from storage
                if (Storage::disk('public')->exists($audioPath)) {
                    Storage::disk('public')->delete($audioPath);
                    Log::info('Audio file deleted successfully');
                } else {
                    Log::warning('Audio file not found in storage: ' . $audioPath);
                }
                
                // Update the database
                $updated = $question->update(['audio_path' => null]);
                Log::info('Database update result: ' . ($updated ? 'success' : 'failed'));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Audio deleted successfully'
                ]);
            }
            
            Log::warning('No audio path found for question: ' . $question->id);
            return response()->json([
                'success' => false,
                'message' => 'No audio found'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting audio: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete audio: ' . $e->getMessage()
            ], 500);
        }
    }
} 