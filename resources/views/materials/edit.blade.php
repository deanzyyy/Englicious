@if(auth()->user()->role === 'student')
    <div class="p-10 text-center text-red-500 text-xl font-bold">Akses ditolak.</div>
    @php return; @endphp
@endif

<x-layout>
    <div class="mx-25">
        <div class="p-10 flex justify-between items-center">
            <div>
                <h1 class="text-white text-4xl font-bold">Edit Material</h1>
                <p class="text-lg text-gray-400 mt-2">Update material details and PDF file</p>
            </div>
            <button id="backButton" onclick="handleBack()" class="px-6 py-3 border border-pink-500 text-pink-500 rounded-lg hover:bg-pink-500 hover:text-white transition-all duration-200 flex items-center space-x-2">
                <i class="fi fi-rs-angle-left"></i>
                <span>Back to Materials List</span>
            </button>
        </div>

        <div class="container p-2">
            <div class="content bg-[#211F27] p-10 rounded-lg">
                <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" id="materialForm">
                    @csrf
                    @method('PUT')
                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-white text-sm font-medium mb-2">Title</label>
                            <input type="text" name="title" id="title" required
                                class="w-full bg-[#1a1a1f] text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="Enter material title"
                                value="{{ $material->title }}">
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-white text-sm font-medium mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full bg-[#1a1a1f] text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="Enter material description">{{ $material->description }}</textarea>
                        </div>

                        <!-- Category and Topic Selection -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="category" class="block text-white text-sm font-medium mb-2">Category</label>
                                <select name="category" id="category" required onchange="loadTopics()"
                                    class="w-full bg-[#1a1a1f] text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                    <option value="">Select Category</option>
                                    @foreach($topics as $category => $categoryTopics)
                                        <option value="{{ $category }}" {{ $material->topic->category === $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="topic_id" class="block text-white text-sm font-medium mb-2">Topic</label>
                                <select name="topic_id" id="topic_id" required onchange="loadSubtopics()"
                                    class="w-full bg-[#1a1a1f] text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                    <option value="">Select Topic</option>
                                    @foreach($topics[$material->topic->category] as $topic)
                                        <option value="{{ $topic->id }}" {{ $material->topic_id === $topic->id ? 'selected' : '' }}>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Subtopic Selection -->
                        <div>
                            <label for="subtopic_id" class="block text-white text-sm font-medium mb-2">Subtopic</label>
                            <select name="subtopic_id" id="subtopic_id" required
                                class="w-full bg-[#1a1a1f] text-white rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                <option value="">Select Subtopic</option>
                                @foreach($subtopics as $subtopic)
                                    <option value="{{ $subtopic->id }}" {{ $material->subtopic_id === $subtopic->id ? 'selected' : '' }}>
                                        {{ $subtopic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Current PDF File -->
                        <div>
                            <label class="block text-white text-sm font-medium mb-2">Current PDF File</label>
                            <div class="flex items-center space-x-4 bg-[#1a1a1f] p-4 rounded-lg">
                                <i class="fi fi-rr-file-pdf text-2xl text-pink-500"></i>
                                <a href="{{ Storage::url($material->file_path) }}" target="_blank" 
                                   class="text-pink-500 hover:text-pink-400 transition-colors">
                                    View Current PDF
                                </a>
                            </div>
                        </div>

                        <!-- New PDF File Upload -->
                        <div>
                            <label for="pdf_file" class="block text-white text-sm font-medium mb-2">Upload New PDF (Optional)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="pdf_file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-[#1a1a1f] hover:bg-[#2a2833] transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fi fi-rr-file-pdf text-4xl text-gray-400 mb-4"></i>
                                        <p class="mb-2 text-sm text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-400">PDF (MAX. 10MB)</p>
                                    </div>
                                    <input id="pdf_file" name="pdf_file" type="file" class="hidden" accept=".pdf" />
                                </label>
                            </div>
                            <div id="file-name" class="mt-2 text-sm text-gray-400"></div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                                Update Material
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Back Confirmation Modal -->
    <div id="backModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-[#211F27] p-6 rounded-lg w-96">
            <h2 class="text-xl font-bold text-white mb-4">Leave Page?</h2>
            <p class="text-gray-400 mb-4">You have unsaved changes. Are you sure you want to leave this page?</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeBackModal()" class="px-4 py-2 text-gray-400 hover:text-white">Cancel</button>
                <button onclick="confirmBack()" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Leave</button>
            </div>
        </div>
    </div>

    <script>
        let hasUnsavedChanges = false;

        // Track form changes
        document.getElementById('materialForm').addEventListener('change', function() {
            hasUnsavedChanges = true;
        });

        // Handle file input change
        document.getElementById('pdf_file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = `Selected file: ${fileName}`;
            }
        });

        function handleBack() {
            if (hasUnsavedChanges) {
                document.getElementById('backModal').classList.remove('hidden');
                document.getElementById('backModal').classList.add('flex');
            } else {
                window.location.href = '{{ route("materials.index") }}';
            }
        }

        function confirmBack() {
            window.location.href = '{{ route("materials.index") }}';
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
            document.getElementById('backModal').classList.remove('flex');
        }

        function loadTopics() {
            const category = document.getElementById('category').value;
            const topicSelect = document.getElementById('topic_id');
            const subtopicSelect = document.getElementById('subtopic_id');
            
            // Clear existing options
            topicSelect.innerHTML = '<option value="">Select Topic</option>';
            subtopicSelect.innerHTML = '<option value="">Select Subtopic</option>';
            
            if (category) {
                @foreach($topics as $category => $categoryTopics)
                    if ('{{ $category }}' === category) {
                        @foreach($categoryTopics as $topic)
                            const option = document.createElement('option');
                            option.value = '{{ $topic->id }}';
                            option.textContent = '{{ $topic->name }}';
                            topicSelect.appendChild(option);
                        @endforeach
                    }
                @endforeach
            }
        }

        function loadSubtopics() {
            const topicId = document.getElementById('topic_id').value;
            const subtopicSelect = document.getElementById('subtopic_id');
            
            // Clear existing options
            subtopicSelect.innerHTML = '<option value="">Select Subtopic</option>';
            
            if (topicId) {
                fetch(`/get-subtopics/${topicId}`)
                    .then(response => response.json())
                    .then(subtopics => {
                        subtopics.forEach(subtopic => {
                            const option = document.createElement('option');
                            option.value = subtopic.id;
                            option.textContent = subtopic.name;
                            subtopicSelect.appendChild(option);
                        });
                    });
            }
        }

        // Handle drag and drop
        const dropZone = document.querySelector('label[for="pdf_file"]');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-pink-500');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-pink-500');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const fileInput = document.getElementById('pdf_file');
            
            fileInput.files = files;
            document.getElementById('file-name').textContent = `Selected file: ${files[0].name}`;
            hasUnsavedChanges = true;
        }
    </script>
</x-layout> 