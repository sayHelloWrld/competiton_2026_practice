<script setup lang="ts">
import { RouterView } from 'vue-router';
import { Suspense } from 'vue';
</script>

<template>
  <nav class="navbar sm:navbar-expand bg-1 fg-2">
    <div class="container-fluid">
      <button class="btn-icon navbar-toggler" type="button" data-bs-toggle="drawer" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon" aria-hidden="true"></span>
      </button>
      <dialog class="drawer drawer-end" tabindex="-1" id="navbarNav" aria-labelledby="navbarNavLabel">
        <div class="drawer-header">
          <h5 class="drawer-title" id="navbarNavLabel">Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="drawer" aria-label="Close"></button>
        </div>
        <div class="drawer-body">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/">Posts</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/posts/create">Create Post</router-link>
            </li>
          </ul>
        </div>
      </dialog>
    </div>
  </nav>
  <div class="container mt-5">
    <Suspense>
      <RouterView />
    </Suspense>
  </div>
</template>

<style scoped></style>
