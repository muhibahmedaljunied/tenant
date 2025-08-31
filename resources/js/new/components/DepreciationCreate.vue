<script setup>
    import Select2 from 'vue3-select2-component'
    import { ref } from 'vue';

    const options = ref([
        {id: 1,text: "Hi"},
    ])

    const myValue = ref("")
</script>
<template>
    <form action=''>
        <div class='form-group'>
            <label for='reference_number'>رقم المرجع</label>
            <input type='text' class='form-control'/>
        </div>
        <div class='form-group'>
            <label for='asset_group'>تصنيف الاصل</label>
        </div>
        <div class='form-group'>
            <Select2 v-model='myValue' :options='options'/>
        </div>
    </form>
</template>
