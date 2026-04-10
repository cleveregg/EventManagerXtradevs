<script>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EventCard from '@/Components/EventCard.vue';
import Pagination from '@/Components/Pagination.vue';

export default {
    components: {
        Head,
        Link,
        AuthenticatedLayout,
        EventCard,
        Pagination,
    },
    props: {
        events: {
            type: Object,
            required: true,
        },
    },
};
</script>

<template>
    <Head title="Események" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Közelgő események
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-if="events.data.length"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <EventCard
                        v-for="event in events.data"
                        :key="event.id"
                        :event="event"
                    />
                </div>

                <div v-else class="text-center py-12">
                    <p class="text-gray-500 text-lg">Jelenleg nincsenek közelgő események.</p>
                </div>

                <Pagination :links="events.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
