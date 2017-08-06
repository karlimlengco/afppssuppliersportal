<template lang="html">

<!-- chat support -->
        <div>
            <div v-bind:class="computedUser">
                <span class="chat__thread__avatar">
                    <img  v-bind:src="computedAvatar" alt="">
                </span>
                <span class="chat__thread__info">
                    <span class="chat__thread__name">{{ computedName }}</span>
                    <span class="chat__thread__message">{{ message.message }}</span>
                </span>
            </div>
        </div>
</template>

<script>
export default {
    props: ['message'],
    computed: {
        computedAvatar: function () {
            if(this.message.user.avatar == null){
                return "/img/user.png";
            }

            return "/avatars/"+this.message.user.avatar;
        },
        computedUser: function () {
            if(this.message.user.id != currentUser.id){
                return "chat__thread__item";
            }
                return "chat__thread__item chat__thread__item--you";

        },
        computedName: function () {
            if(this.message.user.id != currentUser.id){
                return this.message.user.first_name +" "+ this.message.user.surname;
            }
                return "You";

        }
    }
}
</script>
