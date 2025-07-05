<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class GenAiChat extends Component
{
    public $messages = [];
    public $prompt = '';

    public $isLoading = false;

    public function mount()
    {
        // Load messages from session when component mounts
        $this->messages = session('voca_chat_messages', []);
    }

    public function sendPrompt()
    {
        if (trim($this->prompt) === '') return;

        $this->messages[] = ['role' => 'user', 'text' => $this->prompt];

        $this->isLoading = true;
        // Kirim ke server FastAPI atau panggil API lokal Python-mu
        $response = Http::post('http://127.0.0.1:8008/ask', [
            'history' => $this->messages,
            'prompt' => $this->prompt
        ]);
        
        $this->messages[] = [
            'role' => 'ai',
            'text' => $response->json()['response'] ?? 'Tidak ada respon.'
        ];

        // Save messages to session
        session(['voca_chat_messages' => $this->messages]);

        $this->prompt = '';
        $this->isLoading = false;
    }

    public function refreshChat()
    {
        // Clear messages from session and component
        session()->forget('voca_chat_messages');
        $this->messages = [];
    }

    public function render()
    {
        return view('livewire.gen-ai-chat');
    }
}