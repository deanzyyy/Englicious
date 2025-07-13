<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-[#211F27] p-8 rounded-lg shadow-lg w-full max-w-md border border-pink-500">
        <h2 class="text-2xl font-semibold text-white mb-6 text-center">Create Account</h2>

        <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            
            <div>
                <label for="name" class="block text-gray-400 mb-1">Full Name</label>
                <input type="text" name="name" id="name" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none"
                    value="<?php echo e(old('name')); ?>">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="email" class="block text-gray-400 mb-1">Email</label>
                <input type="email" name="email" id="email" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none"
                    value="<?php echo e(old('email')); ?>">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="password" class="block text-gray-400 mb-1">Password</label>
                <input type="password" name="password" id="password" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="password_confirmation" class="block text-gray-400 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required 
                    class="w-full p-2 rounded border border-gray-700 bg-[#2A2833] text-white focus:border-pink-500 focus:outline-none">
            </div>

            <input type="hidden" name="role" value="student">

            <button type="submit" 
                class="w-full py-2 px-4 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                Create Account
            </button>

            <p class="text-center text-gray-400 text-sm">
                Already have an account? 
                <a href="<?php echo e(route('login')); ?>" class="text-pink-500 hover:text-pink-400">Login</a>
            </p>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Englicious\Englicious\resources\views/auth/register.blade.php ENDPATH**/ ?>