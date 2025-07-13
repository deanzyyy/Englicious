<?php if (isset($component)) { $__componentOriginal1f9e5f64f242295036c059d9dc1c375c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c = $attributes; } ?>
<?php $component = App\View\Components\Layout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Layout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen bg-[#1a1a1f] p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <?php if(isset($classroom)): ?>
                <a href="<?php echo e(route('classroom.exercises', $classroom->name)); ?>" class="flex items-center text-gray-400 hover:text-pink-400 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Back to Exercise List
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('exercises.index')); ?>" class="flex items-center text-gray-400 hover:text-pink-400 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Back to Exercise List
                </a>
            <?php endif; ?>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2"><?php echo e($exercise->title); ?></h1>
                <div class="flex items-center space-x-4 text-gray-400">
                    <span><?php echo e($exercise->category); ?></span>
                    <span>•</span>
                    <span><?php echo e($exercise->topic->name); ?></span>
                    <span>•</span>
                    <span><?php echo e($exercise->subtopic->name); ?></span>
                </div>
                <?php if($exercise->description): ?>
                    <p class="text-gray-300 mt-4"><?php echo e($exercise->description); ?></p>
                <?php endif; ?>
            </div>

            <?php if($exercise->is_file_upload && $exercise->file_path): ?>
                <!-- PDF Display -->
                <div class="bg-[#211F27] rounded-lg p-6 mb-8">
                    <div class="w-full rounded-lg overflow-hidden">
                        <iframe 
                            src="<?php echo e(route('preview.file', $exercise->id)); ?>"
                            class="w-full h-[800px] rounded-lg"
                            style="background: #1a1a1f;"
                            frameborder="0">
                        </iframe>
                    </div>
                </div>
            <?php else: ?>
                <!-- Questions Form -->
                <div id="exercise-timer" class="text-2xl font-bold text-pink-500 mb-6"></div>
                <form id="exerciseForm" action="<?php echo e(route('exercises.submit', $exercise->id)); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php if($errors->any()): ?>
                        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                            <ul class="list-disc pl-5">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(isset($classroom)): ?>
                        <input type="hidden" name="classroom_id" value="<?php echo e($classroom->id); ?>">
                    <?php endif; ?>
                    <div id="question-container"></div>
                    <div class="flex justify-between mt-6">
                        <button type="button" id="prevBtn" class="px-6 py-3 bg-gray-700 text-white rounded-lg" style="display:none">Previous</button>
                        <button type="button" id="nextBtn" class="px-6 py-3 bg-pink-500 text-white rounded-lg">Next</button>
                        <button type="submit" id="submitBtn" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white font-medium rounded-lg hover:from-pink-600 hover:to-orange-600 transition-all" style="display:none">Submit Answers</button>
                    </div>
                </form>
                <script>
                const exerciseId = <?php echo e($exercise->id); ?>;
                const category = <?php echo json_encode($exercise->topic->category, 15, 512) ?>;
                console.log('category:', category);
                const isToeflIelts = /toefl|ielts/i.test(category);
                const questions = <?php echo json_encode($exercise->questions, 15, 512) ?>;
                const duration = <?php echo e($exercise->duration); ?>;
                const totalQuestions = questions.length;

                const LS_INDEX = `exercise_${exerciseId}_index`;
                const LS_ANSWERS = `exercise_${exerciseId}_answers`;

                let currentIndex = parseInt(localStorage.getItem(LS_INDEX)) || 0;
                let answers = JSON.parse(localStorage.getItem(LS_ANSWERS) || '{}');

                let timerInterval = null;
                let timeLeft = 0;
                let justNext = false;

                function getPerSoalTime() {
                    return Math.floor(duration * 60 / totalQuestions);
                }

                function renderQuestion() {
                    answers = JSON.parse(localStorage.getItem(LS_ANSWERS) || '{}');
                    const q = questions[currentIndex];
                    let html = `<div class='bg-[#2A2A32] rounded-lg p-6 space-y-4'>`;
                    if (q.image_path) {
                        html += `<div class='mb-4 flex justify-center'><img src='/storage/${q.image_path}' class='max-w-[500px] max-h-[300px] w-auto h-auto object-contain rounded-lg'></div>`;
                    }
                    if (q.audio_path) {
                        html += `<div class='mb-4'><audio controls class='w-full'><source src='/storage/${q.audio_path}' type='audio/mpeg'></audio></div>`;
                    }
                    html += `<span class='text-pink-500 font-medium'>Question ${currentIndex+1}:</span><p class='mt-2'>${q.question_text}</p>`;
                    html += `<div class='space-y-3'>`;
                    if (q.type === 'essay') {
                        const val = answers[q.id] ? answers[q.id] : '';
                        html += `<textarea name='answers[${q.id}]' rows='5' class='w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none' placeholder='Write your answer here...'>${val}</textarea>`;
                    } else {
                        q.options.forEach((opt, idx) => {
                            const checked = answers[q.id] == idx ? 'checked' : '';
                            html += `<label class='flex items-center space-x-3 p-3 rounded-lg bg-[#32323A] hover:bg-[#3A3A42] cursor-pointer transition-colors'><input type='radio' name='answers[${q.id}]' value='${idx}' class='text-pink-500 focus:ring-pink-500' ${checked}><span class='text-white'>${opt}</span></label>`;
                        });
                    }
                    html += `</div></div>`;
                    document.getElementById('question-container').innerHTML = html;

                    // Button logic
                    document.getElementById('prevBtn').style.display = (isToeflIelts ? 'none' : (currentIndex > 0 ? '' : 'none'));
                    document.getElementById('nextBtn').style.display = (currentIndex < totalQuestions-1 ? '' : 'none');
                    document.getElementById('submitBtn').style.display = (currentIndex === totalQuestions-1 || isToeflIelts) ? '' : 'none';

                    // TIMER LOGIC
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                    }
                    if (isToeflIelts) {
                        const soalTimeKey = `exercise_${exerciseId}_time_${currentIndex}`;
                        if (justNext) {
                            timeLeft = getPerSoalTime();
                            justNext = false;
                        } else if (localStorage.getItem(soalTimeKey)) {
                            timeLeft = parseInt(localStorage.getItem(soalTimeKey));
                        } else {
                            timeLeft = getPerSoalTime();
                        }
                        console.log('Set timeLeft TOEFL/IELTS:', timeLeft, 'detik');
                    } else {
                        // Untuk kategori lain, timer global
                        if (window.globalTimeLeft !== undefined) {
                            timeLeft = window.globalTimeLeft;
                        } else {
                            timeLeft = duration * 60;
                        }
                    }
                    document.getElementById('exercise-timer').textContent = formatTime(timeLeft);
                    startTimer();
                }

                function startTimer() {
                    timerInterval = setInterval(() => {
                        timeLeft--;
                        document.getElementById('exercise-timer').textContent = formatTime(timeLeft);
                        // DEBUG LOG
                        console.log('Timer:', timeLeft, 'detik, soal ke', currentIndex+1);

                        if (isToeflIelts) {
                            localStorage.setItem(`exercise_${exerciseId}_time_${currentIndex}`, timeLeft);
                        } else {
                            window.globalTimeLeft = timeLeft;
                        }

                        if (timeLeft <= 0) {
                            clearInterval(timerInterval);
                            timerInterval = null;
                            if (isToeflIelts) {
                                // Hapus waktu soal yang sudah selesai
                                localStorage.removeItem(`exercise_${exerciseId}_time_${currentIndex}`);
                                goNextToefl();
                            } else {
                                document.getElementById('exerciseForm').submit();
                            }
                        }
                    }, 1000);
                }

                function formatTime(sec) {
                    const m = Math.floor(sec/60).toString().padStart(2,'0');
                    const s = (sec%60).toString().padStart(2,'0');
                    return `${m}:${s}`;
                }

                // Navigasi
                document.getElementById('nextBtn').onclick = function() {
                    saveAnswer();
                    if (currentIndex < totalQuestions-1) {
                        currentIndex++;
                        localStorage.setItem(LS_INDEX, currentIndex);
                        justNext = true;
                        renderQuestion();
                    }
                };
                // Previous hanya untuk non TOEFL/IELTS
                if (!isToeflIelts) {
                    document.getElementById('prevBtn').onclick = function() {
                        saveAnswer();
                        if (currentIndex > 0) {
                            currentIndex--;
                            localStorage.setItem(LS_INDEX, currentIndex);
                            renderQuestion();
                        }
                    };
                }
                // Save answer on change
                document.addEventListener('change', function(e) {
                    if (e.target.name && e.target.name.startsWith('answers[')) {
                        saveAnswer();
                    }
                });
                function saveAnswer() {
                    const q = questions[currentIndex];
                    if (q.type === 'essay') {
                        const textarea = document.querySelector('textarea[name="answers['+q.id+']"]');
                        if (textarea) {
                            answers[q.id] = textarea.value;
                        }
                    } else {
                        const selected = document.querySelector('input[name="answers['+q.id+']"]:checked');
                        if (selected) {
                            answers[q.id] = parseInt(selected.value);
                        }
                    }
                    localStorage.setItem(LS_ANSWERS, JSON.stringify(answers));
                }
                // Simpan semua jawaban sebelum submit
                function saveAllAnswers() {
                    questions.forEach((q, idx) => {
                        if (q.type === 'essay') {
                            const textarea = document.querySelector('textarea[name="answers['+q.id+']"]');
                            if (textarea) {
                                answers[q.id] = textarea.value;
                            }
                        } else {
                            const selected = document.querySelector('input[name="answers['+q.id+']"]:checked');
                            if (selected) {
                                answers[q.id] = parseInt(selected.value);
                            }
                        }
                    });
                    localStorage.setItem(LS_ANSWERS, JSON.stringify(answers));
                }
                // Inject semua jawaban ke form sebagai input hidden sebelum submit
                function injectAllAnswersToForm() {
                    const form = document.getElementById('exerciseForm');
                    // Hapus input hidden lama
                    const oldInputs = form.querySelectorAll('.js-dynamic-answer');
                    oldInputs.forEach(i => i.remove());
                    // Ambil semua jawaban dari localStorage
                    const allAnswers = JSON.parse(localStorage.getItem(LS_ANSWERS) || '{}');
                    Object.keys(allAnswers).forEach(qid => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `answers[${qid}]`;
                        input.value = allAnswers[qid];
                        input.className = 'js-dynamic-answer';
                        form.appendChild(input);
                    });
                }
                // TOEFL/IELTS: auto next per soal
                function goNextToefl() {
                    saveAnswer();
                    if (currentIndex < totalQuestions-1) {
                        currentIndex++;
                        localStorage.setItem(LS_INDEX, currentIndex);
                        justNext = true;
                        renderQuestion();
                    } else {
                        document.getElementById('exerciseForm').submit();
                    }
                }
                // Reset local storage hanya saat submit dan tombol back
                document.getElementById('exerciseForm').onsubmit = function() {
                    saveAllAnswers();
                    injectAllAnswersToForm();
                    localStorage.removeItem(LS_INDEX);
                    localStorage.removeItem(LS_ANSWERS);
                    window.globalTimeLeft = undefined;
                    if (isToeflIelts) {
                        for (let i = 0; i < totalQuestions; i++) {
                            localStorage.removeItem(`exercise_${exerciseId}_time_${i}`);
                        }
                    }
                    // Allow form to submit normally
                    return true;
                };
                // Jika ada tombol back, reset localStorage saat klik back
                const backBtn = document.getElementById('backButton');
                if (backBtn) {
                    backBtn.addEventListener('click', function() {
                        localStorage.removeItem(LS_INDEX);
                        localStorage.removeItem(LS_ANSWERS);
                        window.globalTimeLeft = undefined;
                        if (isToeflIelts) {
                            for (let i = 0; i < totalQuestions; i++) {
                                localStorage.removeItem(`exercise_${exerciseId}_time_${i}`);
                            }
                        }
                    });
                }

                // Init
                renderQuestion();
                </script>
            <?php endif; ?>

            <!-- Score Alert -->
            <?php if(session('success')): ?>
                <div class="fixed bottom-4 right-4">
                    <div class="bg-[#211F27] border border-green-500 text-white px-8 py-6 rounded-lg shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-green-500">Exercise Completed!</h3>
                                <p class="text-gray-300"><?php echo e(session('success')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="fixed bottom-4 right-4">
                    <div class="bg-[#211F27] border border-red-500 text-white px-8 py-6 rounded-lg shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-500">Error</h3>
                                <p class="text-gray-300"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $attributes = $__attributesOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__attributesOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c)): ?>
<?php $component = $__componentOriginal1f9e5f64f242295036c059d9dc1c375c; ?>
<?php unset($__componentOriginal1f9e5f64f242295036c059d9dc1c375c); ?>
<?php endif; ?> <?php /**PATH D:\Englicious\Englicious\resources\views/exercises/take.blade.php ENDPATH**/ ?>