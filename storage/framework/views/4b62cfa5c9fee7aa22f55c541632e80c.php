<?php $__env->startSection('content'); ?>
<div class="container py-10">
    <h1 class="text-gray-500 text-xl mb-6">Create Assignment</h1>
    <?php if(session('success')): ?>
        <div class="bg-green-600 text-white p-3 rounded mb-4"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-600 text-white p-3 rounded mb-4"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if(session('debug')): ?>
        <div class="bg-yellow-600 text-white p-3 rounded mb-4"><?php echo e(session('debug')); ?></div>
    <?php endif; ?>
    <form action="<?php echo e(route('classroom.assignments.store', $classroom->name)); ?>" method="POST" class="space-y-6 p-8">
        <?php echo csrf_field(); ?>
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
                        <?php for($i = 1; $i <= 31; $i++): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e(sprintf('%02d', $i)); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="due_month" class="block text-gray-400 text-sm mb-1">Bulan</label>
                    <select name="due_month" id="due_month" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e(date('F', mktime(0, 0, 0, $i, 10))); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="due_year" class="block text-gray-400 text-sm mb-1">Tahun</label>
                    <select name="due_year" id="due_year" class="w-full rounded-lg p-3 bg-[#1a1a1f] text-white focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                        <?php for($i = date('Y'); $i <= date('Y') + 5; $i++): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">Buat Assignment</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/assignments/create.blade.php ENDPATH**/ ?>