<template>
<div class=" ">


    <div class="row">
        <div class="six columns align-left">
            <h3> </h3>
        </div>
        <div class="six columns utility utility--align-right" >
            <a class="button" href="#" @click.prevent='changeType' v-bind:tooltip="tooltipType"><i class="nc-icon-mini arrows-1_refresh-69"></i></a>
            <a class="button" href="/settings/users/create" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
        </div>
    </div>


    <div class="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full name</th>
                    <th>Designation</th>
                    <th>Unit</th>
                    <th>Email</th>
                    <th>Contact #</th>
                    <th>Gender</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in users">

                    <tr >
                        <td><a v-bind:href="showUrl(item)">{{item.username}}</a></td>
                        <td>{{item.full_name}}</td>
                        <td>{{item.designation}}</td>
                        <td>{{item.unit_name}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.contact_number}}</td>
                        <td>{{item.gender}}</td>
                        <td @click.prevent="viewChat(item)" class="pointer"><a href="#"><i class="nc-icon-mini ui-2_chat-round"></i></a></td>
                    </tr>
                </template>

            </tbody>
        </table>
        <div class="pagination">

            <div class="pagination__list pagination__list--solid">
                    <a href=""
                    v-bind:class="[ pagination.current_page > 1 ? 'pagination__list__item' : 'pagination__list__item pagination__list__item--disabled']"
                    @click.prevent="changePage(pagination.current_page - 1)">
                        <i class="nc-icon-outline arrows-1_tail-left"></i>
                    </a>
                    <template v-for="page in pagesNumber">
                        <a href="#" @click.prevent="changePage(page)"  v-bind:class="[ page == isActived ? 'pagination__list__item pagination__list__item--active' : 'pagination__list__item']">{{page}}</a>
                    </template>
                    <a href=""
                        v-bind:class="[ pagination.current_page < pagination.last_page ? 'pagination__list__item' : 'pagination__list__item pagination__list__item--disabled']"
                        @click.prevent="changePage(pagination.current_page + 1)">
                        <i class="nc-icon-outline arrows-1_tail-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<style>
    #vue-modal{
        left:0;
        top:0;
        bottom:0;
        right:0;
    }
</style>

<script>

    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    export default {

        data() {
            return{
                chatId: "",
                users: [],
                pagination: {
                    total: 0,
                    per_page:1,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                upr_id : "",
                type : "",
                tooltipType : "Archives",
            }
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {

                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        created() {
            this.$root.$on('searchingText', (item) =>{
                this.fetchItems(this.pagination.current_page, this.$root.searchText, this.type);
            })
        },

        mounted() {
            this.fetchItems(this.pagination.current_page, this.$root.searchText, this.type);
        },

        methods: {
            changePage: function (page) {
                this.pagination.current_page = page;
                this.fetchItems(this.pagination.current_page, this.$root.searchText, this.type);
            },
            changeType: function (type) {
                if(this.type == ''){
                    this.type = 'trash'
                    this.tooltipType = 'Active'
                }else{
                    this.type = ''
                    this.tooltipType = 'Archives'
                }

                this.fetchItems(this.pagination.current_page, this.$root.searchText, this.type);
            },
            fetchItems(page, value, type = "") {
                $.ajax({
                    url: '/api/users?page='+page+'&&search='+value+'&&trash='+type,
                    success: (response) => {
                       this.users = response.data.data
                       this.pagination = response.pagination
                    }
                });
            },
            showUrl: function(user){
                return "/settings/users/"+user.username;
            },
            viewChat(item){
                $('.chat').addClass('is-visible');
                $('#chatHead').html(item.full_name);
                axios.get('/api/user-message/'+item.id)
                    .then(response => {
                        this.$parent.$emit('getmessage', {
                            message: {
                                sender_id: currentUser.id,
                                id: response.data,
                                receiver_id : item.id
                        }
                    });
                });

                // console.log(this.chatId);

            }

        }
    }
</script>
