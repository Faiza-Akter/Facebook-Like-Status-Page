<?php
$title = 'Create Post | AuthBoard';
ob_start();
?>
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center">
        <h1 class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-700 bg-clip-text text-transparent mb-4">Create New Post</h1>
        <p class="text-slate-700 text-xl font-light">Share your thoughts with the community</p>
    </div>
    
    <!-- Create Post Form -->
    <div class="group relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
        <form action="/posts/store" method="POST" enctype="multipart/form-data" 
              class="relative bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/60 p-6 hover:shadow-2xl transition-all duration-500">
            
            <!-- Content Textarea -->
            <div class="mb-7">
                <label for="content" class="block text-2xl font-bold text-slate-800 mb-6">
                    What's on your mind?
                </label>
                <textarea 
                    id="content" 
                    name="content" 
                    rows="6" 
                    class="w-full px-6 py-5 text-lg border-2 border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 resize-none shadow-sm hover:shadow-md bg-white/50 backdrop-blur-sm"
                    placeholder="Share your thoughts, ideas, or ask a question..."
                    required
                ></textarea>
                <div class="flex justify-between items-center mt-4 text-base text-slate-600">
                    <span class="font-light">Express yourself freely</span>
                    <span id="charCount" class="font-medium bg-blue-50 text-blue-600 px-3 py-1 rounded-lg">0 characters</span>
                </div>
            </div>
            
            <!-- Image Upload -->
            <div class="mb-7">
                <label for="image" class="block text-2xl font-bold text-slate-800 mb-6">
                    Add an image (optional)
                </label>
                
                <!-- File Upload Area -->
                <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-blue-400 transition-all duration-300 bg-gradient-to-br from-blue-50/50 to-purple-50/50 cursor-pointer group/upload" id="uploadArea">
                    <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                        class="hidden"
                        onchange="previewImage(this)"
                    >
                    <div id="uploadContent" class="group-hover/upload:scale-105 transition-transform duration-300">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover/upload:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-700 mb-3 font-semibold text-xl">Click to upload an image</p>
                        <p class="text-base text-slate-600 font-light">PNG, JPG, GIF, WebP up to 10MB</p>
                    </div>
                    <div id="imagePreview" class="hidden mt-8">
                        <img id="preview" class="max-w-full max-h-96 rounded-2xl mx-auto shadow-xl border-2 border-white/80">
                        <button type="button" onclick="removeImage()" class="mt-6 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-6 py-3 rounded-xl transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 border border-white/20">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Remove Image
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-6">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-purple-700 hover:from-blue-700 hover:to-purple-800 text-white px-8 py-5 rounded-2xl transition-all duration-300 font-bold text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20 group/submit">
                    <svg class="w-6 h-6 inline mr-3 group-hover/submit:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Publish Post
                </button>
                <a href="/posts" 
                   class="flex-1 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-8 py-5 rounded-2xl transition-all duration-300 font-bold text-lg text-center shadow-xl hover:shadow-2xl transform hover:-translate-y-1 border border-white/20">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter
document.getElementById('content').addEventListener('input', function() {
    const charCount = this.value.length;
    const charCountElement = document.getElementById('charCount');
    charCountElement.textContent = charCount + ' characters';
    
    // Change color based on character count
    if (charCount > 500) {
        charCountElement.className = 'font-medium bg-purple-100 text-purple-600 px-3 py-1 rounded-lg';
    } else if (charCount > 100) {
        charCountElement.className = 'font-medium bg-blue-100 text-blue-600 px-3 py-1 rounded-lg';
    } else {
        charCountElement.className = 'font-medium bg-blue-50 text-blue-600 px-3 py-1 rounded-lg';
    }
});

// Image preview functionality
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
            document.getElementById('uploadContent').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('uploadContent').classList.remove('hidden');
}

// Click to upload
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('image').click();
});

// Add focus effect to textarea
document.getElementById('content').addEventListener('focus', function() {
    this.classList.add('border-blue-400', 'shadow-lg');
});

document.getElementById('content').addEventListener('blur', function() {
    this.classList.remove('border-blue-400', 'shadow-lg');
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
