<template>
    <Head>
        <title>กิจกรรมรางวัล</title>
    </Head>
    <div class="mt-6 md:mt-12 bg-orange-200 shadow p-4 md:p-8">
        <h2 class="mb-4 py-2 border-b-2 border-green-500 border-dashed text-xl font-medium text-pink-400">
            กิจกรรมของฉัน
        </h2>
        <button
            class="border border-green-500 bg-green-300 py-2 px-4 text-center text-green-600"
            @click="newEvent"
        >
            สร้างกิจกรรม
        </button>
        <div
            class="mt-4 bg-white p-4 rounded-lg flex justify-between items-center"
            v-for="event in myGiftEvents"
            :key="event.slug"
        >
            <p>{{ event.title }}</p>
            <Link
                class="border border-yellow-400 bg-yellow-200 py-2 px-4 text-center text-yellow-500"
                :href="`/gift-events/${event.slug}/edit`"
            >
                แก้ไข
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link, Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    myGiftEvents: { type: Array, required: true}
});

const newEvent = () => {
    let form = useForm({
        title: 'กิจกรรมใหม่',
        no_luck_label: 'เสียใจด้วยค่ะ'
    });

    form.post('/gift-events');
};
</script>