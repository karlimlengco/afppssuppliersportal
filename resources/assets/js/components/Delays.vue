<template>
<div class=" ">

    <div class="modal" id="vue-modal">
        <div class="modal__dialogue modal__dialogue--round-corner">
                <button type="button" class="modal__close-button">
                    <i class="nc-icon-outline ui-1_simple-remove"></i>
                </button>


                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Justification for {{uprName}} </h1>
                </div>

                <div class="modal__dialogue__body">
                    <div class="row">
                        <label>Justification</label>
                        <textarea class="textarea" v-model="model.remarks"></textarea>
                    </div>
                    <div class="row">
                        <label>Action Taken</label>
                        <textarea class="textarea" v-model="model.action"></textarea>
                    </div>
                </div>

                <div class="modal__dialogue__foot">
                    <button class="button" @click.prevent="updateModel()" >Proceed</button>
                </div>

        </div>
    </div>


    <div class="row">
        <div class="six columns">
            <h1>Delay Notifications</h1>
        </div>
    </div>

    <div class="table-scroll">
        <table class="table table--with-border ">
            <thead>
                <tr>
                   <th>UPR Number</th>
                   <th>Project Name/ Activity</th>
                   <th>ABC</th>
                   <th>Next Step</th>
                   <th>Expected Date</th>
                   <th>Number of Delays</th>
                   <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in items">
                    <tr>
                        <td>{{item.upr_number}}</td>
                        <td>{{item.project_name}}</td>
                        <td>{{formatPrice(item.total_amount)}}</td>
                        <td>{{item.next_step}}</td>
                        <td>{{item.next_due}}</td>
                        <td>{{item.delay}}</td>
                        <td @click.prevent="showModal(item)" style="cursor:pointer" tooltip="Justification" position="left"> <span class="nc-icon-mini education_notepad"></span></td>
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
                items: [],
                pagination: {
                    total: 0,
                    per_page:1,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                search:"",
                uprName:"",
                model: {
                    remarks: '',
                    action: '',
                    _token: csrf_token
                },
                upr_id : "",
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
                this.fetchItems(this.pagination.current_page, this.$root.searchText);
            })
        },

        mounted() {
            this.fetchItems(this.pagination.current_page, this.search);
        },

        methods: {
            searching(){
                alert(this.$root.searchText);
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
              if(value == 'undefined' || value == null || value == ''){
                $.ajax({
                    url: '/upr-delays/api?page='+page,
                    success: (response) => {
                       this.items = response.data.data
                       this.pagination = response.pagination
                    }
                });
              }else
              {
                $.ajax({
                    url: '/upr-delays/api?page='+page+'&search='+value,
                    success: (response) => {
                       this.items = response.data.data
                       this.pagination = response.pagination
                    }
                });
              }

            },
            showModal(item){
                this.upr_id  = item.id
                this.uprName = item.upr_number
                $('.modal').addClass('is-visible');
            },
            updateModel(){
                axios.put('/unit-purchase-requests/justification/' + this.upr_id, this.model)
                $('.modal').removeClass('is-visible');
                // this.model.remarks = ""
                // this.model.action = ""
            }

        }
    }
</script>
