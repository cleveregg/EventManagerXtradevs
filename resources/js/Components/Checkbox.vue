<script>
export default {
    emits: ['update:checked'],
    props: {
        checked: {
            type: [Array, Boolean],
            required: true,
        },
        value: {
            default: null,
        },
    },
    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },
            set(val) {
                this.$emit('update:checked', val);
            },
        },
    },
};
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
