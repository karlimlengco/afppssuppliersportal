
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
Vue.component('user-lists', require('./components/UserLists.vue'));
Vue.component('delivery-order', require('./components/DeliveryOrder.vue'));

const app = new Vue({
    el: '#app',
    data: {
        messages: [],
        usersInRoom: [],
        chatId:"",
        receiverId:"",
        searchText:""
    },
    methods: {
        addMessage(message) {
            newMessage = {
                message: message.message,
                user: {
                    first_name: "You",
                    id: currentUser.id,
                    avatar: currentUser.avatar,
                    surname: ""
                },
                chatId : this.chatId,
                receiverId : this.receiverId

            };
            // Add to existing messages
            this.messages.push(newMessage);
            // Persist to the database etc
            axios.post('/messages', newMessage).then(response => {
                // Do whatever;
            })
        },
        searching(){
            this.$emit('searchingText', {
                searchText: this.searchText,
            });
        },
        getMessage(){
            axios.get('/messages').then(response => {
                if(is_admin == false){
                    if( Object.keys(response.data).length > 1)
                    {
                        this.chatId = response.data[0].chat_id;
                    }
                }
                this.messages = response.data;
            });
        }
    },
    created() {
        this.$on('getmessage', (item) =>{
            console.log(item);
            this.chatId = item.message.id
            this.receiverId = item.message.receiver_id
            if(item.message.receiver_id != null){
                axios.get('/messages/'+item.message.sender_id+'/'+item.message.receiver_id).then(response => {
                    this.messages = response.data;
                });
            }
            else{
                axios.get('/messages/'+item.message.sender_id).then(response => {
                    this.messages = response.data;
                });
            }
        })

        this.getMessage();

        Echo.join('chatroom')
            .here((users) => {
                this.usersInRoom = users;
                console.log('user here')
            })
            .joining((user) => {
                this.usersInRoom.push(user);
                console.log('user join')
            })
            .leaving((user) => {
                this.usersInRoom = this.usersInRoom.filter(u => u != user)
                console.log('user leave')
            })
            .listen('MessagePosted', (e) => {
                console.log('messagePosted')
                this.messages.push({
                    message: e.message.message,
                    user: e.user
                });
            });
    }
});
