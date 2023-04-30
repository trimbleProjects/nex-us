<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    tranAmounts: Number,
    taxeds: Number,
    ident: String,
});

const form = useForm({
    editTranAmount: null,//document.querySelector("#tranAmount").value,
    taxed: null//this.taxed,
    // token: token
});

const submit = () => {
    form.put(route("EditTransaction"));
};

</script>

<script >

export default {
    data() {
        return {
            tranAmount : "",
            taxed: this.taxeds,
            ident : this.ident,
        }
    },
    mounted() {
        this.tranAmount = this.tranAmounts
    },

    methods: {
        Hide(){
            this.$emit('hide');
        },
        EditTransaction(){
            var editTranAmount, editTax, editId = 0;
            editTranAmount = document.querySelector("#tranAmount").value;
            editTax = document.querySelector("#taxed").value;
            // form.put(route('EditTransaction'), {
            //     preserveScroll: true,
            //     onSuccess: () => form.reset(),
            //     onError: () => {
            //         // if (form.errors.password) {
            //         //     form.reset('password', 'password_confirmation');
            //         //     passwordInput.value.focus();
            //         // }
            //         // if (form.errors.current_password) {
            //         //     form.reset('current_password');
            //         //     currentPasswordInput.value.focus();
            //         // }
            //     },
            // });

            this.$emit('edit', editTranAmount, editTax, this.ident)
            
            // axios.put(route('EditTransaction'), {
            //     editId: this.ident,
            //     editTranAmount: this.tranAmount,
            //     editTax: this.taxed,
                
            // });
            // .then({

            // })
            // .catch(error => console.log(error));
            // form.put(route('EditTransaction'), {
            //     preserveScroll: true,
            //     onSuccess: () => form.reset(),
            //     onError: () => {
            //         if (form.errors.city) {
            //             form.reset('city', 'That city does not exist in this state');
            //             // passwordInput.value.focus();
            //         }
            //         // if (form.errors.current_password) {
            //         //     form.reset('current_password');
            //         //     currentPasswordInput.value.focus();
            //         // }
            //     },
            // });
        }
    }
}
</script>

<template >
    <div id="edit" class="grid bg-white dark:bg-gray-800 text-xl text-gray-800 dark:text-gray-200 leading-tight p-5" >
        <h1>Edit Transaction</h1>
        <form @submit.prevent="EditTransaction">
        <!-- <form @submit.prevent="form.put(route('EditTransaction'))" > -->
            <div>
                <div class="mt-4">
                    <InputLabel for="amount" value="Transaction Amount: " />
                    <TextInput 
                        type='text' 
                        class="mt-1 block w-full"
                        :value = this.tranAmount
                        :v-model = this.tranAmount
                        name="tranAmount"
                        id="tranAmount"
                    />
                </div>
                <div class="mt-6">
                    <InputLabel for="taxed" value="Taxed Amount: " />
                    <TextInput 
                        type='text' 
                        class="mt-1 block w-full"
                        :value = this.taxed
                        v-model = this.taxed
                        name="taxed"
                        id="taxed"
                    />
                </div>
                <div>
                    <div class="grid grid-cols-2 mt-6 justify-center content-center">
                        <div><SecondaryButton @click="Hide">Close</SecondaryButton></div>
                        <div><PrimaryButton>Submit</PrimaryButton></div>
                    </div>
                </div>

            </div>
            <div></div>
            <div></div>
        </form>
    </div>
</template>



<style>



</style>