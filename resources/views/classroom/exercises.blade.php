@extends('classroom.show')

@section('content')
<div class="container">
    <div class="py-10">
        <div class="flex justify-between">
            <h1 class="text-gray-500 text-xl">EXERCISES</h1>
            @if(auth()->user()->role === 'student')
                <a href="{{ route('exercises.my_results') }}"
                   class="px-4 py-2 border-2 border-pink-500 text-pink-500 rounded-lg hover:opacity-90 transition-opacity flex items-center">
                    <i class="fi fi-rr-list mr-2"></i>
                    My Exercise Results
                </a>
            @else
                <button class="text-gray-500 text-xl hover:text-white">See All</button>
            @endif
        </div>

        <div class="list-exercises w-full space-y-5 pt-5">
            @if($classroom->exercises->isEmpty())
                <div class="card w-full h-auto p-10 bg-[#211F27] rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">No exercises available in this classroom yet.</p>
                </div>
            @else
                @foreach($classroom->exercises as $exercise)
                    <div id="exercise-card-{{ $exercise->id }}" class="group card w-full h-auto p-5 bg-[#211F27] rounded-lg flex items-center justify-between relative overflow-hidden transition-all duration-300 hover:bg-pink-950/20 before:content-[''] before:absolute before:top-0 before:left-0 before:w-1 before:h-full before:bg-pink-500/0 hover:before:bg-pink-500 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-gradient-to-r after:from-pink-500/50 after:to-transparent">
                        <div class="judul">
                            <h1 class="text-white font-bold text-lg group-hover:text-pink-500 transition-colors">{{ $exercise->title }}</h1>
                            <p class="text-gray-400 group-hover:text-pink-300 transition-colors">{{ $exercise->category }} - {{ $exercise->topic->name }}</p>
                        </div>
                        <div class="w-auto">
                            <p class="text-gray-400 group-hover:text-pink-300 transition-colors">{{ $exercise->created_at->format('d-M-Y') }}</p>
                        </div>
                        <div class="flex space-x-3">
                            @if($exercise->is_file_upload)
                                <a href="{{ route('preview.file', $exercise->id) }}" 
                                   class="rounded-lg px-4 py-2 text-gray-300 border border-transparent hover:border-pink-500 hover:text-pink-500 hover:bg-pink-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                    View PDF
                                </a>
                            @else
                                <a href="{{ route('classroom.exercise.take', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}" 
                                   class="rounded-lg px-4 py-2 text-gray-300 border border-transparent hover:border-pink-500 hover:text-pink-500 hover:bg-pink-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                    Kerjakan
                                </a>
                            @endif
                            @if(auth()->user()->role !== 'student')
                                <a href="{{ route('classroom.exercise.review', ['className' => $classroom->name, 'exerciseId' => $exercise->id]) }}"
                                   class="rounded-lg px-4 py-2 text-blue-400 border border-blue-500 hover:bg-blue-500 hover:text-white transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                    Review Answers
                                </a>
                                <button type="button" onclick="confirmDelete({{ $exercise->id }})" 
                                        class="rounded-lg px-4 py-2 text-red-500 border border-transparent hover:border-red-500 hover:bg-red-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(exerciseId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This exercise will be removed from the classroom. Students won't be able to access it anymore.",
        icon: 'warning',
        background: '#211F27',
        color: '#fff',
        showCancelButton: true,
        confirmButtonColor: '#FF1493',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteExercise(exerciseId);
        }
    });
}

async function deleteExercise(exerciseId) {
    try {
        const response = await fetch(`/classroom/{{ $classroom->id }}/exercises/${exerciseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        console.log('Response:', data); // Debug log

        if (response.ok) {
            const card = document.getElementById(`exercise-card-${exerciseId}`);
            if (card) {
                // Add fade out animation
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(20px)';

                // Remove the card after animation
                setTimeout(() => {
                    card.remove();
                    
                    // Check if there are any exercises left
                    const exercisesList = document.querySelector('.list-exercises');
                    if (!exercisesList.querySelector('.card')) {
                        exercisesList.innerHTML = `
                            <div class="card w-full h-auto p-10 bg-[#211F27] rounded-lg flex items-center justify-center">
                                <p class="text-gray-500">No exercises available in this classroom yet.</p>
                            </div>
                        `;
                    }
                }, 300);
            }

            Swal.fire({
                title: 'Deleted!',
                text: data.message,
                icon: 'success',
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493'
            });
        } else {
            throw new Error(data.message || 'Failed to delete exercise');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Something went wrong',
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    }
}
</script>
@endpush
@endsection 