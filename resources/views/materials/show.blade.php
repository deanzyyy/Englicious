<x-layout>
    <div class="min-h-screen bg-[#1a1a1f] p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <a href="{{ route('materials.index') }}" class="flex items-center text-gray-400 hover:text-pink-400 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Materials List
            </a>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">{{ $material->title }}</h1>
                <div class="flex items-center space-x-4 text-gray-400">
                    <span>{{ $material->category }}</span>
                    <span>•</span>
                    <span>{{ $material->topic->name }}</span>
                    <span>•</span>
                    <span>{{ $material->subtopic->name }}</span>
                </div>
                @if($material->description)
                    <p class="text-gray-300 mt-4">{{ $material->description }}</p>
                @endif
            </div>

            <!-- PDF Viewer -->
            <div class="bg-[#211F27] rounded-lg overflow-hidden">
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-white font-medium">PDF Document</h2>
                        <a href="{{ Storage::url($material->file_path) }}" target="_blank" 
                           class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                            Download PDF
                        </a>
                    </div>
                </div>
                <div class="aspect-[16/9] w-full">
                    <iframe src="{{ Storage::url($material->file_path) }}" 
                            class="w-full h-full"
                            type="application/pdf">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</x-layout> 