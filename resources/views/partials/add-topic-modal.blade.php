<!-- Add Topic Modal -->
<div id="addTopicModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-[#211F27] p-6 rounded-lg w-[500px]">
        <h2 class="text-2xl font-bold text-white mb-4">Add New Topic</h2>
        
        <!-- Topic Form -->
        <form id="addTopicForm" class="space-y-4">
            @csrf
            
            <!-- Category -->
            <div>
                <label for="newCategory" class="block text-lg text-gray-300 font-semibold mb-2">Category</label>
                <select id="newCategory" name="category" class="mt-2 block w-full p-3 text-white bg-[#101014] rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
                    <option value="">Select Category</option>
                    <option value="General">General</option>
                    <option value="IELTS">IELTS</option>
                    <option value="TOEFL">TOEFL</option>
                </select>
            </div>

            <!-- Topic Name -->
            <div>
                <label for="topicName" class="block text-lg text-gray-300 font-semibold mb-2">Topic Name</label>
                <input type="text" id="topicName" name="name" required
                    class="mt-2 bg-[#101014] p-3 text-white rounded-lg w-full border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                    placeholder="Enter topic name">
            </div>

            <!-- Subtopics -->
            <div>
                <label class="block text-lg text-gray-300 font-semibold mb-2">Subtopics</label>
                <div id="subtopics-container" class="space-y-2" wire:ignore>
                    <div class="flex items-center space-x-2 subtopic-item">
                        <input type="text" name="subtopics[]" required
                            class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"
                            placeholder="Enter subtopic name">
                        <button type="button" onclick="window.removeSubtopicField(this)" class="text-red-500 hover:text-red-400 text-xl">
                            <i class="fi fi-rr-minus-circle"></i>
                        </button>
                    </div>
                </div>
                <button type="button" id="addSubtopicButton" 
                        class="mt-4 px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity flex items-center space-x-2">
                    <i class="fi fi-rr-plus"></i>
                    <span>Add Subtopic</span>
                </button>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="window.closeAddTopicModal()" 
                    class="px-6 py-3 border border-gray-600 text-gray-400 rounded-lg hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Add Topic
                </button>
            </div>
        </form>
    </div>
</div> 