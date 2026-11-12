<script lang="ts" setup>
import { createPost } from '@/lib/api';
import { computed, ref, type Ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const form: Ref<{title: string, body: string}> = ref({
    title: '',
    body: ''
});

const errors: Ref<{title: string[], body: string[]}> = ref({
    title: [],
    body: []
});

const test = computed(() => {
    return form.value.title + form.value.body;
});

function validateForm(){
  errors.value.title = [];
  errors.value.body = [];

  if(form.value.title.length <= 0) {
    errors.value.title.push('title is required');
  }

  if(form.value.body.length <= 0) {
    errors.value.body.push('body is required');
  }

  return (errors.value.title.length + errors.value.body.length) <= 0;
}

async function handleSubmit(){
    if(!validateForm()) return;

    console.log(test.value);

    try {
        const newPost = await createPost(form.value.title, form.value.body);
    } catch {
        console.log('caught');
    }
    
    console.log('submitted');

    router.replace('/');
}
</script>

<template>
    <form @submit.prevent="handleSubmit" class="d-flex flex-column gap-3">
        <div class="form-field">
            <input v-model.trim="form.title" type="text" class="form-control" id="title" placeholder="title">
            <div v-for="error in errors.title" class="invalid-feedback d-block">
                {{ error }}
            </div>
        </div>
        <div class="form-field">
            <textarea v-model.trim="form.body" class="form-control" id="fieldTextarea" rows="5" placeholder="body"></textarea>
            <div v-for="error in errors.body" class="invalid-feedback d-block">
                {{ error }}
            </div>
        </div>
        <button type="submit" class="btn-solid theme-primary">Create Post</button>
    </form>
</template>