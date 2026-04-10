<script>
export default {
    props: {
        modelValue: {
            type: String,
            required: true,
        },
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
    mounted() {
        if (this.$refs.input.hasAttribute('autofocus')) {
            this.$refs.input.focus();
        }
    },
};
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
