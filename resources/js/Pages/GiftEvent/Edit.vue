<template>
    <Head>
        <title>แก้ไข {{ form.title }}</title>
    </Head>
    <div class="mt-6 md:mt-12 bg-orange-200 shadow p-4 md:p-8">
        <h2 class="mb-4 py-2 border-b-2 border-green-500 border-dashed text-xl font-medium text-pink-400 flex justify-between items-center">
            <span>ข้อมูลกิจกรรม</span>
            <Link
                class="text-sm underline text-blue-500"
                href="/gift-events"
            >
                กลับหน้าหลัก
            </Link>
        </h2>
        <button
            class="mt-4 border border-yellow-400 bg-yellow-200 py-2 px-4 text-center text-yellow-500"
            @click="addGroup"
        >
            บันทึก
        </button>
        <div class="mt-4 bg-white p-4 rounded-lg">
            <div>
                <label for="eventTitle">ชื่อกิจกรรม : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="eventTitle"
                    name="eventTitle"
                    type="text"
                    v-model="form.title"
                >
            </div>
            <div class="mt-4">
                <label for="eventNoLuckLabel">ข้อความในฉลากกรณีไม่ได้รางวัล : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="eventNoLuckLabel"
                    name="eventNoLuckLabel"
                    type="text"
                    v-model="form.no_luck_label"
                >
            </div>
            <div class="mt-4">
                <label for="eventDatetimeStart">วันเวลาเริ่มงาน : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="eventDatetimeStart"
                    name="eventDatetimeStart"
                    type="datetime-local"
                    v-model="form.datetime_start"
                >
            </div>
            <div class="mt-4">
                <label for="eventLink">Link ของงาน : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    id="eventLink"
                    name="eventLink"
                    type="text"
                    v-model="form.link"
                    disabled
                >
            </div>
            <div class="mt-4 md:grid grid-cols-2 gap-4">
                <div>
                    <label for="eventParticipants">จำนวนผู้ร่วมงานทั้งหมด (จาก {{ form.groups.length }} กลุ่ม) : </label>
                    <input
                        class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                        id="eventParticipants"
                        name="eventParticipants"
                        v-model="participantCount"
                        disabled
                    >
                </div>
                <div class="mt-4 md:mt-0">
                    <label for="eventGifts">จำนวนรางวัลทั้งหมด (จาก {{ form.gifts.length }} ชนิด) : </label>
                    <input
                        class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                        id="eventGifts"
                        name="eventGifts"
                        v-model="giftCount"
                        disabled
                    >
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 md:mt-12 bg-orange-200 shadow p-4 md:p-8">
        <h2 class="mb-4 py-2 border-b-2 border-green-500 border-dashed text-xl font-medium text-pink-400">
            กลุ่มผู้ร่วมงาน
        </h2>
        <button
            class="border border-green-500 bg-green-300 py-2 px-4 text-center text-green-600"
            @click="addGroup"
        >
            เพิ่มกลุ่ม
        </button>
        <div
            class="mt-4 bg-white p-4 rounded-lg"
            v-for="(group, key) in form.groups"
            :key="key"
        >
            <div>
                <label :for="`groupTitle-${key}`">ชื่อกลุ่ม : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    :id="`groupTitle-${key}`"
                    :name="`groupTitle-${key}`"
                    type="text"
                    v-model="group.title"
                >
            </div>
            <div class="mt-4">
                <label :for="`groupGiftQuota-${key}`">โควตารางวัล (รางวัล) : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    :id="`groupGiftQuota-${key}`"
                    :name="`groupGiftQuota-${key}`"
                    type="tel"
                    pattern="[0-9]{3}"
                    v-model="group.gift_quota"
                >
            </div>
            <div class="mt-4">
                <label :for="`groupParticipantCount-${key}`">ID ของสมาชิก ({{ group.participants ? group.participants.split(' ').length : 0 }}) : </label>
                <input
                    class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                    :id="`groupParticipantCount-${key}`"
                    :name="`groupParticipantCount-${key}`"
                    type="text"
                    placeholder="copy SAP ID จาก excel มาใส่ช่องนี้"
                    v-model="group.participants"
                >
            </div>
            <div
                class="mt-2 grid grid-cols-2 md:grid-cols-5 gap-4"
                v-if="group.participants"
            >
                <span
                    class="inline-block px-4 py-2 bg-green-200 rounded-3xl text-center text-green-600"
                    v-for="id in group.participants.split(' ')"
                    :key="id"
                >{{ id }}</span>
            </div>
            <button
                class="mt-4 border border-red-500 bg-red-300 py-2 px-4 text-center text-red-600"
                @click="groups.splice(key, 1)"
            >
                ลบกลุ่ม
            </button>
        </div>
    </div>

    <div class="mt-6 md:mt-12 bg-orange-200 shadow p-4 md:p-8">
        <h2 class="mb-4 py-2 border-b-2 border-green-500 border-dashed text-xl font-medium text-pink-400">
            รางวัล
        </h2>
        <button
            class="border border-green-500 bg-green-300 py-2 px-4 text-center text-green-600"
            @click="addGift"
        >
            เพิ่มรางวัล
        </button>
        <div
            class="mt-4 bg-white p-4 rounded-lg"
            v-for="(gift, key) in form.gifts"
            :key="key"
        >
            <div class="md:grid grid-cols-2 gap-4">
                <div>
                    <label :for="`giftTitle-${key}`">ชื่อรางวัล : </label>
                    <input
                        class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                        :id="`giftTitle-${key}`"
                        :name="`giftTitle-${key}`"
                        type="text"
                        v-model="gift.title"
                    >
                </div>
                <div class="mt-4 md:mt-0">
                    <label :for="`giftQuantity-${key}`">จำนวนรางวัล : </label>
                    <input
                        class="inline-block w-full border-2 border-green-300 outline-none focus:border-green-500 px-2 py-1"
                        :id="`giftQuantity-${key}`"
                        :name="`giftQuantity-${key}`"
                        type="tel"
                        pattern="[0-9]{3}"
                        v-model="gift.quantity"
                    >
                </div>
            </div>
            <button
                class="mt-4 border border-red-500 bg-red-300 py-2 px-4 text-center text-red-600"
                @click="gifts.splice(key, 1)"
            >
                ลบรางวัล
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from '@vue/reactivity';
import { nextTick } from '@vue/runtime-core';
import { Link, Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    giftEvent: { type: Object, required: true }
});

const form = useForm({...props.giftEvent});

const addGroup = () => {
    form.groups.push({
        id: null,
        title: null,
        gift_quota: 1,
        participants: null
    });
    nextTick(() => {
        let input = document.getElementById(`groupTitle-${form.groups.length - 1}`);
        input.focus();
        input.scrollIntoView();
    });
};
const participantCount = computed(() => {
    if (!form.groups.length) {
        return 0;
    }
    return form.groups.map(g => g.participants ? g.participants.split(' ').length : 0).reduce((n, m) => n + m, 0);
});

const addGift = () => {
    form.gifts.push({
        id: null,
        title: null,
        quantity: 1
    });
    nextTick(() => {
        let input = document.getElementById(`giftTitle-${form.gifts.length - 1}`);
        input.focus();
        input.scrollIntoView();
    });
};
const giftCount = computed(() => {
    if (!form.gifts.length) {
        return 0;
    }
    return form.gifts.map(g => parseInt(g.quantity)).reduce((n, m) => n + m, 0);
});
</script>