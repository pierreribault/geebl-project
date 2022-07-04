<script setup>
import { reactive, defineProps, toRef } from 'vue';

const props = defineProps({
    count: Number
})

const state = reactive({
    count: toRef(props, 'count')
});

const emit = defineEmits(['plus', 'minus'])

const minus = () => {
    if (state.count > 0) {
        emit('minus', state.count - 1)
    }
}

const plus = () => {
    emit('plus', state.count + 1)
}

</script>

<template>
    <div class="flex justify-between items-center h-full w-full">
        <button class="text-white rounded-full h-8 w-8 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" @click="plus">+</button>
        <p class="text-white px-2">{{ state.count }}</p>
        <button class="text-white rounded-full h-8 w-8 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" @click="minus">-</button>
    </div>
</template>
