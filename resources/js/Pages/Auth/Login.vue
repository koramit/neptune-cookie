<template>
    <h1>login</h1>
    <div>
        <input
            type="text"
            placeholder="login"
            v-model="form.login"
        >
    </div>
    <small class="text-red-400 text-sm">{{ form.errors.login }}</small>
    <div>
        <input
            type="password"
            placeholder="password"
            v-model="form.password"
        >
    </div>
    <button @click="form.post('/login')">
        login
    </button>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    login: null,
    password: null,
});
</script>