<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Subtopic;

class TopicAndSubtopicSeeder extends Seeder
{
    public function run()
    {
        // General Topics
        $generalTopics = [
            'Grammar' => [
                'Tenses',
                'Parts of Speech',
                'Sentence Structure',
                'Articles',
                'Prepositions',
                'Modal Verbs',
                'Conditionals',
                'Active and Passive Voice'
            ],
            'Vocabulary' => [
                'Academic Words',
                'Phrasal Verbs',
                'Idioms',
                'Collocations',
                'Synonyms and Antonyms',
                'Word Formation'
            ],
            'Reading' => [
                'Main Ideas',
                'Supporting Details',
                'Inference',
                'Skimming and Scanning',
                'Text Organization'
            ],
            'Writing' => [
                'Essay Structure',
                'Academic Writing',
                'Creative Writing',
                'Business Writing',
                'Punctuation'
            ]
        ];

        // TOEFL Topics
        $toeflTopics = [
            'TOEFL Reading' => [
                'Main Ideas and Details',
                'Vocabulary in Context',
                'Reference Questions',
                'Inference Questions',
                'Purpose Questions'
            ],
            'TOEFL Listening' => [
                'Conversations',
                'Lectures',
                'Main Ideas',
                'Details Questions',
                'Function Questions'
            ],
            'TOEFL Speaking' => [
                'Independent Tasks',
                'Integrated Tasks',
                'Campus Situations',
                'Academic Topics'
            ],
            'TOEFL Writing' => [
                'Integrated Essay',
                'Independent Essay',
                'Essay Structure',
                'Academic Language'
            ]
        ];

        // IELTS Topics
        $ieltsTopics = [
            'IELTS Reading' => [
                'True/False/Not Given',
                'Matching Headings',
                'Multiple Choice',
                'Summary Completion',
                'Table Completion'
            ],
            'IELTS Listening' => [
                'Section 1 (Social Needs)',
                'Section 2 (Social Context)',
                'Section 3 (Education)',
                'Section 4 (Academic)'
            ],
            'IELTS Speaking' => [
                'Part 1 (Introduction)',
                'Part 2 (Long Turn)',
                'Part 3 (Discussion)',
                'Fluency and Coherence'
            ],
            'IELTS Writing' => [
                'Task 1 (Graph/Chart)',
                'Task 1 (Process/Map)',
                'Task 2 (Essay)',
                'Task Achievement'
            ]
        ];

        // Create topics and subtopics for each category
        $this->createTopicsAndSubtopics('General', $generalTopics);
        $this->createTopicsAndSubtopics('TOEFL', $toeflTopics);
        $this->createTopicsAndSubtopics('IELTS', $ieltsTopics);
    }

    private function createTopicsAndSubtopics($category, $topicsArray)
    {
        foreach ($topicsArray as $topicName => $subtopics) {
            $topic = Topic::create([
                'name' => $topicName,
                'description' => "Collection of {$topicName} exercises",
                'category' => $category
            ]);

            foreach ($subtopics as $subtopicName) {
                Subtopic::create([
                    'name' => $subtopicName,
                    'description' => "Exercises focusing on {$subtopicName}",
                    'topic_id' => $topic->id
                ]);
            }
        }
    }
} 