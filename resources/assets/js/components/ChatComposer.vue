<template>
    <div class="chat-composer">
        <input type="text" name="Type here..." v-model="messageMo" v-on:keydown.enter="sendMessage()">
        <button @click="sendMessage()   ">Send</button>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                messageMo: ""
            }
        },
        methods:{
            sendMessage(){
                this.$emit('messagesent', {
                    message: this.messageText,
                    author : "Hodor"
                });
                console.log(this.messageMo);
            }
        }
    }
</script>
