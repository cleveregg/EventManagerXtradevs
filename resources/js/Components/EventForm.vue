<script>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
    components: {
        InputError,
        InputLabel,
        TextInput,
        PrimaryButton,
    },
    props: {
        event: {
            type: Object,
            default: null,
        },
    },
    data() {
        const form = useForm({
            name: this.event?.name ?? '',
            description: this.event?.description ?? '',
            date: this.event?.date ? this.formatDateForInput(this.event.date) : '',
            attendee_limit: this.event?.attendee_limit ?? '',
            image: null,
        });

        return {
            form,
            imagePreview: this.event?.image ? '/storage/' + this.event.image : null,
        };
    },
    computed: {
        isEditing() {
            return !!this.event;
        },
        submitLabel() {
            return this.isEditing ? 'Mentés' : 'Létrehozás';
        },
    },
    methods: {
        formatDateForInput(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        },
        handleImageChange(e) {
            const file = e.target.files[0];
            this.form.image = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        submit() {
            if (this.isEditing) {
                this.form.post(route('events.update', this.event.id), {
                    _method: 'PUT',
                    forceFormData: true,
                });
            } else {
                this.form.post(route('events.store'), {
                    forceFormData: true,
                });
            }
        },
    },
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div>
            <InputLabel for="name" value="Esemény neve" />
            <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                required
            />
            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="description" value="Leírás" />
            <textarea
                id="description"
                v-model="form.description"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                rows="4"
            ></textarea>
            <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <div>
            <InputLabel for="date" value="Dátum" />
            <TextInput
                id="date"
                v-model="form.date"
                type="datetime-local"
                class="mt-1 block w-full"
                required
            />
            <InputError class="mt-2" :message="form.errors.date" />
        </div>

        <div>
            <InputLabel for="attendee_limit" value="Férőhelyek száma" />
            <TextInput
                id="attendee_limit"
                v-model="form.attendee_limit"
                type="number"
                min="1"
                class="mt-1 block w-full"
                required
            />
            <InputError class="mt-2" :message="form.errors.attendee_limit" />
        </div>

        <div>
            <InputLabel for="image" value="Kép" />
            <input
                id="image"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                @change="handleImageChange"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
            />
            <InputError class="mt-2" :message="form.errors.image" />

            <div v-if="imagePreview" class="mt-3">
                <img
                    :src="imagePreview"
                    alt="Kép előnézet"
                    class="h-48 w-auto rounded-md object-cover"
                />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">
                {{ submitLabel }}
            </PrimaryButton>
            <span v-if="form.processing" class="text-sm text-gray-500">
                Feldolgozás...
            </span>
        </div>
    </form>
</template>
