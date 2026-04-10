<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        links: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <nav v-if="links.length > 3" class="flex items-center justify-center space-x-1 mt-8">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                class="px-3 py-2 text-sm rounded-md"
                :class="{
                    'bg-indigo-600 text-white font-semibold': link.active,
                    'text-gray-700 hover:bg-gray-100': !link.active,
                }"
                v-html="link.label"
                preserve-scroll
            />
            <span
                v-else
                class="px-3 py-2 text-sm text-gray-400"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
