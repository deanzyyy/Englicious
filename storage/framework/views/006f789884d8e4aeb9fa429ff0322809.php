<div>
    <h4 class="text-lg font-bold text-pink-500 mb-4">Detail Nilai Siswa</h4>
    <div class="mb-4">
        <span class="font-semibold text-white">Nama:</span> <span class="text-gray-300"><?php echo e($student->name); ?></span><br>
        <span class="font-semibold text-white">Email:</span> <span class="text-gray-300"><?php echo e($student->email); ?></span>
    </div>
    <div class="mb-6">
        <h5 class="font-semibold text-pink-400 mb-2">Nilai Exercise</h5>
        <?php if($exerciseSubmissions->count() > 0): ?>
            <table class="w-full text-sm mb-2">
                <thead>
                    <tr class="text-gray-400">
                        <th class="py-1 px-2 text-left">Judul</th>
                        <th class="py-1 px-2 text-left">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $exerciseSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-1 px-2 text-white"><?php echo e($ex->exercise->title ?? '-'); ?></td>
                            <td class="py-1 px-2 text-white"><?php echo e($ex->score !== null ? $ex->score : '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-400">Belum ada exercise yang dinilai.</p>
        <?php endif; ?>
    </div>
    <div class="mb-6">
        <h5 class="font-semibold text-pink-400 mb-2">Nilai Assignment</h5>
        <?php if($assignmentSubmissions->count() > 0): ?>
            <table class="w-full text-sm mb-2">
                <thead>
                    <tr class="text-gray-400">
                        <th class="py-1 px-2 text-left">Judul</th>
                        <th class="py-1 px-2 text-left">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $assignmentSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $as): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-1 px-2 text-white"><?php echo e($as->assignment->title ?? '-'); ?></td>
                            <td class="py-1 px-2 text-white"><?php echo e($as->grade !== null ? $as->grade : '-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-400">Belum ada assignment yang dinilai.</p>
        <?php endif; ?>
    </div>
    <div class="mb-2">
        <span class="font-semibold text-white">Rata-rata Exercise:</span> <span class="text-gray-300"><?php echo e($avgExercise !== null ? number_format($avgExercise, 2) : '-'); ?></span><br>
        <span class="font-semibold text-white">Rata-rata Assignment:</span> <span class="text-gray-300"><?php echo e($avgAssignment !== null ? number_format($avgAssignment, 2) : '-'); ?></span><br>
        <span class="font-semibold text-white">Nilai Akhir:</span> <span class="text-pink-400 font-bold"><?php echo e($finalScore !== null ? number_format($finalScore, 2) : '-'); ?></span>
    </div>
</div> <?php /**PATH D:\Englicious\Englicious\resources\views/grades/student_details.blade.php ENDPATH**/ ?>