<script lang="ts" setup>
import { injectPosts } from '@/composables/posts';

const posts = injectPosts();
</script>

<template>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">title</th>
                <th scope="col">body</th>
                <th scope="col">actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(post, index) in posts" :key="index">
                <td>{{ post.id }}</td>
                <td>{{ post.title }}</td>
                <td>{{ post.body }}</td>
                <td class="d-flex flex-column gap-2">
                    <router-link :to="{name: 'edit', params: { id: post.id }, query: { test: 'testval' }}">
                        <button class="btn-solid theme-primary w-100">Edit</button>
                    </router-link>
                    <button type="button" class="btn-solid theme-primary" data-bs-toggle="dialog" data-bs-target="#exampleDialog">
                        Open dialog
                    </button>
                    <dialog class="dialog" id="exampleDialog">
                        <div class="dialog-header">
                            <h1 class="dialog-title">Dialog title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="dialog" aria-label="Close"></button>
                        </div>
                        <div class="dialog-body">
                            <p>This is a native dialog element. It uses the browser’s built-in modal behavior for accessibility and focus management.</p>
                        </div>
                        <div class="dialog-footer">
                            <button type="button" class="btn-solid theme-secondary" data-bs-dismiss="dialog">Close</button>
                            <button type="button" class="btn-solid theme-primary" data-bs-dismiss="dialog" @click="() => console.log('test')" :id="index.toString()">Save changes</button>
                        </div>
                    </dialog>
                </td>
            </tr>
        </tbody>
    </table>
</template>