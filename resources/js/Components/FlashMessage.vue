<script>
export default {
    props: {
        flash: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            visible: true,
        };
    },
    computed: {
        message() {
            return this.flash?.success || this.flash?.error || null;
        },
        type() {
            if (this.flash?.success) return 'success';
            if (this.flash?.error) return 'error';
            return null;
        },
    },
    watch: {
        flash: {
            handler() {
                this.visible = true;
            },
            deep: true,
        },
    },
    methods: {
        close() {
            this.visible = false;
        },
    },
};
</script>

<template>
    <div v-if="message && visible" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
        <div
            class="rounded-md p-4 flex items-center justify-between"
            :class="{
                'bg-green-50 text-green-800': type === 'success',
                'bg-red-50 text-red-800': type === 'error',
            }"
        >
            <span>{{ message }}</span>
            <button
                @click="close"
                class="ml-4 text-lg font-bold leading-none"
                :class="{
                    'text-green-600 hover:text-green-800': type === 'success',
                    'text-red-600 hover:text-red-800': type === 'error',
                }"
            >
                &times;
            </button>
        </div>
    </div>
</template>
