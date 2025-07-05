<script>
function filterExercises(category) {
    window.location.href = `{{ route('exercises.index') }}${category === 'all' ? '' : '?category=' + category}`;
}

function toggleSubtopics(topicId) {
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

function viewExercise(exerciseId) {
    window.location.href = `/exercises/${exerciseId}/take`;
}

function editExercise(id) {
    window.location.href = `/exercises/${id}/edit`;
}

function deleteExercise(exerciseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF1493',
        cancelButtonColor: '#718096',
        confirmButtonText: 'Yes, delete it!',
        background: '#211F27',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/exercises/${exerciseId}/delete`;
        }
    });
}

function openAddTopicModal(category) {
    document.getElementById('topicCategory').value = category;
    document.getElementById('addTopicModal').classList.remove('hidden');
}

function closeAddTopicModal() {
    document.getElementById('addTopicModal').classList.add('hidden');
}

function openAddSubtopicModal(category, topicName) {
    // Get the topic ID based on the topic name
    fetch(`/api/topics/get-id/${encodeURIComponent(topicName)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('subtopicTopicId').value = data.id;
                document.getElementById('addSubtopicModal').classList.remove('hidden');
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Topic not found',
                    icon: 'error',
                    background: '#211F27',
                    color: '#fff',
                    confirmButtonColor: '#FF1493'
                });
            }
        })
        .catch(error => {
            console.error('Error fetching topic ID:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to fetch topic information',
                icon: 'error',
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493'
            });
        });
}

function closeAddSubtopicModal() {
    document.getElementById('addSubtopicModal').classList.add('hidden');
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        closeAddTopicModal();
        closeAddSubtopicModal();
        closeSendToClassModal();
    }
});

// Handle form submissions with SweetAlert2
document.getElementById('addTopicForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to add topic');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error',
            text: error.message,
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    });
});

document.getElementById('addSubtopicForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to add subtopic');
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error',
            text: error.message,
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    });
});

let selectedExerciseId = null;

async function loadClassrooms() {
    try {
        const response = await fetch('/get-classrooms', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch classrooms');
        }
        
        const classrooms = await response.json();
        const select = document.getElementById('classroomSelect');
        select.innerHTML = '<option value="">Choose a classroom...</option>';
        
        if (classrooms.length === 0) {
            select.innerHTML += `<option value="" disabled>No classrooms available</option>`;
            return;
        }

        classrooms.forEach(classroom => {
            select.innerHTML += `
                <option value="${classroom.id}" class="py-2">
                    ${classroom.name}
                </option>`;
        });
    } catch (error) {
        console.error('Error loading classrooms:', error);
        Swal.fire({
            title: 'Error',
            text: 'Failed to load classrooms. Please try again.',
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    }
}

function showSendToClassModal(exerciseId) {
    selectedExerciseId = exerciseId;
    loadClassrooms();
    document.getElementById('sendToClassModal').classList.remove('hidden');
}

function closeSendToClassModal() {
    document.getElementById('sendToClassModal').classList.add('hidden');
    selectedExerciseId = null;
}

async function sendToClass() {
    const classroomId = document.getElementById('classroomSelect').value;
    if (!classroomId) {
        Swal.fire({
            title: 'Select a Classroom',
            text: 'Please choose a classroom to send the exercise to',
            icon: 'warning',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
        return;
    }

    try {
        const response = await fetch(`/exercises/${selectedExerciseId}/send-to-class`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ classroom_id: classroomId })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Failed to send exercise');
        }

        Swal.fire({
            title: 'Success!',
            text: result.message,
            icon: 'success',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
        
        closeSendToClassModal();
    } catch (error) {
        console.error('Error sending exercise:', error);
        Swal.fire({
            title: 'Error',
            text: error.message || 'Failed to send exercise to class. Please try again.',
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    }
}
</script> 