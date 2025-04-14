<x-layout>
    <div class="ml-64">
        <div class="p-10">
        <h1 class="text-white text-4xl font-bold">Exercise Form</h1>
        <p class="text-lg text-gray-400 mt-2">Make a task for student here, Goodluck!</p>
        </div>

        <div class="container p-2">
            <div class="content bg-gray-900 p-10 rounded-lg">
                <form action="" class="space-y-4 max-w-2xl">
                    <label for="" class="text-gray-300 text-lg">Title Exercise</label>
                    <input type="text" class="bg-gray-800 p-2 text-white rounded-lg w-full" placeholder="Exercise Title">
                    <label for="" class="text-gray-300 text-lg">Description</label>
                    <input type="text" class="bg-gray-800 p-2 text-white rounded-lg w-full" placeholder="Description">
                    <label for="options" class="block text-lg text-gray-300">Classroom</label>
<select id="options" class="mt-1 block w-full p-2 text-white bg-gray-800 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
    <option value="" class="text-gray-500">Select your Class</option>
    <option value="1">1A Class</option>
    <option value="2">2B Class</option>
    <option value="3">3C Class</option>
</select>
                    <label for="options" class="block text-lg text-gray-300">Category</label>
<select name="category" id="options" class="mt-1 block w-full p-2 text-white bg-gray-800 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
    <option value="" class="text-gray-500">Select your Category</option>
    <option value="general">General</option>
    <option value="Toefl">Toefl</option>
    <option value="Ielts">Ielts</option>
</select>
<div class="flex justify-between items-center space-y-lg">
<button class="bg-blue-600 text-white p-3 rounded-lg">Add New Task</button>
<button class="bg-green-600 text-white p-3 rounded-lg">Finish</button>
</div>

                </form>
            </div>
        </div>
    </div>

<script>

</script>

</x-layout>