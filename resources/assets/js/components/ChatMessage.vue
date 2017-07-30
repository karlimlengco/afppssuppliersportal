<template lang="html">

<!-- chat support -->
    <div class="chat chat-message">
        <div class="chat__head">
            <div class="chat__head__title">Super Chat</div>
            <div class="chat__head__utility">
                <!-- <button class="chat__head__utility__button minimize-chat">
                    <i class="nc-icon-mini ui-1_simple-delete"></i>
                </button> -->
                <button class="chat__head__utility__button close-chat">
                    <i class="nc-icon-mini ui-1_simple-remove"></i>
                </button>
            </div>
        </div>
        <div class="chat__thread">
            <div class="chat__thread__item">
                <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                <span class="chat__thread__info">
                    <span class="chat__thread__name">{{ message.user.first_name }}</span>
                    <span class="chat__thread__message">{{ message.message }}</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message']
}
</script>

<style lang="css">
.chat-message {
    padding: 1rem;
}

.chat-message > p {
    margin-bottom: .5rem;
}
</style>
