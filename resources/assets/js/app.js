
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
/**
 * Include directives
 */
require('./directives/v-link')
require('./directives/v-form-check')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('delay-table', require('./components/Delays.vue'));
Vue.component('analytics', require('./components/Analytics.vue'));
Vue.component('chat-message', require('./components/ChatMessage.vue'));
Vue.component('chat-head', require('./components/ChatHead.vue'));
Vue.component('chat-log', require('./components/ChatLog.vue'));
Vue.component('chat-composer', require('./components/ChatComposer.vue'));
Vue.component('admin-messages', require('./components/AdminMessage.vue'));

const app = new Vue({
    el: '#app',
    data: {
        messages: [],
        usersInRoom: [],
        chatId:""
    },
    methods: {
        addMessage(message) {
            newMessage = {
                message: message.message,
                user: {
                    first_name: "You",
                    surname: ""
                },
                chatId : this.chatId
            };
            // Add to existing messages
            this.messages.push(newMessage);
            // Persist to the database etc
            axios.post('/messages', newMessage).then(response => {
                // Do whatever;
            })
        }
    },
    created() {
        this.$on('getmessage', (item) =>{
            this.chatId = item.message.id
            axios.get('/messages/'+item.message.sender_id).then(response => {
                this.messages = response.data;
            });
        })
        axios.get('/messages').then(response => {
            if(is_admin == false){
                if( Object.keys(response.data).length > 1)
                {
                    this.chatId = response.data[0].chat_id;
                }
            }
            this.messages = response.data;
        });

        Echo.join('chatroom')
            .here((users) => {
                this.usersInRoom = users;
            })
            .joining((user) => {
                this.usersInRoom.push(user);
            })
            .leaving((user) => {
                this.usersInRoom = this.usersInRoom.filter(u => u != user)
            })
            .listen('MessagePosted', (e) => {
                this.messages.push({
                    message: e.message.message,
                    user: e.user
                });
            });
    }
});
