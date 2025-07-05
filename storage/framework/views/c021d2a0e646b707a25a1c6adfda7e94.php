

<?php $__env->startSection('content'); ?>
<div class="flex flex-col max-w-3xl mx-auto w-full min-h-[calc(100vh-5rem)]">
    <h1 class="text-3xl font-bold mb-6 text-pink-400 flex items-center gap-2 mt-6 justify-center text-center w-full">
        <i class="fi fi-rr-book-open-reader text-3xl"></i> Dictionary & Translate
    </h1>
    <div id="result-area" class="flex-1 px-2 pb-4 relative">
        <div id="welcome-message" class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
            <div>
                <i class="fi fi-rr-search text-5xl text-pink-400 mb-4"></i>
                <p class="text-2xl text-white font-semibold mb-2">What word are you curious about?</p>
                <p class="text-lg text-gray-300">Type it below to see the definition and translation!</p>
            </div>
        </div>
        <div id="loading" class="hidden text-center py-8">
            <svg class="animate-spin h-10 w-10 text-pink-400 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
            <span class="text-lg text-pink-500 font-bold">Loading...</span>
        </div>
        <div id="error-message" class="hidden bg-pink-500/90 text-white px-4 py-3 rounded-lg mb-4"></div>
        <div id="result" class="hidden">
            <div class="mb-4 flex flex-col items-center justify-center">
                <h2 class="text-2xl font-semibold mb-2 text-white text-center" id="result-word"></h2>
                <div class="flex flex-col items-center justify-center gap-2 mb-2 w-full">
                    <span id="result-phonetic" class="text-pink-300 text-lg"></span>
                    <div id="audio-container" class="flex justify-center w-full"></div>
                </div>
                <!-- Translation Google Translate Style -->
                <div class="w-full flex flex-col md:flex-row gap-4 justify-center items-stretch mb-4">
                    <div class="flex-1 bg-[#18171d] rounded-xl shadow border border-pink-500/20 p-4 flex flex-col">
                        <span class="text-xs text-pink-400 font-bold mb-2">ENGLISH</span>
                        <span id="result-english" class="text-lg text-white break-words"></span>
                    </div>
                    <div class="flex-1 bg-[#18171d] rounded-xl shadow border border-orange-400/20 p-4 flex flex-col">
                        <span class="text-xs text-orange-400 font-bold mb-2">INDONESIAN</span>
                        <span id="result-translation" class="text-lg text-white break-words"></span>
                    </div>
                </div>
            </div>
            <div id="result-meanings"></div>
        </div>
    </div>
    <form id="dictionary-form" class="w-full flex gap-2 items-center bg-[#18171d] border-t border-pink-500/20 px-4 py-4 sticky bottom-0 z-10" autocomplete="off" style="box-shadow: 0 -2px 16px 0 #211F27cc;">
        <input type="text" id="word-input" name="word" placeholder="Type a word to search..." class="flex-1 px-4 py-3 rounded-lg bg-[#101014] text-white border border-pink-500/30 focus:border-pink-500 focus:ring-2 focus:ring-pink-500 outline-none transition text-lg" required>
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg font-semibold hover:opacity-90 transition text-lg">Search</button>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    const form = document.getElementById('dictionary-form');
    const input = document.getElementById('word-input');
    const loading = document.getElementById('loading');
    const errorDiv = document.getElementById('error-message');
    const resultDiv = document.getElementById('result');
    const resultWord = document.getElementById('result-word');
    const resultPhonetic = document.getElementById('result-phonetic');
    const resultMeanings = document.getElementById('result-meanings');
    const resultTranslation = document.getElementById('result-translation');
    const resultArea = document.getElementById('result-area');
    const welcomeMessage = document.getElementById('welcome-message');
    const audioContainer = document.getElementById('audio-container');

    let hasSearched = false;
    let customAudio = null;
    let customAudioInterval = null;

    function showWelcome(show) {
        if (show) {
            welcomeMessage.classList.remove('hidden');
        } else {
            welcomeMessage.classList.add('hidden');
        }
    }

    showWelcome(true);

    function formatTime(sec) {
        sec = Math.floor(sec);
        return sec >= 60 ? `${Math.floor(sec/60)}:${('0'+(sec%60)).slice(-2)}` : `0:${('0'+sec).slice(-2)}`;
    }

    function renderCustomAudioPlayer(audioUrl) {
        // Clean up previous
        if (customAudio) {
            customAudio.pause();
            customAudio = null;
        }
        if (customAudioInterval) {
            clearInterval(customAudioInterval);
            customAudioInterval = null;
        }
        // HTML
        audioContainer.innerHTML = `
        <div class="flex items-center bg-[#18171d] rounded-2xl shadow-lg px-4 py-3 w-full max-w-lg mx-auto gap-4 border border-pink-500/20">
            <button id="audio-play-btn" class="w-12 h-12 flex items-center justify-center rounded-full bg-pink-500 hover:bg-pink-600 transition focus:outline-none">
                <svg id="audio-play-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v18l15-9-15-9z"/></svg>
                <svg id="audio-pause-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="white" class="w-7 h-7 hidden"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h4v16H6zm8 0h4v16h-4z"/></svg>
            </button>
            <div class="flex-1 flex flex-col gap-1">
                <div class="w-full h-2 bg-[#23222b] rounded-full overflow-hidden relative">
                    <div id="audio-progress-bar" class="h-2 bg-gradient-to-r from-pink-500 to-orange-500 absolute top-0 left-0 rounded-full" style="width:0%"></div>
                    <input id="audio-progress-input" type="range" min="0" max="100" value="0" step="0.1" class="absolute top-0 left-0 w-full h-2 opacity-0 cursor-pointer" />
                </div>
                <div class="flex justify-between text-xs text-gray-300 font-mono mt-1">
                    <span id="audio-current">0:00</span>
                    <span id="audio-duration">0:00</span>
                </div>
            </div>
        </div>
        <audio id="custom-audio" src="${audioUrl}" preload="auto" style="display:none;"></audio>
        `;
        // JS
        customAudio = document.getElementById('custom-audio');
        const playBtn = document.getElementById('audio-play-btn');
        const playIcon = document.getElementById('audio-play-icon');
        const pauseIcon = document.getElementById('audio-pause-icon');
        const progressBar = document.getElementById('audio-progress-bar');
        const progressInput = document.getElementById('audio-progress-input');
        const currentSpan = document.getElementById('audio-current');
        const durationSpan = document.getElementById('audio-duration');

        let isPlaying = false;

        playBtn.onclick = function() {
            if (!customAudio) return;
            if (customAudio.paused) {
                customAudio.play();
            } else {
                customAudio.pause();
            }
        };
        customAudio.onplay = function() {
            isPlaying = true;
            playIcon.classList.add('hidden');
            pauseIcon.classList.remove('hidden');
        };
        customAudio.onpause = function() {
            isPlaying = false;
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
        };
        customAudio.onloadedmetadata = function() {
            durationSpan.textContent = formatTime(customAudio.duration);
        };
        customAudio.ontimeupdate = function() {
            const percent = (customAudio.currentTime / customAudio.duration) * 100;
            progressBar.style.width = percent + '%';
            progressInput.value = percent;
            currentSpan.textContent = formatTime(customAudio.currentTime);
        };
        progressInput.oninput = function(e) {
            if (!customAudio.duration) return;
            const percent = parseFloat(e.target.value);
            customAudio.currentTime = (percent / 100) * customAudio.duration;
        };
        customAudio.onended = function() {
            playIcon.classList.remove('hidden');
            pauseIcon.classList.add('hidden');
            progressBar.style.width = '0%';
            progressInput.value = 0;
            currentSpan.textContent = '0:00';
        };
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const word = input.value.trim();
        if (!word) return;
        // Jika input lebih dari 1 kata, tampilkan error khusus
        if (word.split(/\s+/).length > 1) {
            errorDiv.textContent = 'Please enter only one word.';
            errorDiv.classList.remove('hidden');
            return;
        }
        hasSearched = true;
        showWelcome(false);
        errorDiv.classList.add('hidden');
        resultDiv.classList.add('hidden');
        loading.classList.remove('hidden');
        resultWord.textContent = '';
        resultPhonetic.textContent = '';
        audioContainer.innerHTML = '';
        resultMeanings.innerHTML = '';
        resultTranslation.textContent = '';
        document.getElementById('result-english').textContent = '';
        try {
            const res = await fetch("<?php echo e(route('dictionary.search')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
                },
                body: JSON.stringify({ word })
            });
            const data = await res.json();
            loading.classList.add('hidden');
            if (data.error) {
                errorDiv.textContent = data.error;
                errorDiv.classList.remove('hidden');
                return;
            }
            // Word & phonetic
            resultWord.textContent = data.dictionary.word || word;
            if (data.dictionary.phonetic) {
                resultPhonetic.textContent = data.dictionary.phonetic;
            } else if (data.dictionary.phonetics && data.dictionary.phonetics.length > 0) {
                const phon = data.dictionary.phonetics.find(p => p.text);
                if (phon) resultPhonetic.textContent = phon.text;
            }
            // Audio
            audioContainer.innerHTML = '';
            let audioUrl = null;
            if (data.dictionary.phonetics && data.dictionary.phonetics.length > 0) {
                const audio = data.dictionary.phonetics.find(p => p.audio);
                if (audio && audio.audio) {
                    audioUrl = audio.audio.startsWith('http') ? audio.audio : 'https:' + audio.audio;
                }
            }
            if (audioUrl) {
                renderCustomAudioPlayer(audioUrl);
            }
            // Translation
            resultTranslation.textContent = data.translation || '-';
            document.getElementById('result-english').textContent = word;
            // Meanings
            resultMeanings.innerHTML = '';
            if (data.dictionary.meanings && data.dictionary.meanings.length > 0) {
                data.dictionary.meanings.forEach(meaning => {
                    const part = document.createElement('div');
                    part.className = 'mb-6';
                    part.innerHTML = `<div class='font-bold text-pink-400 mb-1 text-lg'>${meaning.partOfSpeech}</div>`;
                    meaning.definitions.forEach((def, idx) => {
                        part.innerHTML += `<div class='mb-2'><span class='font-semibold text-white'>${idx+1}. ${def.definition}</span>`;
                        if (def.example) {
                            part.innerHTML += `<div class='text-gray-300 text-base mt-1'>Example: <span class='italic'>${def.example}</span></div>`;
                        }
                        if (def.synonyms && def.synonyms.length > 0) {
                            part.innerHTML += `<div class='text-base text-orange-300 mt-1'>Synonyms: <span>${def.synonyms.join(', ')}</span></div>`;
                        }
                        part.innerHTML += '</div>';
                    });
                    resultMeanings.appendChild(part);
                });
            } else {
                resultMeanings.innerHTML = '<div class="text-gray-400">No definitions found.</div>';
            }
            resultDiv.classList.remove('hidden');
            // Scroll ke atas hasil
            setTimeout(() => {
                resultArea.scrollTo({ top: 0, behavior: 'smooth' });
            }, 100);
        } catch (err) {
            loading.classList.add('hidden');
            errorDiv.textContent = 'Failed to fetch data. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });

    // Hide welcome message if loading, error, or result is shown
    function updateWelcomeVisibility() {
        if (!hasSearched && loading.classList.contains('hidden') && errorDiv.classList.contains('hidden') && resultDiv.classList.contains('hidden')) {
            showWelcome(true);
        } else {
            showWelcome(false);
        }
    }
    // Observe changes
    const observer = new MutationObserver(updateWelcomeVisibility);
    observer.observe(loading, { attributes: true, attributeFilter: ['class'] });
    observer.observe(errorDiv, { attributes: true, attributeFilter: ['class'] });
    observer.observe(resultDiv, { attributes: true, attributeFilter: ['class'] });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/dictionary.blade.php ENDPATH**/ ?>