<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';


const user = usePage().props.value.auth.user;

const form = useForm({
    token: user.token,
});
// const props = defineProps({
//     bear: String,
// });
// props: ['token'];

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Generate API Key</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
               The key will not be viewable after refreshing the page.
            </p>
        </header>
        <form @submit.prevent="form.get(route('profiletoken.CreateToken'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="key" value="API Key:" />
                <TextInput 
                    type='text' 
                    class="mt-1 block w-full"
                    v-model= "usePage().props.value.token"
                />
            </div>
            <div>
                <PrimaryButton>Generate Key</PrimaryButton>
            </div>
        </form>
    </section>
</template>
