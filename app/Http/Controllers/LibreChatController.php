<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LibreChatController extends Controller
{
    protected $baseUrl;
    protected $model;

    public function __construct()
    {
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent';
    }

    public function index()
    {
        return view('librechat.chat');
    }

    public function chat(Request $request)
    {
        try {
            $message = $request->input('message');
            if (empty($message)) {
                throw new \Exception('Pesan tidak boleh kosong');
            }

            // Log request details
            Log::info('Starting Chat request', [
                'message' => $message
            ]);

            // Get API key from environment
            $apiKey = env('GOOGLE_AI_KEY');
            if (empty($apiKey)) {
                throw new \Exception('API key Google belum dikonfigurasi');
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(30)
            ->post($this->baseUrl . '?key=' . $apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $message
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 800,
                ]
            ]);

            // Log the raw response for debugging
            Log::info('Raw API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->status() === 404) {
                throw new \Exception('Model AI tidak ditemukan. Pastikan API key valid dan memiliki akses ke Gemini Pro.');
            }

            if (!$response->successful()) {
                throw new \Exception('Error dari API: ' . $response->status() . ' - ' . $response->body());
            }

            $responseData = $response->json();
            
            // Log parsed response
            Log::info('Parsed Response', [
                'responseData' => $responseData
            ]);

            // Extract the generated text from the response
            $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$generatedText) {
                throw new \Exception('Tidak ada respon dari API');
            }

            return response()->json([
                'success' => true,
                'message' => $generatedText
            ]);

        } catch (\Exception $e) {
            Log::error('Chat Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a more user-friendly error message
            $errorMessage = $e->getMessage();
            if (str_contains($errorMessage, 'API key')) {
                $errorMessage = 'API key tidak valid atau belum dikonfigurasi. Silakan ikuti langkah-langkah berikut untuk mendapatkan API key:
1. Kunjungi https://makersuite.google.com/app/apikey
2. Login dengan akun Google Anda
3. Klik "Create API Key"
4. Copy API key yang dihasilkan
5. Tambahkan ke file .env dengan format GOOGLE_AI_KEY=your_api_key';
            }

            return response()->json([
                'success' => false,
                'error' => $errorMessage
            ], 500);
        }
    }
} 