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

defineProps({
    cancelButton: Boolean,
});

</script>
<script>


export default {
    methods: {
        Lookup(){
            debugger;
            this.LookupResult();

            axios.post(route('AmountLookup'), {
                state: this.userState,
                zipCode: this.userZip,
                amount: this.userAmount,
                city: this.userCity
            })
            .then((res) => {
                // console.log(res)
                this.amount = res.data + " should be collected for this transaction";
                this.LookupResult();

            })
        },
        LookupResult(){
            this.loading = !this.loading;
            this.message = !this.message;
        },
        HideTaxCalc(){
            this.$emit('calcHide')
        }
    },
    data() {
        return {
            states : ["Alaska", "Arkansas", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"],
            userState: "",
            userZip: "",
            userAmount: "",
            userCity: "",
            amount: "",
            counter: 0,
            loading: false,
            message: true,
            
        }
    }
}
</script>


<template>

    <h3 class="p-6 text-gray-900 dark:text-gray-100 text-2xl mx-auto">Sales Tax Calculator</h3>
    <div v-if="message" class="px-6 text-gray-900 dark:text-gray-100">{{ amount }}</div>
    <form @submit.prevent="Lookup" class="p-6 text-gray-900 dark:text-gray-100">
        <div class="mt-6 flex">
            <div class="w-1/2 p-2">
                <InputLabel for="state" value="State: " />
                <select
                    type='text' 
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-red-500 dark:focus:border-red-600 focus:ring-red-500 dark:focus:ring-red-600 rounded-md shadow-sm mt-1 block w-full"
                    v-model = "userState"
                    name="state"
                    id="state"
                    >

                    <option v-for="state in states"  :key=" state" :value="state" >{{ state }}</option>
                    
                </select> 
            </div>
            <div class="w-1/2 p-2">
                <InputLabel for="zipCode" value="Zip Code: " />
                <TextInput 
                    type='text' 
                    class="mt-1 block w-full"
                    v-model = "userZip"
                    name="zipCode"
                    id="zipCode"
                />
            </div>
        </div>
        <div class="mt-6 flex">
            <div class="w-1/2 p-2">
                <InputLabel for="city" value="City: " />
                <TextInput 
                    type='text' 
                    class="mt-1 block w-full"
                    v-model = "userCity"
                    name="city"
                    id="city"
                />
            </div>
            <div class="w-1/2 p-2">
                <InputLabel for="amount" value="Transaction Amount: " />
                <TextInput 
                    type='text' 
                    class="mt-1 block w-full"
                    v-model = "userAmount"
                    name="amount"
                    id="amount"
                />
            </div>
        </div>

        <div>
            <div class="grid grid-cols-2 mt-6 justify-center content-center">
                <div><SecondaryButton v-if="cancelButton" @click="HideTaxCalc()">Close</SecondaryButton></div>
                <div><PrimaryButton>Submit</PrimaryButton></div>
            </div>
        </div>
    </form>
    
    
    <div role="status"  v-if="loading" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 scale-150">
        <svg aria-hidden="true" class="inline w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-white fill-red-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg" >
            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
        </svg>
    <span class="sr-only">Loading...</span>
</div>
</template>