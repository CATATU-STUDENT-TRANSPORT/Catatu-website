<script setup>
defineProps({
    start: { type: String, required: true },
    end: { type: String, required: true },
    departureTime: { type: String, default: null },
    price: { type: [String, Number], default: null },
});
</script>

<template>
    <div class="rounded-xl border border-zinc-200 bg-gradient-to-br from-emerald-50 to-sky-50 p-6">
        <div class="flex items-start gap-4">
            <div class="flex flex-col items-center pt-1">
                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                <span class="w-0.5 h-12 bg-zinc-300 my-1"></span>
                <span class="w-3 h-3 rounded-full bg-sky-600"></span>
            </div>
            <div class="flex-1 space-y-3">
                <div>
                    <p class="text-xs uppercase text-zinc-500">From</p>
                    <p class="font-semibold text-zinc-900">{{ start }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-zinc-500">To</p>
                    <p class="font-semibold text-zinc-900">{{ end }}</p>
                </div>
            </div>
            <div class="text-right">
                <p v-if="price !== null" class="text-2xl font-bold text-emerald-700">
                    KES {{ price }}
                </p>
                <p v-if="departureTime" class="text-sm text-zinc-600 mt-1">
                    {{ departureTime }}
                </p>
            </div>
        </div>
    </div>
</template>
