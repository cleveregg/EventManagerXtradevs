<script>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';

export default {
    components: {
        Head,
        Link,
        AuthenticatedLayout,
        DangerButton,
    },
    props: {
        event: {
            type: Object,
            required: true,
        },
    },
    computed: {
        formattedDate() {
            const date = new Date(this.event.date);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}.${month}.${day}. ${hours}:${minutes}`;
        },
        imageUrl() {
            if (this.event.image) {
                return '/storage/' + this.event.image;
            }
            return null;
        },
        availableSpots() {
            return this.event.attendee_limit - (this.event.registrations_count || 0);
        },
        isFull() {
            return this.availableSpots <= 0;
        },
        isCreator() {
            const user = this.$page.props.auth.user;
            return user && user.id === this.event.user_id;
        },
        isLoggedIn() {
            return !!this.$page.props.auth.user;
        },
        isRegistered() {
            const ids = this.$page.props.auth.registeredEventIds || [];
            return ids.includes(this.event.id);
        },
    },
    methods: {
        deleteEvent() {
            if (confirm('Biztosan törölni szeretné ezt az eseményt?')) {
                router.delete(route('events.destroy', this.event.id));
            }
        },
        register() {
            router.post(route('events.register', this.event.id), {}, {
                preserveScroll: true,
            });
        },
    },
};
</script>

<template>
    <Head :title="event.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ event.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link :href="route('events.index')" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        &larr; Vissza az eseményekhez
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <!-- Image -->
                <div v-if="imageUrl" class="h-64 sm:h-80 w-full overflow-hidden flex items-center justify-center">
                    <img
                        :src="imageUrl"
                        :alt="event.name"
                        class="h-full object-contain"
                    />
                </div>
                <div v-else class="h-64 sm:h-80 w-full bg-gray-200 flex items-center justify-center">
                    <svg
                        class="h-24 w-24 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                        />
                    </svg>
                </div>

                <div class="p-6 sm:p-8">
                    <div class="flex items-start justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">{{ event.name }}</h1>

                        <div v-if="isCreator" class="flex items-center space-x-3 ml-4">
                            <Link
                                :href="route('events.edit', event.id)"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                            >
                                Szerkesztés
                            </Link>
                            <DangerButton @click="deleteEvent">
                                Törlés
                            </DangerButton>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                        <span>{{ formattedDate }}</span>
                        <span>&bull;</span>
                        <span v-if="event.user">Szervező: {{ event.user.name }}</span>
                    </div>

                    <div class="mt-2">
                        <span
                            v-if="isFull"
                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800"
                        >
                            BETELT
                        </span>
                        <span
                            v-else
                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                        >
                            {{ availableSpots }} / {{ event.attendee_limit }} szabad hely
                        </span>
                    </div>

                    <div v-if="event.description" class="mt-6 prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ event.description }}</p>
                    </div>

                    <div class="mt-6" v-if="isLoggedIn">
                        <button
                            v-if="isRegistered"
                            disabled
                            class="rounded-md bg-gray-300 px-4 py-2 text-sm font-semibold text-gray-500 cursor-not-allowed"
                        >
                            Már jelentkeztél
                        </button>
                        <button
                            v-else-if="isFull"
                            disabled
                            class="rounded-md bg-red-100 px-4 py-2 text-sm font-semibold text-red-800 cursor-not-allowed"
                        >
                            BETELT
                        </button>
                        <button
                            v-else
                            @click="register"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                        >
                            Jelentkezem
                        </button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
