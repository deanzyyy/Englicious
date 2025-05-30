
<div class="chat-box my-auto">
    <h2 class='flex justify-center text-3xl font-semibold'>Gemini Ai</h2>

    @foreach($messages as $msg)
    <div class="message {{ $msg['role'] }}">
        <div class="text">{{ $msg['text'] }}</div>
    </div>
    @endforeach
        
    <form wire:submit.prevent="sendPrompt">
        <input wire:model.defer="prompt" type="text" placeholder="Ketik pesan..." required>
        <button type="submit">Kirim</button>
    </form>


</div>