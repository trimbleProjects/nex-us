<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import StatesVue from './Nexus/States.vue';
import Transactions from './Nexus/Transactions.vue';
import Popup from './Nexus/Popup.vue';
import { ref } from 'vue';

// let $ = JQuery


defineProps({
    // states: Object,
    nexus: Array,
    transactions: Array,
    // taxTotal: Number,
});

</script>

<script >



export default {
    data() {
        return {
            editTrigger : false,
            tranAmount : "",
            taxed : "",
            id: "",
        }
    },
    methods: {
        EditTriggerValue(tranA, taxes, id){
            this.editTrigger = true;
            this.tranAmount = tranA;
            this.taxed = taxes;
            this.ident = id;
        },
        HidePopup(){
            this.editTrigger = false;
        },
        EditTransaction(tranAmount, tax, identity){
            axios.post(route('EditTransaction'), {
                editId: identity,
                editTranAmount: tranAmount,
                editTax: tax,
                preserveState: true,
            })
            .then(
                this.editTrigger = false,
                // $("#stateNexus").load(location.href + " #stateNexus"),
                // $("#transacationReload").load(location.href + " #transacationReload"),
                location.reload()
                // this.$forceUpdate(),
                // popupKey.value += 1,
                // this.fuckyou += 1,
             );
             
             
        }
    }
    
}
</script>



<template #header class="dark:bg-gray-900">
    <Head title="Nexus" />
    <AuthenticatedLayout>

            <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg m-4" id="stateNexus">
                <StatesVue :nexus="nexus" />
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg m-4">
                <Transactions :transactions="transactions" @eTrigger="EditTriggerValue" id="transacationReload"/>
            </div>
            
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg m-4 shadow-[0_15px_48px_-15px_rgba(255,255,255,1)]" v-if="editTrigger" id="edit-form" >
                <Popup 
                    @hide="HidePopup"
                    @edit="EditTransaction"
                    :tranAmounts="tranAmount"
                    :taxeds="taxed"
                    :ident="ident"
                    
                />
            </div>


        

    </AuthenticatedLayout>
</template>

<style scoped>

#edit-form{
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>