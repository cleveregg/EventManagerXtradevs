<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        event: {
            type: Object,
            required: true,
        },
    },
    computed: {
        truncatedDescription() {
            if (!this.event.description) return '';
            if (this.event.description.length <= 100) return this.event.description;
            return this.event.description.substring(0, 100) + '...';
        },
        formattedDate() {
            const date = new Date(this.event.date);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}.${month}.${day}. ${hours}:${minutes}`;
        },
        availableSpots() {
            return this.event.attendee_limit - (this.event.registrations_count || 0);
        },
        isFull() {
            return this.availableSpots <= 0;
        },
        imageUrl() {
            if (this.event.image) {
                return '/storage/' + this.event.image;
            }
            return null;
        },
    },
};
</script>

<template>
    <Link :href="route('events.show', event.id)" class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-200">
        <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
            <img
                v-if="imageUrl"
                :src="imageUrl"
                :alt="event.name"
                class="w-full h-full object-cover"
            />
            <svg
                v-else
                class="h-16 w-16 text-gray-400"
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

        <div class="p-4 flex flex-col flex-1">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ event.name }}</h3>

            <p class="text-sm text-gray-600 mb-3 flex-1">{{ truncatedDescription }}</p>

            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">{{ formattedDate }}</span>
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
                    {{ availableSpots }} szabad hely
                </span>
            </div>
        </div>
    </Link>
</template>
