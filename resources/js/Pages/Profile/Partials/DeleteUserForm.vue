<script>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick } from 'vue';

export default {
    components: {
        DangerButton,
        InputError,
        InputLabel,
        Modal,
        SecondaryButton,
        TextInput,
    },
    data() {
        return {
            confirmingUserDeletion: false,
            form: useForm({
                password: '',
            }),
        };
    },
    methods: {
        confirmUserDeletion() {
            this.confirmingUserDeletion = true;
            nextTick(() => this.$refs.passwordInput.focus());
        },
        deleteUser() {
            this.form.delete(this.route('profile.destroy'), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onError: () => this.$refs.passwordInput.focus(),
                onFinish: () => this.form.reset(),
            });
        },
        closeModal() {
            this.confirmingUserDeletion = false;
            this.form.clearErrors();
            this.form.reset();
        },
    },
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Fiók törlése
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                A fiók törlése után minden erőforrás és adat véglegesen törlődik.
                A fiók törlése előtt kérjük, mentse el az összeset adatot,
                amelyet meg szeretné tartani.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Fiók törlése</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    Biztosan törölni szeretné a fiókját?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    A fiók törlése után minden erőforrás és adat véglegesen
                    törlődik. Kérjük, adja meg a jelszavát a fiók végleges
                    törlésének megerősítéséhez.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Jelszó"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Jelszó"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Mégse
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Fiók törlése
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
