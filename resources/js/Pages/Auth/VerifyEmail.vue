<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
    components: {
        GuestLayout,
        PrimaryButton,
        Head,
        Link,
    },
    props: {
        status: {
            type: String,
        },
    },
    data() {
        return {
            form: useForm({}),
        };
    },
    computed: {
        verificationLinkSent() {
            return this.status === 'verification-link-sent';
        },
    },
    methods: {
        submit() {
            this.form.post(this.route('verification.send'));
        },
    },
};
</script>

<template>
    <GuestLayout>
        <Head title="E-mail hitelesítés" />

        <div class="mb-4 text-sm text-gray-600">
            Köszönjük a regisztrációt! Mielőtt elkezdenéd használni a fiókodat,
            kérjük, erősítsd meg az e-mail címedet az általunk küldött linkre kattintva.
            Ha nem kaptad meg az e-mailt, szívesen küldünk másikat.
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600"
            v-if="verificationLinkSent"
        >
            Új hiteleső link került elküdésre a regisztráció során megadott
            e-mail címre.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Hitelesítő e-mail újraküldése
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >Kijelentkezés</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
