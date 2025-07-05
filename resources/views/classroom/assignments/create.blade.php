@extends('classroom.show')

@section('content')
<div class="container py-10">
    <h1 class="text-gray-500 text-xl mb-6">Create Assignment</h1>
    @if(session('success'))
        <div class="bg-green-600 text-white p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-600 text-white p-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if(session('debug'))
        <div class="bg-yellow-600 text-white p-3 rounded mb-4">{{ session('debug') }}</div>
    @endif
    <form action="{{ route('classroom.assignments.store', $classroom->name) }}" method="POST" class="space-y-6 p-8">
        @csrf
        <div>
            <label class="block text-white mb-2">Judul Assignment</label>
            <input type="text" name="title" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
        </div>
        <div>
            <label class="block text-white mb-2">Deskripsi Assignment</label>
            <textarea name="description" rows="4" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" placeholder="Berikan deskripsi atau instruksi untuk tugas ini..."></textarea>
        </div>
        <div>
            <label class="block text-white mb-2">Batas Waktu Pengumpulan</label>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="due_day" class="block text-gray-400 text-sm mb-1">Hari</label>
                    <select name="due_day" id="due_day" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ sprintf('%02d', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="due_month" class="block text-gray-400 text-sm mb-1">Bulan</label>
                    <select name="due_month" id="due_month" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label for="due_year" class="block text-gray-400 text-sm mb-1">Tahun</label>
                    <select name="due_year" id="due_year" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        @for ($i = date('Y'); $i <= date('Y') + 5; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        {{-- <div>
            <label class="block text-white mb-2">Upload File (PDF/DOCX)</label>
            <input type="file" name="file" accept=".pdf,.doc,.docx" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500">
        </div> --}}
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">Buat Assignment</button>
        </div>
    </form>
</div>
@endsection 