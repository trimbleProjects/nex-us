<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { nextTick, ref } from 'vue';

const form = useForm({
    state: '',
    zipCode: '',
    amount: '',
    city: ''
});

const updatePassword = () => {
    axios.get(route('AmountLookup'), {
        state: $("#state").val(),
        zipCode: $("zipCode").val(),
        amount: $("#amount").val(),
        city: $("#city").val()

        // preserveScroll: true,
        // onSuccess: () => form.reset(),

    // nextTick(() => passwordInput.value.focus());
    })
    .then((res) => {
        console.log(res)
    })
};

</script>
<script>


export default {
    methods: {
        Lookup(){
            axios.get(route('AmountLookup'), {
            state: $("#state").val(),
            zipCode: $("zipCode").val(),
            amount: $("#amount").val(),
            city: $("#city").val()
            })
            .then((res) => {
                console.log(res)
            })
        }
    }
}
</script>


<template>

    <h3>How much tax to collect</h3>
    <form @submit.prevent="Lookup">
        <div class="mt-6">
            <InputLabel for="state" value="State: " />
            <TextInput 
                type='text' 
                class="mt-1 block w-full"
                v-model = form.state 
                name="state"
                id="state"
            />
        </div>
        <div class="mt-6">
            <InputLabel for="zipCode" value="Zip Code: " />
            <TextInput 
                type='text' 
                class="mt-1 block w-full"
                v-model = form.zipCode
                name="zipCode"
                id="zipCode"
            />
        </div>
        <div class="mt-6">
            <InputLabel for="city" value="City: " />
            <TextInput 
                type='text' 
                class="mt-1 block w-full"
                v-model = form.city
                name="city"
                id="city"
            />
        </div>
        <div class="mt-6">
            <InputLabel for="amount" value="Transaction Amount: " />
            <TextInput 
                type='text' 
                class="mt-1 block w-full"
                v-model = form.amount
                name="amount"
                id="amount"
            />
        </div>
        <div>
            <div class="grid grid-cols-2 mt-6 justify-center content-center">
                <div></div>
                <div><PrimaryButton>Submit</PrimaryButton></div>
            </div>
        </div>
    </form>
</template>