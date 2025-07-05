<!-- Add Topic Modal -->
<div id="addTopicModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-[#211F27] rounded-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Add New Topic</h2>
            <button onclick="closeAddTopicModal()" class="text-gray-400 hover:text-white">
                <i class="fi fi-rr-cross text-xl"></i>
            </button>
        </div>
        <form id="addTopicForm" method="POST" action="{{ route('topics.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="category" id="topicCategory">
            <div>
                <label for="topic_name" class="block text-gray-300 text-sm font-medium mb-2">Topic Name</label>
                <input type="text" name="name" id="topic_name" placeholder="Enter topic name" 
                       class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
            </div>
            <div>
                <label for="topic_description" class="block text-gray-300 text-sm font-medium mb-2">Description (Optional)</label>
                <textarea name="description" id="topic_description" placeholder="Enter topic description" rows="3"
                          class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAddTopicModal()" 
                        class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Add Topic
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Subtopic Modal -->
<div id="addSubtopicModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-[#211F27] rounded-lg border border-pink-500/20 p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-white">Add New Subtopic</h2>
            <button onclick="closeAddSubtopicModal()" class="text-gray-400 hover:text-white">
                <i class="fi fi-rr-cross text-xl"></i>
            </button>
        </div>
        <form id="addSubtopicForm" method="POST" action="{{ route('subtopics.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="topic_id" id="subtopicTopicId">
            <div>
                <label for="subtopic_name" class="block text-gray-300 text-sm font-medium mb-2">Subtopic Name</label>
                <input type="text" name="name" id="subtopic_name" placeholder="Enter subtopic name" 
                       class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500" required>
            </div>
            <div>
                <label for="subtopic_description" class="block text-gray-300 text-sm font-medium mb-2">Description (Optional)</label>
                <textarea name="description" id="subtopic_description" placeholder="Enter subtopic description" rows="3"
                          class="w-full bg-[#101014] p-3 text-white rounded-lg border border-gray-700 focus:border-pink-500 focus:ring-1 focus:ring-pink-500"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeAddSubtopicModal()" 
                        class="px-4 py-2 border-2 border-pink-500 text-white rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity">
                    Add Subtopic
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Send to Class Modal -->
<div id="sendToClassModal" class="modal hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-[#211F27] rounded-lg shadow-xl border border-pink-500/20 w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <i class="fi fi-rr-share text-pink-500 text-xl"></i>
                    <h2 class="text-2xl font-bold text-white">Send to Class</h2>
                </div>
                <button onclick="closeSendToClassModal()" class="text-gray-400 hover:text-white">
                    <i class="fi fi-rr-cross text-xl"></i>
                </button>
            </div>

            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Select Classroom</label>
                <div class="relative">
                    <select id="classroomSelect" 
                            class="w-full bg-[#1a1a1f] text-white rounded-lg border border-gray-700 p-3 pr-10 focus:border-pink-500 focus:ring-1 focus:ring-pink-500 appearance-none">
                        <option value="">Choose a classroom...</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <i class="fi fi-rr-angle-small-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button onclick="closeSendToClassModal()" 
                        class="px-4 py-2 text-white border-2 border-pink-500 rounded-lg hover:bg-gradient-to-r from-pink-500 to-orange-500 hover:border-transparent transition-all duration-300">
                    Cancel
                </button>
                <button onclick="sendToClass()" 
                        class="px-4 py-2 bg-gradient-to-r from-pink-500 to-orange-500 text-white rounded-lg hover:opacity-90 transition-opacity duration-300">
                    Send Exercise
                </button>
            </div>
        </div>
    </div>
</div> 