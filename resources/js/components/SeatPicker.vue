<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    rows: { type: Number, default: 8 },
    cols: { type: Number, default: 4 },
    taken: { type: Array, default: () => [] },
    maxSelect: { type: Number, default: 1 },
    inputName: { type: String, default: 'seats' },
});

const selected = ref([]);

const grid = computed(() => {
    const seats = [];
    for (let r = 1; r <= props.rows; r++) {
        for (let c = 1; c <= props.cols; c++) {
            const label = `${String.fromCharCode(64 + r)}${c}`;
            seats.push(label);
        }
    }
    return seats;
});

function isTaken(seat) {
    return props.taken.includes(seat);
}

function toggle(seat) {
    if (isTaken(seat)) return;
    const i = selected.value.indexOf(seat);
    if (i >= 0) {
        selected.value.splice(i, 1);
        return;
    }
    if (selected.value.length >= props.maxSelect) {
        selected.value.shift();
    }
    selected.value.push(seat);
}

function seatClass(seat) {
    if (isTaken(seat)) return 'bg-zinc-300 text-zinc-500 cursor-not-allowed';
    if (selected.value.includes(seat)) return 'bg-emerald-600 text-white';
    return 'bg-white hover:bg-emerald-50 text-zinc-800 border border-zinc-300';
}
</script>

<template>
    <div class="space-y-3">
        <div class="text-xs text-zinc-500 flex gap-4">
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-white border border-zinc-300 inline-block rounded"></span>Available</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-emerald-600 inline-block rounded"></span>Selected</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-zinc-300 inline-block rounded"></span>Taken</span>
        </div>
        <div class="inline-grid gap-2" :style="{ gridTemplateColumns: `repeat(${cols}, minmax(0, 1fr))` }">
            <button
                v-for="seat in grid"
                :key="seat"
                type="button"
                :disabled="isTaken(seat)"
                @click="toggle(seat)"
                :class="['px-3 py-2 rounded text-sm font-medium transition', seatClass(seat)]"
            >
                {{ seat }}
            </button>
        </div>
        <input type="hidden" :name="inputName" :value="JSON.stringify(selected)" />
        <p class="text-sm text-zinc-600">
            Selected: <span class="font-semibold">{{ selected.join(', ') || 'none' }}</span>
        </p>
    </div>
</template>
