<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import moment from "moment";
import timezone from "moment-timezone";


const props = defineProps({
    transactions: Object,
});

</script>
<style scoped>

</style>
<script>
export default {
    methods: {
        TriggerCall(){
            var tranA, taxed, ident = "";
            tranA = event.target.parentElement.parentElement.nextElementSibling.textContent;
            taxed = event.target.parentElement.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.textContent;
            ident = event.target.id;
            this.$emit('eTrigger', tranA, taxed, ident);

        }
    }
}
</script>



<template>
    {{ editTrigger }}
    <div v-if="transactions">
        <div class='grid grid-cols-[1fr_1fr_1fr_1fr_1fr_120px] text-xl text-gray-800 dark:text-gray-200 leading-tight m-4'>
            <div class="grid justify-center content-center">Options</div>
            <div class="grid justify-center content-center">Transaction Amount</div>
            <div class="grid justify-center content-center">Rate</div>
            <div class="grid justify-center content-center">Taxed Amount</div>
            <div class="grid justify-center content-center">City / Zip</div>
            <div class="grid justify-center content-center">Time</div>
        </div>
        <div v-for="tran in transactions"
            :key="tran.id" 
            class='grid grid-cols-[1fr_1fr_1fr_1fr_1fr_120px]  text-xl text-gray-800 dark:text-gray-200 leading-tight m-4 grid-row-color-offset p-4 rounded-md justify-center content-center'
        >
            <div class="grid grid-cols-2 justify-center content-center">
                <div><PrimaryButton :id="tran.id" @click="TriggerCall">Edit</PrimaryButton></div>
                <div><DangerButton class="ml-3">Delete</DangerButton></div>

            </div>
            <div class="grid justify-center content-center">{{ tran.amount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}}</div>
            <div class="grid justify-center content-center">{{ tran.rate }}</div>
            <div class="grid justify-center content-center">{{ tran.tax_amount.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') }}</div>
            <div class="grid justify-center content-center">{{ tran.city }}<br/>{{ tran.zip_code }}</div>
            
            <div id="time" class="grid justify-center content-center">{{ moment.utc(tran.created_at).tz(timezone.tz.guess()).format("MM-DD-YYYY h:mm:ss a") }}</div>
        </div>
    </div>

</template>