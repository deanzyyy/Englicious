<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DictionaryController extends Controller
{
    // Tampilkan halaman dictionary
    public function index()
    {
        return view('dictionary');
    }

    // Handle pencarian dictionary dan translate
    public function search(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
        ]);
        $word = $request->input('word');
        $dictionaryApi = "https://api.dictionaryapi.dev/api/v2/entries/en/" . urlencode($word);
        $dictionaryResult = null;
        $translateResult = null;
        $error = null;

        try {
            // Dictionary API
            $response = Http::timeout(10)->get($dictionaryApi);
            if ($response->successful() && !empty($response[0])) {
                $dictionaryResult = $response[0];
            }
            // Translate ke Indonesia (LibreTranslate utama)
            $translateApi = 'https://libretranslate.com/translate';
            $translateResponse = Http::timeout(10)->post($translateApi, [
                'q' => $word,
                'source' => 'en',
                'target' => 'id',
                'format' => 'text',
            ]);
            if ($translateResponse->successful() && !empty($translateResponse['translatedText'])) {
                $translateResult = $translateResponse['translatedText'];
            } else {
                // Fallback ke MyMemory jika gagal
                $fallbackApi = 'https://api.mymemory.translated.net/get?q=' . urlencode($word) . '&langpair=en|id';
                $fallbackResponse = Http::timeout(10)->get($fallbackApi);
                if ($fallbackResponse->successful() && !empty($fallbackResponse['responseData']['translatedText'])) {
                    $translateResult = $fallbackResponse['responseData']['translatedText'];
                }
            }
            if (!$dictionaryResult && !$translateResult) {
                $error = 'Word or sentence not found.';
            }
        } catch (\Exception $e) {
            $error = 'Error fetching data: ' . $e->getMessage();
        }

        return response()->json([
            'dictionary' => $dictionaryResult,
            'translation' => $translateResult,
            'error' => $error,
        ]);
    }
} 