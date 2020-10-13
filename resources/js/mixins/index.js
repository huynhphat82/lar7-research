/**
 * Define a mixin
 */
const MyMixin = {
    data() {
        return {
            '$mixin': {
                test: this.$mixinTest,
                hello: this.$mixinHello,
            }
        };
    },
    created() {
        //
    },
    methods: {
        $mixinTest() {
            console.log('This is from mixin test');
        },
        $mixinHello() {
            console.log('This is from mixin hello');
        },
    }
};

export default MyMixin;

// import Vue from 'vue';

// register glabal mixin
// Vue.mixin({
//     created() {
//         var myOption = this.$options.myOption;
//         console.log('Global options => ', myOption);
//     },
// });

// methods, components, directives trong mixin sẽ được merge vào chung 1 object
// nếu keys trùng nhau, keys của component sẽ được ưu tiên
// new Vue({
//     mixins: [MyMixin],
//     data() {
//         return {
//             message: 'Hi',
//             name: 'Vue',
//         };
//     },
//     created() {
//         console.log(this.$data);
//         // { message: 'Hi', age: 10, name: 'Vue' }
//     },
//     myOption: 'My options',
// });
