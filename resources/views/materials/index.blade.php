<x-layout>
    <div class="ml-10 p-10">
        @if(isset($error))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $error }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white">Materials List</h1>
                <p class="text-lg text-gray-400 mt-2">Total: {{ $total_materials }} {{ Str::plural('Material', $total_materials) }}</p>
            </div>
            @if(auth()->user()->role !== 'student')
            <a href="{{ route('materials.create') }}" 
                class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Upload New Material
            </a>
            @endif
        </div>

        <!-- Category Filter Tabs -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-4">
                <a href="{{ route('materials.index') }}" 
                    class="category-tab px-4 py-2 text-white rounded-lg hover:bg-white/5 transition-all {{ $currentCategory === 'all' ? 'bg-gradient-to-r from-pink-500 to-orange-500' : '' }}">
                    All Materials
                </a>
                @foreach($allCategories as $category)
                    <a href="{{ route('materials.index', ['category' => $category]) }}" 
                        class="category-tab px-4 py-2 text-white rounded-lg hover:bg-white/5 transition-all {{ $currentCategory === $category ? 'bg-gradient-to-r from-pink-500 to-orange-500' : '' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
            @if(auth()->user()->role !== 'student')
            <button onclick="window.openAddTopicModal()" 
                    class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center space-x-2">
                <i class="fi fi-rr-plus"></i>
                <span>Add New Topic</span>
            </button>
            @endif
        </div>

        @if(auth()->user()->role === 'student' && $noClassroomJoined)
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20 p-8 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-sad-tear text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">Materials belum tersedia</h3>
                <p class="text-gray-400 mb-4">
                    Silahkan bergabung ke dalam classroom terlebih dahulu untuk melihat materials.
                </p>
            </div>
        @elseif($materials->count() > 0)
            <div class="space-y-4">
                @foreach($materials as $category => $topicGroups)
                    <div class="material-category" data-category="{{ $category }}">
                        <!-- Category Header -->
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <i class="fi fi-rr-graduation-cap mr-3 text-pink-500"></i>
                                {{ $category }} Materials
                            </h2>
                        </div>

                        <!-- Topics List -->
                        <div class="space-y-4">
                            @foreach($topicGroups as $topicName => $topicMaterials)
                                <div class="bg-[#211F27] rounded-lg overflow-hidden">
                                    <!-- Topic Header -->
                                    <div class="flex items-center justify-between p-4 border-l-4 border-pink-500">
                                        <div class="flex items-center space-x-3">
                                            <i class="fi fi-rr-book-alt text-pink-500"></i>
                                            <h3 class="text-xl font-bold text-white">{{ $topicName }}</h3>
                                            <span class="text-gray-400">({{ $topicMaterials->count() }} {{ Str::plural('Material', $topicMaterials->count()) }})</span>
                                        </div>
                                        <button onclick="window.toggleSubtopics('{{ str_replace(' ', '_', $topicName) }}')" class="text-gray-400 hover:text-white transition-colors">
                                            <i id="icon-{{ str_replace(' ', '_', $topicName) }}" class="fi fi-rr-angle-small-down transform transition-transform duration-200"></i>
                                        </button>
                                    </div>

                                    <!-- Subtopics Container -->
                                    <div id="subtopics-{{ str_replace(' ', '_', $topicName) }}" class="hidden">
                                        @php
                                            $subtopicGroups = $topicMaterials->groupBy(function($material) {
                                                return optional($material->subtopic)->name ?? 'General';
                                            });
                                        @endphp

                                        @foreach($subtopicGroups as $subtopicName => $materials)
                                            <div class="ml-8 p-4 bg-[#1a1a1f] rounded-lg mx-4 mb-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center space-x-3">
                                                        <i class="fi fi-rr-notebook text-pink-500"></i>
                                                        <h4 class="text-lg font-medium text-white">{{ $subtopicName }}</h4>
                                                    </div>
                                                    <span class="text-gray-400 text-sm">{{ $materials->count() }} {{ Str::plural('Material', $materials->count()) }}</span>
                                                </div>

                                                <!-- Material List -->
                                                <div class="space-y-3 mt-4">
                                                    @foreach($materials as $material)
                                                        <div class="flex items-center justify-between py-2 px-4 bg-[#211F27] rounded-lg hover:bg-[#2a2833] transition-all duration-300">
                                                            <div class="flex-grow">
                                                                <div class="flex items-center space-x-3">
                                                                    <h5 class="text-white font-medium">{{ $material->title }}</h5>
                                                                </div>
                                                                <p class="text-gray-400 text-sm mt-1">{{ $material->description ?: 'No description' }}</p>
                                                                @if($material->creator)
                                                                    <p class="text-gray-500 text-xs mt-1"><i class="fi fi-rr-user mr-1"></i>By {{ $material->creator->name }}</p>
                                                                @endif
                                                                <div class="flex items-center space-x-2 mt-2">
                                                                    <span class="text-xs text-gray-500">
                                                                        <i class="fi fi-rr-clock mr-1"></i>
                                                                        {{ $material->created_at->diffForHumans() }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center space-x-2">
                                                                <a href="{{ route('materials.show', $material->id) }}"
                                                                   class="px-3 py-1 text-pink-500 hover:text-white border border-pink-500 rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 transition-all">
                                                                    View Material
                                                                </a>
                                                                @if(auth()->user()->role !== 'student')
                                                                <button onclick="window.showSendToClassModal({{ $material->id }})" 
                                                                        class="px-3 py-1 text-pink-500 hover:text-white border border-pink-500 rounded hover:bg-gradient-to-r from-pink-500 to-orange-500 transition-all">
                                                                    <i class="fi fi-rr-paper-plane"></i>
                                                                </button>
                                                                <button onclick="window.editMaterial({{ $material->id }})" 
                                                                        class="px-3 py-1 text-blue-500 hover:text-white border border-blue-500 rounded hover:bg-gradient-to-r from-blue-500 to-blue-600 transition-all">
                                                                    Edit
                                                                </button>
                                                                <button onclick="window.deleteMaterial({{ $material->id }})" 
                                                                        class="px-3 py-1 text-red-500 hover:text-white border border-red-500 rounded hover:bg-gradient-to-r from-red-500 to-red-600 transition-all">
                                                                    Delete
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#211F27] rounded-lg shadow-lg border border-white/20 p-8 text-center">
                <div class="mb-4">
                    <i class="fi fi-rr-book-alt text-pink-500 text-5xl"></i>
                </div>
                <h3 class="text-white text-xl font-semibold mb-2">No Materials Found</h3>
                <p class="text-gray-400 mb-4">
                    @if(request('category'))
                        No materials found in the {{ request('category') }} category.
                    @else
                        Start by uploading your first material.
                    @endif
                </p>
                <a href="{{ route('materials.create') }}" 
                   class="inline-block px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Upload Material
                </a>
            </div>
        @endif

        <!-- Add Topic Modal -->
        @include('partials.add-topic-modal')

        <!-- Send to Class Modal -->
        <div id="sendToClassModal" class="fixed inset-0  bg-opacity-50 hidden items-center justify-center">
            <div class="bg-[#211F27] p-6 rounded-lg w-96 border-2 border-pink-500">
                <h2 class="text-xl font-bold text-white mb-4">Send to Classroom</h2>
                <div class="mb-4">
                    <label class="block text-gray-400 mb-2">Select Classroom</label>
                    <select id="classroomSelect" class="w-full bg-[#1a1a1f] text-white rounded p-2">
                        <!-- Will be populated via AJAX -->
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="window.closeSendToClassModal()" 
                            class="px-4 py-2 text-white border-2 border-pink-500 rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all duration-300">
                        Cancel
                    </button>
                    <button onclick="window.sendToClass()" 
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity duration-300">
                        Send Material
                    </button>
                </div>
            </div>
        </div>

        <!-- Material Details Modal -->
        <div id="materialModal" class="modal hidden fixed inset-0 bg-black bg-opacity-10 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="modal-content bg-[#211F27] rounded-lg shadow-xl border border-pink-500/20 w-full max-w-4xl mx-4 max-h-[80vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white" id="modalTitle"></h2>
                            <p class="text-gray-400 mt-2" id="modalDescription"></p>
                        </div>
                        <button onclick="window.closeMaterialModal()" class="text-gray-400 hover:text-white">
                            <i class="fi fi-rr-cross text-xl"></i>
                        </button>
                    </div>
                    <div id="modalContent" class="space-y-6">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Functions globalized
            window.filterMaterials = function(category) {
                const url = new URL(window.location.href);
                if (category === 'all') {
                    url.searchParams.delete('category');
                } else {
                    url.searchParams.set('category', category);
                }
                window.location.href = url.toString();
            }

            window.toggleSubtopics = function(topicId) {
                const subtopicsContainer = document.getElementById(`subtopics-${topicId}`);
                const icon = document.getElementById(`icon-${topicId}`);
                
                if (subtopicsContainer.classList.contains('hidden')) {
                    subtopicsContainer.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    subtopicsContainer.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                }
            }

            // All DOMContentLoaded related logic combined here
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM Content Loaded in materials/index.blade.php');

                // Initialize all subtopics as hidden
                const subtopicsContainers = document.querySelectorAll('[id^="subtopics-"]');
                subtopicsContainers.forEach(container => {
                    container.classList.add('hidden');
                });

                // Add smooth transition for rotate
                const icons = document.querySelectorAll('[id^="icon-"]');
                icons.forEach(icon => {
                    icon.style.transition = 'transform 0.2s ease-in-out';
                });

                // Functions for Add New Topic Modal
                window.openAddTopicModal = function() {
                    console.log('openAddTopicModal called');
                    document.getElementById('addTopicModal').classList.remove('hidden');
                    document.getElementById('addTopicModal').classList.add('flex');
                    // Pastikan subtopics-container selalu terlihat
                    document.getElementById('subtopics-container').classList.remove('hidden');
                    // Pasang ulang event listener setiap kali modal dibuka
                    const addSubtopicButton = document.getElementById('addSubtopicButton');
                    if (addSubtopicButton) {
                        addSubtopicButton.replaceWith(addSubtopicButton.cloneNode(true));
                        const newAddSubtopicButton = document.getElementById('addSubtopicButton');
                        newAddSubtopicButton.addEventListener('click', window.addSubtopicField);
                    }
                }

                window.closeAddTopicModal = function() {
                    console.log('closeAddTopicModal called');
                    document.getElementById('addTopicModal').classList.add('hidden');
                    document.getElementById('addTopicModal').classList.remove('flex');
                    document.getElementById('addTopicForm').reset();

                    const subtopicsContainer = document.getElementById('subtopics-container');
                    while (subtopicsContainer.children.length > 1) {
                        subtopicsContainer.removeChild(subtopicsContainer.lastChild);
                    }
                }

                window.addSubtopicField = function() {
                    console.log('addSubtopicField called');
                    const subtopicsContainer = document.getElementById('subtopics-container');
                    // Debug: log semua subtopics-container di DOM
                    console.log('All subtopics-container:', document.querySelectorAll('#subtopics-container'));
                    // Debug: log modal addTopicModal
                    console.log('addTopicModal:', document.getElementById('addTopicModal'));
                    // Debug: log parent dari subtopicsContainer
                    console.log('subtopicsContainer parent:', subtopicsContainer ? subtopicsContainer.parentElement : null);
                    // Hapus border debugging (jika ada)
                    if (subtopicsContainer) subtopicsContainer.style.border = '';
                    const newField = document.createElement('div');
                    newField.classList.add('flex', 'items-center', 'space-x-2', 'subtopic-item');
                    newField.innerHTML = `
                        <input type="text" name="subtopics[]" required
                            class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                            placeholder="Enter subtopic name">
                        <button type="button" onclick="window.removeSubtopicField(this)" class="text-red-500 hover:text-red-400 text-xl">
                            <i class="fi fi-rr-minus-circle"></i>
                        </button>
                    `;
                    if (subtopicsContainer) subtopicsContainer.appendChild(newField);
                    // Log isi container setelah append
                    console.log('subtopicsContainer children:', subtopicsContainer ? subtopicsContainer.children : null);
                }

                window.removeSubtopicField = function(buttonElement) {
                    console.log('removeSubtopicField called');
                    const subtopicsContainer = document.getElementById('subtopics-container');
                    if (subtopicsContainer.children.length > 1) {
                        buttonElement.closest('.subtopic-item').remove();
                    }
                }

                // Event listener for Add Subtopic button
                const addSubtopicButton = document.getElementById('addSubtopicButton');
                if (addSubtopicButton) {
                    console.log('addSubtopicButton found, attaching event listener.');
                    addSubtopicButton.addEventListener('click', window.addSubtopicField);
                } else {
                    console.log('addSubtopicButton NOT found.');
                }

                // Handle form submission for Add New Topic Modal
                document.getElementById('addTopicForm').addEventListener('submit', function(e) {
                    console.log('addTopicForm submitted');
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const category = formData.get('category');
                    const topicName = formData.get('name');
                    const subtopicInputs = document.querySelectorAll('input[name="subtopics[]"]');
                    const subtopics = Array.from(subtopicInputs).map(input => input.value.trim()).filter(value => value !== '');
                    
                    const finalCategory = category;

                    if (subtopics.length === 0) {
                        alert('At least one subtopic is required.');
                        return;
                    }

                    if (!finalCategory) {
                        alert('Please select a category.');
                        return;
                    }

                    fetch('/topics', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            name: topicName,
                            category: finalCategory,
                            subtopics: subtopics
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Topic added successfully.');
                            window.closeAddTopicModal();
                            window.location.reload();
                        } else {
                            alert('Failed to add topic: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error adding topic:', error);
                        alert('An error occurred while adding the topic');
                    });
                });

                // Functions for Send to Class Modal (already existing)
                const sendToClassModal = document.getElementById('sendToClassModal');
                const classroomSelect = document.getElementById('classroomSelect');
                let currentMaterialId = null;

                window.showSendToClassModal = function(materialId) {
                    console.log('showSendToClassModal called');
                    currentMaterialId = materialId;
                    sendToClassModal.classList.remove('hidden');
                    sendToClassModal.classList.add('flex');
                    window.loadClassrooms(); 
                }

                window.closeSendToClassModal = function() {
                    console.log('closeSendToClassModal called');
                    sendToClassModal.classList.add('hidden');
                    sendToClassModal.classList.remove('flex');
                    classroomSelect.innerHTML = '<option value="">Loading...</option>';
                }

                window.loadClassrooms = async function() {
                    console.log('loadClassrooms called');
                    try {
                        const response = await fetch('/get-classrooms');
                        if (!response.ok) {
                            throw new Error('Failed to fetch classrooms');
                        }
                        const classrooms = await response.json();
                        classroomSelect.innerHTML = '<option value="">Choose a classroom...</option>';
                        if (classrooms.length === 0) {
                            classroomSelect.innerHTML += `<option value="" disabled>No classrooms available</option>`;
                        }
                        classrooms.forEach(classroom => {
                            const option = document.createElement('option');
                            option.value = classroom.id;
                            option.textContent = classroom.name;
                            classroomSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error loading classrooms:', error);
                        classroomSelect.innerHTML = '<option value="">Error loading classrooms</option>';
                        alert('Failed to load classrooms. Please try again.');
                    }
                }

                window.sendToClass = async function() {
                    console.log('sendToClass called');
                    const classroomId = classroomSelect.value;
                    if (!classroomId) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Pilih Classroom',
                                text: 'Setidaknya harap memilih 1 classroom.',
                                icon: 'warning',
                                background: '#211F27',
                                color: '#fff',
                                confirmButtonColor: '#FF1493',
                                customClass: {
                                    popup: 'rounded-lg',
                                    title: 'font-bold',
                                    confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                    content: 'text-white'
                                }
                            });
                        } else {
                            alert('Setidaknya harap memilih 1 classroom.');
                        }
                        return;
                    }

                    try {
                        const response = await fetch('/materials/send-to-class', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ material_id: currentMaterialId, classroom_id: classroomId })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.closeSendToClassModal();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message || 'Materi berhasil dikirim ke classroom!',
                                    icon: 'success',
                                    background: '#211F27',
                                    color: '#fff',
                                    confirmButtonColor: '#FF1493',
                                    customClass: {
                                        popup: 'rounded-lg',
                                        title: 'font-bold',
                                        confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                        content: 'text-white'
                                    }
                                });
                            } else if (typeof window.showSuccessModal === 'function') {
                                window.showSuccessModal(data.message || 'Materi berhasil dikirim ke classroom!');
                            } else {
                                alert(data.message || 'Materi berhasil dikirim ke classroom!');
                            }
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Gagal',
                                    text: data.message || 'Failed to send material to classroom',
                                    icon: 'error',
                                    background: '#211F27',
                                    color: '#fff',
                                    confirmButtonColor: '#FF1493',
                                    customClass: {
                                        popup: 'rounded-lg',
                                        title: 'font-bold',
                                        confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                        content: 'text-white'
                                    }
                                });
                            } else {
                                alert('Failed to send material to classroom: ' + (data.message || 'Unknown error'));
                            }
                        }
                    } catch (error) {
                        console.error('Error sending material:', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while sending the material to classroom.',
                                icon: 'error',
                                background: '#211F27',
                                color: '#fff',
                                confirmButtonColor: '#FF1493',
                                customClass: {
                                    popup: 'rounded-lg',
                                    title: 'font-bold',
                                    confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                    content: 'text-white'
                                }
                            });
                        } else {
                            alert('An error occurred while sending the material to classroom.');
                        }
                    }
                }

                // Functions for Delete Material Confirmation Modal (already existing)
                const deleteMaterialModal = document.getElementById('deleteMaterialModal');
                let materialToDelete = null;

                window.deleteMaterial = function(materialId) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Hapus Material?',
                            text: 'Apakah Anda yakin ingin menghapus material ini? Tindakan ini tidak dapat dibatalkan.',
                            icon: 'warning',
                            showCancelButton: true,
                            background: '#211F27',
                            color: '#fff',
                            confirmButtonColor: '#FF1493',
                            cancelButtonColor: '#374151',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            customClass: {
                                popup: 'rounded-lg',
                                title: 'font-bold',
                                confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                cancelButton: 'px-6 py-2 rounded-lg text-white bg-gray-600 hover:bg-gray-700 transition-opacity',
                                content: 'text-white'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.confirmDelete(materialId);
                            }
                        });
                    } else {
                        if (confirm('Apakah Anda yakin ingin menghapus material ini?')) {
                            window.confirmDelete(materialId);
                        }
                    }
                }

                window.confirmDelete = async function(materialId) {
                    try {
                        const response = await fetch(`/materials/${materialId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            // Hapus card dari DOM jika ada id khusus, atau reload
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Material deleted successfully!',
                                icon: 'success',
                                background: '#211F27',
                                color: '#fff',
                                confirmButtonColor: '#FF1493',
                                customClass: {
                                    popup: 'rounded-lg',
                                    title: 'font-bold',
                                    confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                    content: 'text-white'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message || 'Gagal menghapus material',
                                icon: 'error',
                                background: '#211F27',
                                color: '#fff',
                                confirmButtonColor: '#FF1493',
                                customClass: {
                                    popup: 'rounded-lg',
                                    title: 'font-bold',
                                    confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                    content: 'text-white'
                                }
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            title: 'Error!',
                            text: error.message || 'Terjadi kesalahan',
                            icon: 'error',
                            background: '#211F27',
                            color: '#fff',
                            confirmButtonColor: '#FF1493',
                            customClass: {
                                popup: 'rounded-lg',
                                title: 'font-bold',
                                confirmButton: 'px-6 py-2 rounded-lg text-white bg-gradient-to-r from-pink-500 to-orange-500 hover:opacity-90 transition-opacity',
                                content: 'text-white'
                            }
                        });
                    }
                }

                // Functions for Material Details Modal (already existing)
                const materialModal = document.getElementById('materialModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalDescription = document.getElementById('modalDescription');
                const modalContent = document.getElementById('modalContent');

                window.viewMaterial = async function(materialId) {
                    console.log('viewMaterial called');
                    try {
                        const response = await fetch(`/materials/${materialId}`);
                        if (!response.ok) {
                            throw new Error('Failed to fetch material details');
                        }
                        const material = await response.json();

                        modalTitle.textContent = material.title;
                        modalDescription.textContent = material.description || 'No description';
                        modalContent.innerHTML = ''; // Clear previous content

                        if (material.type === 'youtube') {
                            modalContent.innerHTML += `<div class="aspect-video w-full"><iframe src="https://www.youtube.com/embed/${material.youtube_id}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full rounded-lg"></iframe></div>`;
                        } else if (material.type === 'file') {
                            modalContent.innerHTML += `<embed src="/storage/${material.file_path}" type="application/pdf" width="100%" height="500px" class="rounded-lg" />`;
                        }

                        materialModal.classList.remove('hidden');
                        materialModal.classList.add('flex');
                    } catch (error) {
                        console.error('Error fetching material details:', error);
                        alert('Failed to load material details. Please try again.');
                    }
                }

                window.closeMaterialModal = function() {
                    console.log('closeMaterialModal called');
                    materialModal.classList.add('hidden');
                    materialModal.classList.remove('flex');
                    modalContent.innerHTML = ''; // Clear content on close
                }

                // Close modal when clicking outside
                materialModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        window.closeMaterialModal();
                    }
                });

                // Prevent closing when clicking inside modal content
                materialModal.querySelector('.modal-content').addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Tambahkan fungsi editMaterial agar tombol Edit berfungsi
                window.editMaterial = function(materialId) {
                    window.location.href = `/materials/${materialId}/edit`;
                }
            });
        </script>
        @endpush
    </div>
</x-layout> 