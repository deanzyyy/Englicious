<!-- Logout Confirmation Modal -->
<div id="logoutConfirmationModal" class="fixed inset-0 bg-opacity-50 z-[100] hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-[#211F27] rounded-lg shadow-lg border border-pink-500/20 w-full max-w-md">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-white mb-4 text-center">Log Out?</h2>
                <p class="text-gray-400 mb-6 text-center">Are you sure you want to log out?</p>
                <div class="flex justify-center gap-2">
                    <button type="button" onclick="closeLogoutConfirmationModal()"
                            class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmLogout()"
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openLogoutConfirmationModal() {
        document.getElementById('logoutConfirmationModal').classList.remove('hidden');
    }
    function closeLogoutConfirmationModal() {
        document.getElementById('logoutConfirmationModal').classList.add('hidden');
    }
    function confirmLogout() {
        document.getElementById('logoutForm').submit();
    }
</script>
@endpush 