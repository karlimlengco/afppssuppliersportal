<template lang="html">
  <div class="chat__compose">
      <textarea required maxlength="355" @keyup.enter="sendMessage" v-model="messageText" class="chat__compose__textarea" name="" id="" cols="30" rows="3" placeholder="Write your message here"></textarea>
      <div class="chat__compose__utility">
          <span class="chat__compose__word-count">{{charactersLeft}}</span>
          <span class="chat__compose__options">
              <a href="#" class="chat__compose__options__item" @click.prevent="sendMessage">Send</a>
          </span>
      </div>
  </div>
</template>

<script>
export default {

    data() {
        return{
            messageText: ""
        }
    },

    methods: {
        sendMessage() {
            this.$emit('messagesent', {
                message: this.messageText,
                user: {
                    first_name: $('.navbar-right .dropdown-toggle').text()
                }
            });
            this.messageText = '';
        }
    },

    computed: {
        charactersLeft() {
            var chars = this.messageText.length,
                limit = 355;

            return limit - chars;
          }
    }
}
</script>

