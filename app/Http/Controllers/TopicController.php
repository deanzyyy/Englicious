<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function storeTopic(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string',
                'subtopics' => 'required|array|min:1',
                'subtopics.*' => 'required|string|max:255'
            ]);

            // Create the topic
            $topic = Topic::create([
                'name' => $request->name,
                'category' => $request->category
            ]);

            // Create subtopics
            foreach ($request->subtopics as $subtopicName) {
                Subtopic::create([
                    'name' => $subtopicName,
                    'topic_id' => $topic->id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Topic and subtopics created successfully',
                'data' => $topic->load('subtopics')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating topic and subtopics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create topic and subtopics: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeSubtopic(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'topic_id' => 'required|exists:topics,id'
            ]);

            $subtopic = Subtopic::create([
                'name' => $request->name,
                'description' => $request->description,
                'topic_id' => $request->topic_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subtopic created successfully',
                'data' => $subtopic
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating subtopic: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subtopic: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTopicId($name)
    {
        try {
            $topic = Topic::where('name', $name)->first();
            
            if (!$topic) {
                return response()->json([
                    'success' => false,
                    'message' => 'Topic not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'id' => $topic->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting topic ID: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get topic ID: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTopicsByCategory(Request $request)
    {
        $category = $request->input('category');
        $topics = Topic::where('category', $category)->get();
        return response()->json($topics);
    }

    public function getSubtopicsByTopic(Request $request)
    {
        $topicId = $request->input('topic_id');
        $subtopics = Subtopic::where('topic_id', $topicId)->get();
        return response()->json($subtopics);
    }
} 