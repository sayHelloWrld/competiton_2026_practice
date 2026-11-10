<script lang="ts" setup>
import { getPosts } from '@/lib/api';
import type { Post } from '@/lib/types/post';
import { ref, type Ref } from 'vue';

const posts: Ref<Post[]> = ref(await getPosts());
</script>

<template>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">title</th>
                <th scope="col">body</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="post in posts">
                <td>{{ post.id }}</td>
                <td>{{ post.title }}</td>
                <td>{{ post.body }}</td>
            </tr>
        </tbody>
    </table>
</template>