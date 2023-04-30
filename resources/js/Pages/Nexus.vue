<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import StatesVue from './Nexus/States.vue';


defineProps({
    // states: Object,
    nexus: Array,
});

</script>



<template>
    <Head title="Nexus" />
    <AuthenticatedLayout>
        <template #header>

            <StatesVue :nexus="nexus" />
        </template>


        

    </AuthenticatedLayout>
</template>