<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import JsonDisplay from '@/Components/JsonDisplay.vue';

const fileInput = ref(null);
const files = ref([]);
const isDragover = ref(false);
const jsonData = ref(null);
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function dragover(event) {
    event.preventDefault();
    isDragover.value = true;
}

function dragleave(event) {
    event.preventDefault();
    isDragover.value = false;
}

function drop(event) {
    event.preventDefault();
    isDragover.value = false;
    files.value = [...event.dataTransfer.files];
}

function handleFileChange(event) {
    files.value = [...event.target.files];
}

function triggerFileInput() {
    fileInput.value.click();
}

async function submitFiles() {
    const formData = new FormData();
    files.value.forEach(file => {
        formData.append('fileUpload', file);
    });

    try {
        jsonData.value = null;
        const response = await axios.post('/api/verifyDocument', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': csrfToken
            },
            withCredentials: true
        });
        jsonData.value = response.data;
        console.log(response.data);
    } catch (error) {
        console.error(error);
    }
}
</script>

<template>
    <Head title="Upload Verification" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="grid justify-items-center mt-4">
          <div class="w-2/5">
            <div
                class="drag-drop-area"
                @click="triggerFileInput"
                @dragover="dragover"
                @dragleave="dragleave"
                @drop="drop"
                :class="{ 'dragover': isDragover }"
            >
                <p>Drag & Drop your files here or click to upload</p>
                <input
                    type="file"
                    @change="handleFileChange"
                    ref="fileInput"
                    class="file-input"
                    multiple
                />
            </div>
            <button class="block w-full text-sm text-slate-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-violet-50 file:text-violet-700
                          hover:file:bg-violet-100" @click="submitFiles" :disabled="!files.length">Upload</button>
            <ul v-if="files.length">
                <li v-for="file in files" :key="file.name">{{ file.name }}</li>
            </ul>
          </div>
          <div class="w-2/5 mt-4 ">
            <JsonDisplay :jsonData="jsonData"></JsonDisplay>
          </div>
           
        </div>    
    </AuthenticatedLayout>
</template>

<style scoped>
.drag-drop-area {
    border: 2px dashed #ccc;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s;
}
.drag-drop-area.dragover {
    background-color: #e3e3e3;
}
.file-input {
    display: none;
}
</style>
