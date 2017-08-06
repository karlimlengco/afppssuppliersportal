<template>
<div class=" ">
    <div class="row">
        <div class="six columns">
            <h1>Delay Notifications</h1>
        </div>
        <div class="six columns">
            <input type="text" v-model="search" @keyup.enter="searching" class="input" placeholder="Search UPR">
        </div>
    </div>

    <div class="table-scroll">
        <table class="table table--with-border ">
            <thead>
                <tr>
                   <th>UPR Number</th>
                   <th>Ref Number</th>
                   <th>Name</th>
                   <th>ABC</th>
                   <th>Next Step</th>
                   <th>Expected Date</th>
                   <th>Number of Delays</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <tr>
                        <td>{{item.upr_number}}</td>
                        <td>{{item.ref_number}}</td>
                        <td>{{item.project_name}}</td>
                        <td>{{formatPrice(item.total_amount)}}</td>
                        <td>{{item.next_step}}</td>
                        <td>{{item.next_due}}</td>
                        <td>{{item.delay}}</td>
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
                search:""
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
            this.fetchItems(this.pagination.current_page, this.search);
        },

        methods: {
            searching(){
                this.fetchItems(this.pagination.current_page, this.search);
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            changePage: function (page) {
                this.pagination.current_page = page;
                this.fetchItems(this.pagination.current_page);
            },
            fetchItems(page, value) {
                $.ajax({
                    url: '/upr-delays/api?page='+page+'&&search='+value,
                    success: (response) => {
                       this.items = response.data.data
                       this.pagination = response.pagination
                    }
                });
            },
        }
    }
</script>
