<x-layout>
  <div class="pt-3">
    {{-- Toast Notification --}}
    <div id="toast-notification" class="hidden fixed top-4 right-4 bg-pink-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span id="toast-message"></span>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->role === 'student')
        {{-- Student Home Page --}}
        <div class="container mx-auto px-8 py-6 max-w-[1200px]">
            <p class="text-gray-400 text-lg mb-2">Welcome, {{ Auth::user()->name }},</p>
            <p class="text-white text-2xl font-semibold mb-6">Enthusiasm for learning.</p>
            <h1 class="text-gray-400 text-2xl font-semibold mb-6">Find Your Classroom</h1>
            <div class="mb-6 relative">
                <div class="flex items-center border border-gray-700 rounded-lg bg-[#211F27] focus-within:border-pink-500">
                    <div class="px-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="classroom-search-input" placeholder="Search classroom by name..." 
                           class="w-full p-3 bg-transparent text-white focus:outline-none">
                    <button id="classroom-search-button" class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-5 py-3 rounded-r-lg hover:opacity-90 transition-opacity">
                        Find
                    </button>
                </div>
            </div>
            
            <div id="classroom-suggestions" class="space-y-2 mb-4"></div>
            <div id="classroom-specific-results" class="space-y-4"></div>
        </div>
        <!-- Latest Exercise Section -->
        @if(isset($latestExercises) && count($latestExercises) > 0)
        <div id="latest-exercise-section" class="container mx-auto px-8 py-6 max-w-[1200px]">
            <h2 class="text-xl font-bold text-pink-400 mb-4">Latest Exercise</h2>
            <div class="space-y-6">
                @foreach($latestExercises as $exercise)
                <div class="bg-[#211F27] rounded-xl shadow p-6 flex flex-col w-full max-w-none">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $exercise->title }}</h3>
                    <p class="text-gray-300 text-base mb-2">{{ $exercise->description }}</p>
                    <div class="flex items-center text-xs text-gray-400 mb-2">
                        <span class="mr-4"><i class="fi fi-rr-calendar"></i> {{ $exercise->created_at->format('d M Y') }}</span>
                        <span class="mr-4"><i class="fi fi-rr-user"></i> By {{ $exercise->creator->name ?? '-' }}</span>
                    </div>
                    <a href="/exercises/{{ $exercise->id }}/take" class="mt-auto bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Take Exercise</a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <!-- Latest Materials Section -->
        @if(isset($latestMaterials) && count($latestMaterials) > 0)
        <div id="latest-materials-section" class="container mx-auto px-8 py-6 max-w-[1200px]">
            <h2 class="text-xl font-bold text-orange-400 mb-4">Latest Materials</h2>
            <div class="space-y-6">
                @foreach($latestMaterials as $material)
                <div class="bg-[#211F27] rounded-xl shadow p-6 flex flex-col w-full max-w-none">
                    <h3 class="text-2xl font-bold text-white mb-2">{{ $material->title }}</h3>
                    <p class="text-gray-300 text-base mb-2">{{ $material->description }}</p>
                    <div class="flex items-center text-xs text-gray-400 mb-2">
                        <span class="mr-4"><i class="fi fi-rr-calendar"></i> {{ $material->created_at->format('d M Y') }}</span>
                    </div>
                    @if($material->file_path)
                    <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" class="mt-auto bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600">View Material</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <!-- News Section -->
        <div id="news-section" class="container mx-auto px-8 py-6 max-w-[1200px]">
            <h2 class="text-xl font-bold text-gray-200 mb-4">Latest News</h2>
            <div id="news-list" class="space-y-6"></div>
        </div>
        <script>
        // Student classroom search logic
        (function() {
            let debounceTimer;
            const input = document.getElementById('classroom-search-input');
            const suggestionsDiv = document.getElementById('classroom-suggestions');
            const resultsDiv = document.getElementById('classroom-specific-results');
            const button = document.getElementById('classroom-search-button');

            function renderSuggestions(classrooms) {
                suggestionsDiv.innerHTML = '';
                if (classrooms.length > 0) {
                    classrooms.forEach(classroom => {
                        suggestionsDiv.innerHTML += `
                            <div class="bg-[#211F27] p-3 rounded-lg flex items-center justify-between cursor-pointer hover:bg-[#2A2833]" 
                                 onclick="selectClassroomSuggestion('${classroom.name}')">
                                <p class="text-white font-medium">${classroom.name}</p>
                            </div>
                        `;
                    });
                } else {
                    suggestionsDiv.innerHTML = '<p class="text-gray-500 text-center text-sm">No suggestions found.</p>';
                }
            }

            function renderResults(classrooms, query) {
                resultsDiv.innerHTML = '';
                if (classrooms.length > 0) {
                    // Show exact match if available
                    const exactMatch = classrooms.find(c => c.name.toLowerCase() === query.toLowerCase());
                    if (exactMatch) {
                        resultsDiv.innerHTML += `
                            <div class="bg-[#211F27] p-4 rounded-lg flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="text-white font-bold text-lg">${exactMatch.name}</h3>
                                    <p class="text-gray-400 text-sm">${exactMatch.description ?? ''}</p>
                                </div>
                                <button onclick="openPasswordModal('${exactMatch.name}')" 
                                        class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                    Enter Class
                                </button>
                            </div>
                        `;
                    } else {
                        resultsDiv.innerHTML = '<p class="text-gray-500 text-center">No exact match found. Try a different name or check suggestions above.</p>';
                    }
                } else {
                    resultsDiv.innerHTML = '<p class="text-gray-500 text-center">No classrooms found.</p>';
                }
            }

            window.selectClassroomSuggestion = function(className) {
                input.value = className;
                suggestionsDiv.innerHTML = '';
                button.click();
            };

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();
                resultsDiv.innerHTML = '';
                if (query.length < 2) {
                    suggestionsDiv.innerHTML = '';
                    return;
                }
                debounceTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`/search-classroom?query=${encodeURIComponent(query)}`);
                        const classrooms = await response.json();
                        renderSuggestions(classrooms);
                    } catch (error) {
                        suggestionsDiv.innerHTML = '<p class="text-red-500 text-center text-sm">Error loading suggestions.</p>';
                    }
                }, 300);
            });

            button.addEventListener('click', async function() {
                const query = input.value.trim();
                suggestionsDiv.innerHTML = '';
                if (query.length < 1) {
                    resultsDiv.innerHTML = '';
                    return;
                }
                try {
                    const response = await fetch(`/search-classroom?query=${encodeURIComponent(query)}`);
                    const classrooms = await response.json();
                    renderResults(classrooms, query);
                } catch (error) {
                    resultsDiv.innerHTML = '<p class="text-red-500 text-center">Error searching classroom.</p>';
                }
            });
        })();

        // News fetch & like logic
        document.addEventListener('DOMContentLoaded', function() {
            const newsList = document.getElementById('news-list');
            let newsData = [];
            function renderNews(news) {
                newsList.innerHTML = '';
                if(news.length === 0) {
                    newsList.innerHTML = '<div class="text-gray-400 text-center">No news available.</div>';
                    return;
                }
                news.forEach(item => {
                    const descId = `desc-${item.id}`;
                    const btnId = `btn-${item.id}`;
                    newsList.innerHTML += `
                    <div class="bg-[#211F27] rounded-xl shadow p-6 flex flex-col w-full max-w-none">
                        <img src="${item.image ? '/storage/' + item.image : '/img/Logo.png'}" alt="" class="w-full h-48 object-cover rounded mb-4">
                        <h3 class="text-2xl font-bold text-white mb-2">${item.title}</h3>
                        <p id="${descId}" class="text-gray-300 text-base mb-2 line-clamp-2">${item.description}</p>
                        <button id="${btnId}" class="text-pink-400 underline text-sm mb-2 self-start">Read More</button>
                        <div class="flex items-center text-xs text-gray-400 mb-2">
                            <span class="mr-4"><i class="fi fi-rr-calendar"></i> ${new Date(item.date).toLocaleDateString()}</span>
                            <span class="mr-4"><i class="fi fi-rr-eye"></i> <span id="views-${item.id}">${item.views}</span> views</span>
                            <span><i class="fi fi-rr-heart"></i> <span id="likes-${item.id}">${item.likes}</span> likes</span>
                        </div>
                        <button onclick="likeNews(${item.id})" class="mt-auto bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">Like</button>
                    </div>
                    `;
                });
                // Add read more logic
                news.forEach(item => {
                    const desc = document.getElementById(`desc-${item.id}`);
                    const btn = document.getElementById(`btn-${item.id}`);
                    if(desc && btn) {
                        btn.addEventListener('click', function() {
                            if(desc.classList.contains('line-clamp-2')) {
                                desc.classList.remove('line-clamp-2');
                                btn.textContent = 'Show Less';
                            } else {
                                desc.classList.add('line-clamp-2');
                                btn.textContent = 'Read More';
                            }
                        });
                    }
                });
            }
            async function fetchNews() {
                const res = await fetch('/news/list');
                newsData = await res.json();
                renderNews(newsData);
                // Increment view for all news (once per page load)
                newsData.forEach(item => {
                    fetch(`/news/${item.id}/increment-view`, {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('views-' + item.id).textContent = data.views;
                        });
                });
            }
            window.likeNews = function(id) {
                fetch(`/news/${id}/like`, {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('likes-' + id).textContent = data.likes;
                    });
            }
            fetchNews();
        });
        </script>
    @else
        {{-- Teacher/Admin Home Page --}}
        <div>
            <div class="Container-judul flex justify-center items-center">
                <div class="px-36 flex pt-25">
                    <div class="w-[500px] space-y-2">
                        <p class="text-gray-400">Welcome to Englicious</p>
                        <h1 class="text-white font-bold text-5xl">{{ auth()->user()->name}}</h1>
                        <p class="text-gray-400">you are a {{ auth()->user()->role }} in Englicious, Happy teaching</p>

                        <div class="space-x-2">
                            <button onclick="window.location.href='/classroom'" class="px-2 mt-6 bg-gradient-to-r from-pink-500 to-orange-500 w-25 h-10 text-white rounded-lg hover:bg-gradient-to-l from-pink-500 to-orange-500">Enter Class</button>
                            <button onclick="openModal()" class="px-2 mt-6 border-2 w-auto h-10 rounded-lg text-white hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-none">Create Classroom</button>
                        </div>
                    </div>

                    <div class="gambar ">
                        <img src="img/12.png" alt="" class="w-[500px] h-auto">
                    </div>
                </div>
            </div>

            {{-- Classroom list --}}
            <div class="container-Room px-8 py-6 max-w-[1200px] mx-auto">
                <h1 class="text-gray-400 text-2xl font-semibold mb-6">Your Classroom</h1>
                <div class="relative">
                    <div class="overflow-x-auto no-scrollbar">
                        <div class="flex gap-6 pb-4" id="your-classroom-list">
                            @if($classrooms->count() > 0)
                                @foreach($classrooms as $classroom)
                                    @if(Auth::user()->role === 'admin' || Auth::id() === $classroom->teacher_id)
                                        <div id="classroom-{{ $classroom->id }}" class="flex-none w-[280px] bg-[#211F27] rounded-xl hover:border-2 hover:border-pink-500 transition-all duration-200">
                                            <div class="p-5 flex flex-col h-[200px]">
                                                <div class="flex-1">
                                                    <h3 class="text-xl font-bold text-white mb-2 truncate">{{ $classroom->name }}</h3>
                                                    <p class="text-gray-400 text-sm line-clamp-2">{{ $classroom->description }}</p>
                                                </div>
                                                <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                                    <button onclick="openPasswordModal('{{ $classroom->name }}')" 
                                                            class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                                        Open Class
                                                    </button>
                                                    @if(Auth::id() === $classroom->teacher_id || (Auth::user() && Auth::user()->role === 'admin'))
                                                        <button onClick="openDeleteModal({{ $classroom->id }}, '{{ $classroom->name }}')" 
                                                                class="text-pink-500 hover:text-pink-400 font-medium transition-colors px-3">
                                                            Delete
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="w-full text-center py-8">
                                    <div class="mb-4">
                                        <i class="fi fi-rr-book-alt text-pink-500 text-5xl"></i>
                                    </div>
                                    <h3 class="text-white text-xl font-semibold mb-2">Classroom not available</h3>
                                    <p class="text-gray-400 mb-4">Please make a classroom to get started</p>
                                    <button onclick="openModal()" 
                                            class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                        Create Classroom
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Exercise Shortcuts --}}
            <div class="container-exercises px-8 py-6 max-w-[1200px] mx-auto">
                <h1 class="text-gray-400 text-2xl font-semibold mb-6">Quick Exercise Access</h1>
                <div class="relative">
                    <div class="overflow-x-auto no-scrollbar">
                        <div class="flex gap-6 pb-4">
                            @php
                                $exercises = App\Models\Exercise::latest()->take(10)->get();
                            @endphp

                            @if($exercises->count() > 0)
                                @foreach($exercises as $exercise)
                                    <div class="flex-none w-[280px] bg-[#211F27] rounded-xl hover:border-2 hover:border-pink-500 transition-all duration-200">
                                        <div class="p-5 flex flex-col h-[200px]">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <i class="fi fi-rr-pencil text-pink-500"></i>
                                                    <span class="text-sm text-pink-500">{{ $exercise->category }}</span>
                                                </div>
                                                <h3 class="text-xl font-bold text-white mb-2 truncate">{{ $exercise->title }}</h3>
                                                <p class="text-gray-400 text-sm line-clamp-2">{{ $exercise->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                                @if($exercise->is_file_upload)
                                                    <a href="/preview/{{ $exercise->id }}" 
                                                       class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                                        View PDF
                                                    </a>
                                                @else
                                                    <a href="/exercises/{{ $exercise->id }}/take" 
                                                       class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                                        Take Exercise
                                                    </a>
                                                @endif
                                                <a href="{{ route('exercises.index') }}" 
                                                   class="text-pink-500 hover:text-pink-400 font-medium transition-colors px-3">
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="w-full text-center py-8">
                                    <div class="mb-4">
                                        <i class="fi fi-rr-pencil text-pink-500 text-5xl"></i>
                                    </div>
                                    <h3 class="text-white text-xl font-semibold mb-2">No exercises available</h3>
                                    <p class="text-gray-400 mb-4">Start by creating your first exercise</p>
                                    <a href="{{ route('exercises.create') }}" 
                                       class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-6 py-2 rounded-lg hover:opacity-90 transition-opacity inline-block">
                                        Create Exercise
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal" class="hidden fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
                <div class="bg-white dark:bg-[#211F27] p-6 rounded-lg shadow-lg w-full max-w-md border-1 border-pink-500">
                    <h2 class="text-2xl font-semibold text-white mb-4">Add New Classroom</h2>
                
                    {{-- ALERT AREA --}}
                    <div id="form-alert" class="mb-3 hidden"><h1></h1></div>
                
                    <form id="add-classroom-form">
                        @csrf
                        <input type="text" name="class_name" placeholder="Class Name" required class="w-full mb-3 p-2 rounded border border-gray-400 text-white bg-[#211F27]">
                        <input type="text" name="description" placeholder="Description" required class="w-full mb-3 p-2 rounded border border-gray-400 text-white bg-[#211F27]">
                        <input type="password" name="password" placeholder="Password (max 8 characters)" maxlength="8" required class="w-full mb-3 p-2 rounded border border-gray-400 text-white bg-[#2A2833]">
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeModal()" class="px-4 py-2 border-1 border-pink-500 text-pink-500 hover:bg-red-500 hover:text-white hover:border-0 rounded">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Add</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    @endif
  </div>

  <!-- Password Verification Modal -->
  <div id="password-modal" class="hidden fixed inset-0 flex items-center justify-center bg-opacity-50 z-50">
      <div class="bg-white dark:bg-[#211F27] p-6 rounded-lg shadow-lg w-full max-w-sm border border-pink-500">
          <h2 class="text-xl font-semibold text-white mb-4">Enter Classroom Password</h2>
          <p class="text-gray-400 mb-4" id="password-modal-classroom-name"></p>
          <form id="password-form" method="POST" action="#" onsubmit="return false;">
              @csrf
              <input type="hidden" id="password-classroom-name-hidden" name="className">
              <input type="password" id="classroom-password-input" name="password" placeholder="Password" required
                     class="w-full mb-4 p-2 rounded border border-gray-700 text-white bg-[#2A2833] focus:border-pink-500 focus:outline-none">
              <div id="password-error-message" class="text-red-500 text-sm mb-4 hidden"></div>
              <div class="flex justify-end space-x-3">
                  <button type="button" onclick="closePasswordModal()" class="px-4 py-2 border-2 border-pink-500 text-pink-500 hover:bg-red-500 hover:text-white hover:border-0 rounded">Cancel</button>
                  <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Enter</button>
              </div>
          </form>
      </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="delete-modal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div class="bg-white dark:bg-[#211F27] p-6 rounded-lg shadow-lg w-full max-w-sm border border-pink-500">
          <div class="flex items-center mb-4">
              <div class="flex-shrink-0">
                  <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                  </svg>
              </div>
              <div class="ml-3">
                  <h3 class="text-lg font-semibold text-white">Delete Classroom</h3>
              </div>
          </div>
          <p class="text-gray-400 mb-6">Are you sure you want to delete this classroom? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
              <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 border-2 border-pink-500 text-pink-500 hover:bg-gray-600 hover:text-white hover:border-0 rounded transition-colors">Cancel</button>
              <button type="button" id="confirm-delete-btn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">Delete</button>
          </div>
      </div>
  </div>

  @push('scripts')
  <script>
      // Global variables
      let globalSearchDebounceTimer;
      let currentClassroomName = null;
      let currentClassroomId = null;

      // Moved general modal functions outside of role-specific blocks
      function openPasswordModal(className) {
          currentClassroomName = className;
          document.getElementById('password-modal-classroom-name').textContent = `for ${className}`;
          document.getElementById('password-classroom-name-hidden').value = className;
          document.getElementById('password-modal').classList.remove('hidden');
          document.getElementById('classroom-password-input').focus();
      }

      function closePasswordModal() {
          document.getElementById('password-modal').classList.add('hidden');
          document.getElementById('classroom-password-input').value = '';
          document.getElementById('password-error-message').classList.add('hidden');
      }

      function openDeleteModal(classroomId, className) {
          currentClassroomId = classroomId;
          currentClassroomName = className;
          document.getElementById('delete-modal').classList.remove('hidden');
      }

      function closeDeleteModal() {
          document.getElementById('delete-modal').classList.add('hidden');
          currentClassroomId = null;
          currentClassroomName = null;
      }

      // Add the missing deleteClassroom function
      function deleteClassroom(classroomId) {
          fetch(`/classroom/delete/${classroomId}`, {
              method: 'DELETE',
              headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                  'Accept': 'application/json',
              }
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  // Remove the classroom card from the DOM
                  const classroomElement = document.getElementById(`classroom-${classroomId}`);
                  if (classroomElement) {
                      classroomElement.remove();
                  }
                  showToast('Classroom deleted successfully');
              } else {
                  showToast(data.message || 'Failed to delete classroom', 'error');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              showToast('An error occurred while deleting the classroom', 'error');
          });
      }

      // Set up delete confirmation button
      const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
      if (confirmDeleteBtn) {
          confirmDeleteBtn.addEventListener('click', function() {
              if (currentClassroomId) {
                  deleteClassroom(currentClassroomId);
                  closeDeleteModal();
              }
          });
      }

      // Password form event listener with null check
      const passwordForm = document.getElementById('password-form');
      if (passwordForm) {
          passwordForm.addEventListener('submit', async function(e) {
              e.preventDefault();

              const form = e.target;
              const formData = new FormData(form);
              const password = formData.get('password');
              const className = formData.get('className');
              const errorMessageDiv = document.getElementById('password-error-message');

              try {
                  const response = await fetch(`/classroom/verify-password/${className}`, {
                      method: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                          'Accept': 'application/json',
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({ password: password })
                  });

                  const data = await response.json();

                  if (response.ok && data.success) {
                      closePasswordModal();
                      window.location.href = `/classroom/${className}`;
                  } else {
                      errorMessageDiv.textContent = data.message || 'Incorrect password';
                      errorMessageDiv.classList.remove('hidden');
                  }
              } catch (error) {
                  console.error('Error:', error);
                  errorMessageDiv.textContent = 'An error occurred. Please try again.';
                  errorMessageDiv.classList.remove('hidden');
              }
          });
      }

      // Global Search Functionality
      const globalSearchInput = document.getElementById('global-search-input');
      if (globalSearchInput) {
          globalSearchInput.addEventListener('input', function() {
              clearTimeout(globalSearchDebounceTimer);
              const query = this.value;
              const suggestionsDiv = document.getElementById('global-search-suggestions');
              const resultsDiv = document.getElementById('global-search-results');

              resultsDiv.innerHTML = '';

              if (query.length < 2) {
                  suggestionsDiv.innerHTML = '';
                  return;
              }

              globalSearchDebounceTimer = setTimeout(async () => {
                  try {
                      console.log('Searching for:', query);
                      const response = await fetch(`/global-search?query=${encodeURIComponent(query)}`);
                      
                      if (!response.ok) {
                          throw new Error(`HTTP error! status: ${response.status}`);
                      }
                      
                      const results = await response.json();
                      console.log('Search results:', results);

                      suggestionsDiv.innerHTML = '';
                      if (results.classrooms && results.classrooms.length > 0 || 
                          results.exercises && results.exercises.length > 0 || 
                          results.materials && results.materials.length > 0) {
                          
                          if (results.classrooms && results.classrooms.length > 0) {
                              suggestionsDiv.innerHTML += '<div class="text-pink-500 text-sm font-medium mb-2">Classrooms:</div>';
                              results.classrooms.forEach(classroom => {
                                  suggestionsDiv.innerHTML += `
                                      <div class="bg-[#211F27] p-3 rounded-lg flex items-center justify-between cursor-pointer hover:bg-[#2A2833]" 
                                           onclick="selectGlobalSearchSuggestion('${classroom.name}')">
                                          <div class="flex items-center space-x-2">
                                              <i class="fi fi-rr-book-alt text-pink-500"></i>
                                              <p class="text-white font-medium">${classroom.name}</p>
                                          </div>
                                          <span class="text-gray-400 text-xs">Classroom</span>
                                      </div>
                                  `;
                              });
                          }

                          if (results.exercises && results.exercises.length > 0) {
                              suggestionsDiv.innerHTML += '<div class="text-pink-500 text-sm font-medium mb-2 mt-4">Exercises:</div>';
                              results.exercises.forEach(exercise => {
                                  suggestionsDiv.innerHTML += `
                                      <div class="bg-[#211F27] p-3 rounded-lg flex items-center justify-between cursor-pointer hover:bg-[#2A2833]" 
                                           onclick="selectGlobalSearchSuggestion('${exercise.title}')">
                                          <div class="flex items-center space-x-2">
                                              <i class="fi fi-rr-pencil text-pink-500"></i>
                                              <p class="text-white font-medium">${exercise.title}</p>
                                          </div>
                                          <span class="text-gray-400 text-xs">Exercise</span>
                                      </div>
                                  `;
                              });
                          }

                          if (results.materials && results.materials.length > 0) {
                              suggestionsDiv.innerHTML += '<div class="text-pink-500 text-sm font-medium mb-2 mt-4">Materials:</div>';
                              results.materials.forEach(material => {
                                  suggestionsDiv.innerHTML += `
                                      <div class="bg-[#211F27] p-3 rounded-lg flex items-center justify-between cursor-pointer hover:bg-[#2A2833]" 
                                           onclick="selectGlobalSearchSuggestion('${material.title}')">
                                          <div class="flex items-center space-x-2">
                                              <i class="fi fi-rr-document text-pink-500"></i>
                                              <p class="text-white font-medium">${material.title}</p>
                                          </div>
                                          <span class="text-gray-400 text-xs">Material</span>
                                      </div>
                                  `;
                              });
                          }
                      } else {
                          suggestionsDiv.innerHTML = '<p class="text-gray-500 text-center text-sm">No suggestions found.</p>';
                      }
                  } catch (error) {
                      console.error('Error searching suggestions:', error);
                      suggestionsDiv.innerHTML = `<p class="text-red-500 text-center text-sm">Error loading suggestions: ${error.message}</p>`;
                  }
              }, 300);
          });
      }

      function selectGlobalSearchSuggestion(name) {
          const searchInput = document.getElementById('global-search-input');
          const suggestionsDiv = document.getElementById('global-search-suggestions');
          
          searchInput.value = name;
          suggestionsDiv.innerHTML = '';
          document.getElementById('global-search-button').click();
      }

      const globalSearchButton = document.getElementById('global-search-button');
      if (globalSearchButton) {
          globalSearchButton.addEventListener('click', async function() {
              const query = document.getElementById('global-search-input').value;
              const resultsDiv = document.getElementById('global-search-results');
              const suggestionsDiv = document.getElementById('global-search-suggestions');

              suggestionsDiv.innerHTML = '';

              if (query.length < 1) {
                  resultsDiv.innerHTML = '';
                  return;
              }

              try {
                  console.log('Performing search for:', query);
                  const response = await fetch(`/global-search?query=${encodeURIComponent(query)}`);
                  
                  if (!response.ok) {
                      throw new Error(`HTTP error! status: ${response.status}`);
                  }
                  
                  const results = await response.json();
                  console.log('Search results:', results);

                  resultsDiv.innerHTML = '';
                  
                  if (results.classrooms && results.classrooms.length > 0 || 
                      results.exercises && results.exercises.length > 0 || 
                      results.materials && results.materials.length > 0) {
                      
                      if (results.classrooms && results.classrooms.length > 0) {
                          resultsDiv.innerHTML += '<h3 class="text-white text-lg font-semibold mb-4">Classrooms</h3>';
                          results.classrooms.forEach(classroom => {
                              resultsDiv.innerHTML += `
                                  <div class="bg-[#211F27] p-4 rounded-lg flex items-center justify-between mb-3">
                                      <div class="flex-1">
                                          <div class="flex items-center space-x-2 mb-2">
                                              <i class="fi fi-rr-book-alt text-pink-500"></i>
                                              <h4 class="text-white font-bold text-lg">${classroom.name}</h4>
                                          </div>
                                          <p class="text-gray-400 text-sm">${classroom.description}</p>
                                      </div>
                                      <button onclick="openPasswordModal('${classroom.name}')" 
                                              class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                          Enter Class
                                      </button>
                                  </div>
                              `;
                          });
                      }

                      if (results.exercises && results.exercises.length > 0) {
                          resultsDiv.innerHTML += '<h3 class="text-white text-lg font-semibold mb-4 mt-6">Exercises</h3>';
                          results.exercises.forEach(exercise => {
                              const actionButton = exercise.is_file_upload 
                                  ? `<a href="/preview/${exercise.id}" class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">View PDF</a>`
                                  : `<a href="/exercises/${exercise.id}/take" class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">Take Exercise</a>`;
                                  
                              resultsDiv.innerHTML += `
                                  <div class="bg-[#211F27] p-4 rounded-lg flex items-center justify-between mb-3">
                                      <div class="flex-1">
                                          <div class="flex items-center space-x-2 mb-2">
                                              <i class="fi fi-rr-pencil text-pink-500"></i>
                                              <h4 class="text-white font-bold text-lg">${exercise.title}</h4>
                                              <span class="text-pink-500 text-xs bg-pink-500 bg-opacity-20 px-2 py-1 rounded">${exercise.category}</span>
                                          </div>
                                          <p class="text-gray-400 text-sm">${exercise.description}</p>
                                      </div>
                                      ${actionButton}
                                  </div>
                              `;
                          });
                      }

                      if (results.materials && results.materials.length > 0) {
                          resultsDiv.innerHTML += '<h3 class="text-white text-lg font-semibold mb-4 mt-6">Materials</h3>';
                          results.materials.forEach(material => {
                              resultsDiv.innerHTML += `
                                  <div class="bg-[#211F27] p-4 rounded-lg flex items-center justify-between mb-3">
                                      <div class="flex-1">
                                          <div class="flex items-center space-x-2 mb-2">
                                              <i class="fi fi-rr-document text-pink-500"></i>
                                              <h4 class="text-white font-bold text-lg">${material.title}</h4>
                                          </div>
                                          <p class="text-gray-400 text-sm">${material.description}</p>
                                      </div>
                                      <a href="/materials/${material.id}/show" 
                                         class="bg-gradient-to-r from-pink-500 to-orange-500 text-white px-4 py-2 rounded-lg hover:opacity-90 transition-opacity">
                                          View Material
                                      </a>
                                  </div>
                              `;
                          });
                      }
                  } else {
                      resultsDiv.innerHTML = '<p class="text-gray-500 text-center">No results found.</p>';
                  }
              } catch (error) {
                  console.error('Error searching:', error);
                  resultsDiv.innerHTML = `<p class="text-red-500 text-center">Error searching: ${error.message}</p>`;
              }
          });
      }

      // Tambahkan fungsi openModal dan closeModal jika belum ada
      function openModal() {
          document.getElementById('modal').classList.remove('hidden');
      }
      function closeModal() {
          document.getElementById('modal').classList.add('hidden');
      }

  </script>
  @endpush

  @push('styles')
  <style>
    .no-scrollbar::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Webkit browsers */
    }

    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
  </style>
  @endpush
</x-layout>