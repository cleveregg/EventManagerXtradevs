<script>
import { Head, Link } from '@inertiajs/vue3';
import CommonLayout from '@/Layouts/CommonLayout.vue';
import EventForm from '@/Components/EventForm.vue';

export default {
    components: {
        Head,
        Link,
        CommonLayout,
        EventForm,
    },
    props: {
        event: {
            type: Object,
            required: true,
        },
    },
};
</script>

<template>
    <Head title="Esemény szerkesztése" />

    <CommonLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Esemény szerkesztése
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                    <EventForm :event="event" />
                </div>
            </div>
        </div>
    </CommonLayout>
</template>
