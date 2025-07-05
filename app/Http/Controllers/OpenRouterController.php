<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterController extends Controller
{
    protected $baseUrl = 'https://api.openrouter.ai/api/v1';
    protected $model = 'anthropic/claude-2';

    public function index()
    {
        return view('openrouter.chat');
    }

    public function chat(Request $request)
    {
        try {
            $prompt = $request->input('message');
            if (empty($prompt)) {
                throw new \Exception('Message cannot be empty');
            }

            // Log request details
            Log::info('Starting OpenRouter request', [
                'prompt' => $prompt,
                'model' => $this->model
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type' => 'application/json',
                'Origin' => config('app.url'),
                'Referer' => config('app.url'),
                'HTTP-Referer' => config('app.url')
            ])
            ->timeout(60)
            ->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            // Log the complete response for debugging
            Log::info('OpenRouter Response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->json()
            ]);

            if (!$response->successful()) {
                Log::error('OpenRouter Error Response', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                throw new \Exception('Error from OpenRouter: ' . $response->status() . ' - ' . json_encode($response->json()));
            }

            $responseData = $response->json();
            
            return response()->json([
                'success' => true,
                'message' => $responseData['choices'][0]['message']['content'] ?? 'No response content'
            ]);

        } catch (\Exception $e) {
            Log::error('OpenRouter Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 