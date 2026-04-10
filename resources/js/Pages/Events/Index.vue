<script>
import { Head, Link } from '@inertiajs/vue3';
import EventCard from '@/Components/EventCard.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

export default {
    components: {
        Head,
        Link,
        EventCard,
        Pagination,
        FlashMessage,
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

    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-bold text-gray-800">
                            Események
                        </Link>
                    </div>

                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="route('events.create')"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                            >
                                Új esemény
                            </Link>
                            <span class="text-sm text-gray-700">{{ $page.props.auth.user.name }}</span>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm text-gray-700 hover:text-gray-900"
                            >
                                Kijelentkezés
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm text-gray-700 hover:text-gray-900"
                            >
                                Bejelentkezés
                            </Link>
                            <Link
                                :href="route('register')"
                                class="text-sm text-gray-700 hover:text-gray-900"
                            >
                                Regisztráció
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <FlashMessage :flash="$page.props.flash" />

        <!-- Content -->
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Közelgő események</h1>

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
</template>
