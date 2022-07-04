<script setup>
import { reactive, defineProps } from 'vue';

const state = reactive({
    count: 0
});

const emit = defineEmits(['plus', 'minus'])

const minus = () => {
    if (state.count > 0) {
        state.count--;
        emit('minus', state.count)
    }
}

const plus = () => {
    state.count++;
    emit('plus', state.count)
}
</script>

<template>
    <div class="flex justify-between items-center h-full w-full">
        <button class="text-white rounded-full h-8 w-8 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" @click="plus">+</button>
        <p class="text-white px-2">{{ state.count }}</p>
        <button class="text-white rounded-full h-8 w-8 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" @click="minus">-</button>
    </div>
</template>
