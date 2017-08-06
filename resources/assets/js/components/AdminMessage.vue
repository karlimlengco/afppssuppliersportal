<template>
<div class=" ">
    <div class="row">
        <div class="six columns">
            <!-- <h1>Messages</h1> -->
        </div>
    </div>

    <div class="table-scroll">
        <table class="table table--with-border ">
            <tbody>
                <template v-for="(item, index) in items">
                    <tr @click.prevent="viewChat(item)" style="cursor:pointer">
                        <td style='text-align:left'>
                            <span style="float:left;margin-right:10px">
                                <img v-bind:src="avatar(item)" alt="" style="width:50px">
                            </span>

                            <p style="line-height:30px;margin-bottom:0">{{item.sender.first_name}} {{item.sender.middle_name}} {{item.sender.surname}}</p>
                            <p style="line-height:5px;margin-bottom:0" v-if="item.last_message != null">{{item.last_message.created_at}}</p>
                        </td>
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

<script>

    export default {
        data() {
            return{
                items: [],
                pagination: {
                    total: 0,
                    per_page:1,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                chatHead:""
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

        mounted() {
            this.fetchItems(this.pagination.current_page);
        },

        methods: {
            avatar(item){
                if(item.sender.avatar == null){
                    return '/img/user.png';
                }
                return '/avatars/'+item.sender.avatar;
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            changePage: function (page) {
                this.pagination.current_page = page;
                this.fetchItems(this.pagination.current_page);
            },
            fetchItems(page) {
                $.ajax({
                    url: '/admin-messages/api?page='+page,
                    success: (response) => {
                       this.items = response.data.data
                       this.pagination = response.pagination
                    }
                });
            },
            viewChat(item){
                $('.chat').addClass('is-visible');
                $('#chatHead').html(item.sender.first_name);
                this.$parent.$emit('getmessage', {
                    message: item
                });
            }
        }
    }
</script>
