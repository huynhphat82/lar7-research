<template>
    <vuejs-datepicker :format="format"></vuejs-datepicker>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import MyMixin from '../mixins/'

    export default {
        mixins: [MyMixin],
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
            // use mixin
            this.$data.$mixin.test();
            this.$mixinHello();
            // use plugin
            Vue.myGlobalMethod();
            this.$myMethod();
            this.$mixinPlugin();

            console.log('$http => ', this.$http);
            this.$http.get('https://jsonplaceholder.typicode.com/users').then(r => {
              console.log('r => ', r.data)
            })
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
