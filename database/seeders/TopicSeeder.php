<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Subtopic;

class TopicSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'General' => [
                [
                    'name' => 'Grammar',
                    'description' => 'Learn English grammar rules and structures',
                    'subtopics' => [
                        ['name' => 'Tenses', 'description' => 'Past, Present, and Future tenses'],
                        ['name' => 'Parts of Speech', 'description' => 'Nouns, Verbs, Adjectives, etc.'],
                        ['name' => 'Sentence Structure', 'description' => 'Simple, Compound, and Complex sentences'],
                        ['name' => 'Modal Verbs', 'description' => 'Can, Could, Should, Would, etc.'],
                        ['name' => 'Conditionals', 'description' => 'Zero, First, Second, and Third conditionals'],
                    ]
                ],
                [
                    'name' => 'Vocabulary',
                    'description' => 'Build your English vocabulary',
                    'subtopics' => [
                        ['name' => 'Common Words', 'description' => 'Frequently used English words'],
                        ['name' => 'Synonyms & Antonyms', 'description' => 'Words with similar and opposite meanings'],
                        ['name' => 'Idioms & Phrases', 'description' => 'Common English expressions'],
                        ['name' => 'Academic Words', 'description' => 'Vocabulary for academic purposes'],
                        ['name' => 'Business English', 'description' => 'Professional and business terminology'],
                    ]
                ],
                [
                    'name' => 'Reading',
                    'description' => 'Improve reading comprehension skills',
                    'subtopics' => [
                        ['name' => 'Main Ideas', 'description' => 'Identifying central themes and main points'],
                        ['name' => 'Details', 'description' => 'Finding specific information'],
                        ['name' => 'Inference', 'description' => 'Drawing conclusions from text'],
                        ['name' => 'Author\'s Purpose', 'description' => 'Understanding writer\'s intentions'],
                        ['name' => 'Text Structure', 'description' => 'Analyzing organization of text'],
                    ]
                ],
                [
                    'name' => 'Writing',
                    'description' => 'Develop writing skills',
                    'subtopics' => [
                        ['name' => 'Essay Writing', 'description' => 'Structure and organization of essays'],
                        ['name' => 'Academic Writing', 'description' => 'Formal academic papers'],
                        ['name' => 'Creative Writing', 'description' => 'Stories and creative pieces'],
                        ['name' => 'Business Writing', 'description' => 'Professional correspondence'],
                        ['name' => 'Report Writing', 'description' => 'Technical and analytical reports'],
                    ]
                ],
                [
                    'name' => 'Listening',
                    'description' => 'Enhance listening comprehension',
                    'subtopics' => [
                        ['name' => 'General Comprehension', 'description' => 'Understanding main ideas'],
                        ['name' => 'Specific Details', 'description' => 'Catching specific information'],
                        ['name' => 'Note Taking', 'description' => 'Taking effective notes'],
                        ['name' => 'Academic Lectures', 'description' => 'Understanding academic content'],
                        ['name' => 'Daily Conversations', 'description' => 'Natural English dialogues'],
                    ]
                ],
                [
                    'name' => 'Speaking',
                    'description' => 'Practice speaking skills',
                    'subtopics' => [
                        ['name' => 'Pronunciation', 'description' => 'Correct sound production'],
                        ['name' => 'Fluency', 'description' => 'Speaking smoothly and naturally'],
                        ['name' => 'Presentation Skills', 'description' => 'Public speaking techniques'],
                        ['name' => 'Conversation Skills', 'description' => 'Natural dialogue practice'],
                        ['name' => 'Debate & Discussion', 'description' => 'Argumentative speaking'],
                    ]
                ]
            ],
            'IELTS' => [
                [
                    'name' => 'IELTS Reading',
                    'description' => 'IELTS Reading test preparation',
                    'subtopics' => [
                        ['name' => 'Skimming & Scanning', 'description' => 'Quick reading techniques'],
                        ['name' => 'True/False/Not Given', 'description' => 'Statement analysis'],
                        ['name' => 'Matching Headings', 'description' => 'Paragraph and heading matching'],
                        ['name' => 'Multiple Choice', 'description' => 'Multiple choice questions'],
                        ['name' => 'Gap Filling', 'description' => 'Completing information'],
                    ]
                ],
                [
                    'name' => 'IELTS Writing',
                    'description' => 'IELTS Writing test preparation',
                    'subtopics' => [
                        ['name' => 'Task 1 Academic', 'description' => 'Graph and chart description'],
                        ['name' => 'Task 1 General', 'description' => 'Letter writing'],
                        ['name' => 'Task 2 Essay', 'description' => 'Essay writing skills'],
                        ['name' => 'Grammar for IELTS', 'description' => 'Essential grammar patterns'],
                        ['name' => 'Vocabulary for IELTS', 'description' => 'Key IELTS vocabulary'],
                    ]
                ],
                [
                    'name' => 'IELTS Listening',
                    'description' => 'IELTS Listening test preparation',
                    'subtopics' => [
                        ['name' => 'Section 1', 'description' => 'Social needs listening'],
                        ['name' => 'Section 2', 'description' => 'Social needs monologue'],
                        ['name' => 'Section 3', 'description' => 'Educational context'],
                        ['name' => 'Section 4', 'description' => 'Academic lecture'],
                        ['name' => 'Note Completion', 'description' => 'Form filling practice'],
                    ]
                ],
                [
                    'name' => 'IELTS Speaking',
                    'description' => 'IELTS Speaking test preparation',
                    'subtopics' => [
                        ['name' => 'Part 1', 'description' => 'Introduction and interview'],
                        ['name' => 'Part 2', 'description' => 'Individual long turn'],
                        ['name' => 'Part 3', 'description' => 'Two-way discussion'],
                        ['name' => 'Pronunciation', 'description' => 'Clear pronunciation practice'],
                        ['name' => 'Fluency', 'description' => 'Speaking fluently'],
                    ]
                ]
            ],
            'TOEFL' => [
                [
                    'name' => 'TOEFL Reading',
                    'description' => 'TOEFL Reading section preparation',
                    'subtopics' => [
                        ['name' => 'Main Ideas', 'description' => 'Identifying main topics'],
                        ['name' => 'Details', 'description' => 'Finding specific information'],
                        ['name' => 'Inferences', 'description' => 'Making conclusions'],
                        ['name' => 'Vocabulary', 'description' => 'Understanding words in context'],
                        ['name' => 'Reference', 'description' => 'Pronoun and reference words'],
                    ]
                ],
                [
                    'name' => 'TOEFL Writing',
                    'description' => 'TOEFL Writing section preparation',
                    'subtopics' => [
                        ['name' => 'Integrated Task', 'description' => 'Reading, listening and writing'],
                        ['name' => 'Independent Task', 'description' => 'Essay writing'],
                        ['name' => 'Essay Structure', 'description' => 'Organization and coherence'],
                        ['name' => 'Academic Style', 'description' => 'Formal writing style'],
                        ['name' => 'Common Topics', 'description' => 'Frequently tested topics'],
                    ]
                ],
                [
                    'name' => 'TOEFL Listening',
                    'description' => 'TOEFL Listening section preparation',
                    'subtopics' => [
                        ['name' => 'Conversations', 'description' => 'Campus conversations'],
                        ['name' => 'Lectures', 'description' => 'Academic lectures'],
                        ['name' => 'Main Ideas', 'description' => 'Understanding main points'],
                        ['name' => 'Details', 'description' => 'Catching specific details'],
                        ['name' => 'Function', 'description' => 'Understanding speaker purpose'],
                    ]
                ],
                [
                    'name' => 'TOEFL Speaking',
                    'description' => 'TOEFL Speaking section preparation',
                    'subtopics' => [
                        ['name' => 'Independent Tasks', 'description' => 'Personal experience topics'],
                        ['name' => 'Integrated Tasks', 'description' => 'Combining skills tasks'],
                        ['name' => 'Response Structure', 'description' => 'Organizing responses'],
                        ['name' => 'Pronunciation', 'description' => 'Clear speech practice'],
                        ['name' => 'Note Taking', 'description' => 'Quick note-taking skills'],
                    ]
                ]
            ]
        ];

        foreach ($categories as $category => $topics) {
            foreach ($topics as $topicData) {
                $subtopics = $topicData['subtopics'];
                unset($topicData['subtopics']);
                
                $topic = Topic::firstOrCreate(
                    ['name' => $topicData['name'], 'category' => $category],
                    ['description' => $topicData['description'], 'category' => $category]
                );
                
                foreach ($subtopics as $subtopicData) {
                    Subtopic::firstOrCreate(
                        ['name' => $subtopicData['name'], 'topic_id' => $topic->id],
                        ['description' => $subtopicData['description'], 'topic_id' => $topic->id]
                    );
                }
            }
        }
    }
} 