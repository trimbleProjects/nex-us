
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    // states: Object,
    nexus: Object,
    taxTotal: Number,
});

</script>

<template>
    <div class='grid-cols-3 text-xl text-gray-800 dark:text-gray-200 leading-tight'>
        <div class='grid even:bg-slate-50'>
            <div class="grid grid-cols-5 p-4 sticky top-0 bg-white dark:bg-gray-800">
                <div>State</div>
                <div>Transaction Amount</div>
                <div>Transactions</div>
                <div>Tax Collected</div>
            </div>
            <div 
                v-for="(nex, index) in nexus" 
                :key="index" 
                class="grid-row-color-offset p-4 m-4 rounded-md bg-white dark:bg-gray-800"
            >
                <component :is='Object.keys(nexus).length >1 ? "a" : "span"' 
                    class="grid grid-cols-5 p-2"
                    :href="route('Nexus')+ '/' + index"
                >
                    <h3>{{index}}</h3>
                    <div v-for="(val, str) in nex" :key="str">
                        {{ val == null ? 0 : val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')}}
                    </div>
                    <div v-if="Object.keys(nexus).length >1"><PrimaryButton >View</PrimaryButton></div>
            
                </component>

                
            </div>
        </div>
    </div>
</template>