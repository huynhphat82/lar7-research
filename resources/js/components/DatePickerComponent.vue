<template>
    <vuejs-datepicker :format="format"></vuejs-datepicker>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';

console.log(Datepicker)
    export default {
        props: {
            format: {
                type: String,
                default: 'dd/MM/yyyy'
            },
        },
        data: function () {
            return {

            };
        },
        mounted() {
            console.log("datepicker component mounted");
        },
        components: {
            vuejsDatepicker: Datepicker
        },
        methods: {
            formmat: function() {
                return 'dd/MM/yyyy';
            }
        }
    };
</script>
