<template>
    <Head>
        <title>Neptune: ลงชื่อเข้าใช้งาน</title>
    </Head>
    <div class="flex flex-col justify-center items-center w-full min-h-screen">
        <div class="mt-4 px-4 py-8 w-80 bg-white shadow">
            <div>
                <label for="login">ชื่อบัญชี : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="login"
                    name="login"
                    type="text"
                    v-model="form.login"
                >
            </div>
            <small class="text-red-400 text-sm">{{ form.errors.login }}</small>
            <div class="mt-4">
                <label for="login">รหัสผ่าน : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="login"
                    name="login"
                    type="password"
                    v-model="form.password"
                >
            </div>
            <button
                class="mt-6 w-full border border-green-500 bg-green-300 py-2 px-4 text-center text-green-600"
                @click="form.post('/login')"
            >
                เข้าใช้งาน
            </button>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    login: null,
    password: null,
});
</script>