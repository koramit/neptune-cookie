<template>
    <Head>
        <title>{{ giftEvent.title }}</title>
    </Head>
    <div class="mt-6 md:mt-12 bg-orange-200 shadow p-4 md:p-8">
        <h2 class="mb-4 py-2 border-b-2 border-green-500 border-dashed text-xl font-medium text-pink-400 flex justify-between items-center">
            {{ giftEvent.title }}
        </h2>
        <p class="my-4 text-orange-400 text-sm font-medium">
            ** กรณีมีผู้จับรางวัลพร้อมกันเป็นจำนวนมากแล้วได้ใบรางวัลซ้ำกันนั้น ขอสงวนสิทธิ์มอบรางวัลตามลำดับการจับ โดยผู้จับได้รางวัลก่อนมีสิทธิ์ก่อน
        </p>
        <p
            class="my-4 text-red-400 text-2xl font-medium"
            v-if="!giftEvent.can.access"
        >
            ท่านไม่สามารถเข้าร่วมงานได้ โปรดติดต่อผู้จัดงาน
        </p>
        <p
            class="my-4 text-red-400 text-2xl font-medium"
            v-else-if="!giftEvent.can.start"
        >
            งานยังไม่เริ่ม กรุณาลองใหม่ภายหน้า
        </p>
        <h3
            class="mb-4 py-2 text-2xl text-red-400"
            v-if="form.errors.number"
        >
            {{ form.errors.number }}
        </h3>
        <h3
            class="mb-4 py-2 text-2xl text-green-400"
            v-if="$page.props.gift"
        >
            {{ $page.props.gift }}
        </h3>
        <div
            class="grid grid-cols-2 gap-2 md:grid-cols-5 md:gap-4"
            v-if="(giftEvent.can.access && giftEvent.can.start) || giftEvent.organizer"
        >
            <div
                v-for="number in giftEvent.participantCount"
                :key="number"
                class="rounded shadow p-4 md:p-8"
                :class="{
                    'bg-white': !isDrewLabel(number),
                    'bg-green-200': isGiftLabel(number),
                    'bg-gray-300': isNoLuckLabel(number),
                }"
            >
                <div
                    class="mb-2 md:mb-4 font-medium text-xl text-center"
                    v-if="!isDrewLabel(number)"
                >
                    # {{ number }}
                </div>
                <div
                    v-else
                    class="mb-2 md:mb-4 font-medium text-md text-center"
                >
                    {{ getDrewLabel(number).name }}
                </div>
                <button
                    class="text-6xl text-center block w-full disabled:opacity-50"
                    v-if="!isDrewLabel(number)"
                    :disabled="!giftEvent.can.draw || form.processing || !giftEvent.can.start"
                    @click="draw(number)"
                >
                    <span
                        class="block"
                        :class="{
                            'animate-spin': form.processing
                        }"
                    >🍪</span>
                </button>
                <div
                    class="font-medium text-lg text-center"
                    v-else
                >
                    {{ getDrewLabel(number).gift }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3';
const props = defineProps({
    giftEvent: { type: Object, required: true }
});

const isDrewLabel = (number) => {
    return props.giftEvent.drewLabels.filter(l => l.number == number).length;
};

const isGiftLabel = (number) => {
    if (!isDrewLabel(number)) {
        return false;
    }

    return getDrewLabel(number).gift !== props.giftEvent.no_luck_label;
};

const isNoLuckLabel = (number) => {
    if (!isDrewLabel(number)) {
        return false;
    }

    return getDrewLabel(number).gift === props.giftEvent.no_luck_label;
};

const getDrewLabel = (number) => {
    return props.giftEvent.drewLabels.filter(l => l.number == number)[0] ?? null;
};

const form = useForm({
    number: null
});

const draw = (number) => {
    form.number = number;
    form.post(`/gift-events/${props.giftEvent.slug}/draw`);
};
</script>