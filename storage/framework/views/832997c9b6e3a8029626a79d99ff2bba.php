<?php $__env->startSection('content'); ?>

          
                <div class="container">
                    <div class="py-10 ">
                        <div class="flex justify-between">
                            <h1 class="text-gray-500 text-xl">MATERIALS</h1>
                            
                        </div>


                        <div class="pt-5 pb-5">
                          <div class="flex justify-between items-center text-center">
                            <?php if(auth()->user()->role !=='student'): ?>
                          <button class="rounded-lg px-4 py-2 text-gray-300 border border-transparent hover:border-pink-500 hover:text-pink-500 hover:bg-pink-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                              Create Materials
                          </button>
                          <?php endif; ?>
                         
                        </div>
                        </div>

                        <div class="list-materials w-full space-y-5 pt-5">
                         <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                         <div class="group card w-full h-auto p-5 bg-[#211F27] rounded-lg flex items-center justify-between relative overflow-hidden transition-all duration-300 hover:bg-pink-950/20 before:content-[''] before:absolute before:top-0 before:left-0 before:w-1 before:h-full before:bg-pink-500/0 hover:before:bg-pink-500 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-gradient-to-r after:from-pink-500/50 after:to-transparent">
                            <div class="judul">
                              <h1 class="text-white font-bold text-lg group-hover:text-pink-500 transition-colors"><?php echo e($material->title); ?></h1>
                              <p class="text-gray-400 group-hover:text-pink-300 transition-colors"><?php echo e($material->category ?? '-'); ?></p>
                            </div>
                            <div class="w-auto">
                              <p class="text-gray-400 group-hover:text-pink-300 transition-colors"><?php echo e($material->created_at->format('d-M-Y')); ?></p>
                            </div>
                            <div>
                              <a href="<?php echo e(route('materials.show', $material->id)); ?>">
                                <button class="rounded-lg px-4 py-2 text-gray-300 border border-transparent hover:border-pink-500 hover:text-pink-500 hover:bg-pink-950/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                  Lihat Materi
                                </button>
                              </a>
                              <?php if(auth()->user()->role !== 'student'): ?>
                              <button type="button" onclick="confirmDeleteMaterial(this, <?php echo e($material->id); ?>)" class="rounded-lg px-4 py-2 text-red-400 border border-transparent hover:border-red-500 hover:text-white hover:bg-red-900/20 transition-all duration-300 uppercase tracking-wider text-sm font-medium">
                                Hapus
                              </button>
                              <?php endif; ?>
                            </div>
                         </div>
                         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                         <p class="text-gray-400">Belum ada materi.</p>
                         <?php endif; ?>
                        </div>

                    </div>
                </div>
            

<?php $__env->stopSection(); ?>

<?php if(session('success')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo e(session('success')); ?>',
            icon: 'success',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493',
        });
    });
</script>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteMaterial(button, materialId) {
    Swal.fire({
        title: 'Hapus Materi?',
        text: 'Apakah Anda yakin ingin menghapus materi ini dari kelas?',
        icon: 'warning',
        showCancelButton: true,
        background: '#211F27',
        color: '#fff',
        confirmButtonColor: '#FF1493',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteMaterialFromClassroom(button, materialId);
        }
    });
}

async function deleteMaterialFromClassroom(button, materialId) {
    try {
        const response = await fetch(`/materials/classroom/<?php echo e($classroom->id); ?>/material/${materialId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        if (response.ok && data.success) {
            // Remove card dari DOM
            const card = button.closest('.card');
            if (card) card.remove();
            Swal.fire({
                title: 'Berhasil!',
                text: data.message || 'Materi berhasil dihapus dari kelas.',
                icon: 'success',
                background: '#211F27',
                color: '#fff',
                confirmButtonColor: '#FF1493'
            });
        } else {
            throw new Error(data.message || 'Gagal menghapus materi');
        }
    } catch (error) {
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Terjadi kesalahan',
            icon: 'error',
            background: '#211F27',
            color: '#fff',
            confirmButtonColor: '#FF1493'
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('classroom.show', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/classroom/materials.blade.php ENDPATH**/ ?>