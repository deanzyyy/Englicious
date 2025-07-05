<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Topic;
use App\Models\Subtopic;
use App\Models\Exercise;
use App\Models\Material;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create test user if not exists
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'teacher'
            ]
        );

        // Create test classrooms
        $classrooms = [
            [
                'name' => 'Ignatius Classroom',
                'description' => 'This is a test classroom for Ignatius',
                'password' => 'test123',
                'code' => 'TEST01',
                'teacher_id' => $user->id
            ],
            [
                'name' => 'English Class',
                'description' => 'General English class with Ignatius materials',
                'password' => 'test123',
                'code' => 'TEST02',
                'teacher_id' => $user->id
            ]
        ];

        foreach ($classrooms as $classroomData) {
            Classroom::firstOrCreate(
                ['name' => $classroomData['name']],
                $classroomData
            );
        }

        // Create test topic and subtopic
        $topic = Topic::firstOrCreate(
            ['name' => 'Test Topic'],
            [
                'description' => 'Test topic for exercises and materials',
                'category' => 'General'
            ]
        );

        $subtopic = Subtopic::firstOrCreate(
            ['name' => 'Test Subtopic'],
            [
                'description' => 'Test subtopic for exercises and materials',
                'topic_id' => $topic->id
            ]
        );

        // Create test exercises
        $exercises = [
            [
                'title' => 'Ignatius Grammar Exercise',
                'description' => 'Grammar exercise created by Ignatius',
                'category' => 'Grammar',
                'topic_id' => $topic->id,
                'subtopic_id' => $subtopic->id,
                'is_file_upload' => false
            ],
            [
                'title' => 'English Vocabulary Test',
                'description' => 'Vocabulary test with Ignatius content',
                'category' => 'Vocabulary',
                'topic_id' => $topic->id,
                'subtopic_id' => $subtopic->id,
                'is_file_upload' => false
            ]
        ];

        foreach ($exercises as $exerciseData) {
            Exercise::firstOrCreate(
                ['title' => $exerciseData['title']],
                $exerciseData
            );
        }

        // Create test materials
        $materials = [
            [
                'title' => 'Ignatius Study Material',
                'description' => 'Study material prepared by Ignatius',
                'file_path' => '/test/ignatius.pdf',
                'topic_id' => $topic->id,
                'subtopic_id' => $subtopic->id,
                'category' => 'General'
            ],
            [
                'title' => 'English Learning Guide',
                'description' => 'Learning guide with Ignatius methodology',
                'file_path' => '/test/guide.pdf',
                'topic_id' => $topic->id,
                'subtopic_id' => $subtopic->id,
                'category' => 'General'
            ]
        ];

        foreach ($materials as $materialData) {
            Material::firstOrCreate(
                ['title' => $materialData['title']],
                $materialData
            );
        }

        echo "Test data created successfully!\n";
        echo "Try searching for 'ignatius' in the global search.\n";
    }
} 