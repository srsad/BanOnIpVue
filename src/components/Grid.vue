<template>
	<div class="x-panel-body x-panel-body-noheader">
		<table class="table">
			<thead>
				<tr>
					<th style="width:18px;">
						<input type="checkbox" 
								@click="selectAll" 
								v-model="allSelected">
					</th>
					<th>{{$lexicon('banonip_item_id')}}</th>
					<th>{{$lexicon('banonip_item_name')}}</th>
					<th>{{$lexicon('banonip_item_description')}}</th>
					<th>{{$lexicon('banonip_item_b')}}</th>
					<th>{{$lexicon('banonip_item_c')}}</th>
					<th>{{$lexicon('banonip_item_d')}}</th>
					<th>{{$lexicon('banonip_item_active')}}</th>
					<th>{{$lexicon('banonip_grid_actions')}}</th>
				</tr>
			</thead>

			<tbody>
				<tr v-for="(val, idx) in ipList" 
					@dblclick="callActioin('updateItem', val.id)"
					@click="select(val.id)"
					v-model="allSelected">
					<td>
						<input type="checkbox"
								class="table__item" 
								:value="val.id" 
								v-model="ids"
								@click="select">
					</td>
					<td>{{val.id}}</td>
					<td>{{val.name}}</td>
					<td>{{val.description}}</td>
					<td>
						<span class="green table__chek" v-if="val.b === true">
							<i class="icon icon-check"></i>
						</span>
					</td>
					<td>
						<span class="green table__chek" v-if="val.c === true">
							<i class="icon icon-check"></i>
						</span>
					</td>
					<td>
						<span class="green table__chek" v-if="val.d === true">
							<i class="icon icon-check"></i>
						</span>
					</td>
					<td>
						<div class="x-grid3-cell-inner x-grid3-col-7">
							<span v-if="val.active === true" class="green">{{$lexicon('yes')}}</span>
							<span v-else class="red">{{$lexicon('no')}}</span>
						</div>
					</td>
					<td>
						<button v-for="btn in val.actions" 
								:class="[btn.icon, 'banonip-btn banonip-btn-default']"
								@click="callActioin(btn.action, val.id)"
								:title="btn.title"
								v-if="btn.button === true"
								>
						</button>
					</td>
				</tr>

			</tbody>
		</table>

		<ul class="pagination">
			<li>
				<button type="button" class="x-btn x-btn-small" @click="firstPage">
					<i class="x-btn-text x-tbar-page-first"></i>&nbsp;
				</button>
			</li>
			<li>
				<button type="button" class=" x-btn x-btn-small" @click="prevPage">
					<i class="x-btn-text x-tbar-page-prev"></i>&nbsp;
				</button>
			</li>
			<li>Страница</li>
			<li>
				<input type="text" 
						class="x-form-text x-form-field x-form-num-field x-tbar-page-number" 
						v-model.numbe="page" 
						@keyup.enter="selectPage">
			</li>
			<li>из {{totalPage}}</li>
			<li>
				<button type="button" class=" x-btn x-btn-small" @click="nextPage">
					<i class="x-btn-text x-tbar-page-next"></i>&nbsp;
				</button>
			</li>
			<li>
				<button type="button" class=" x-btn x-btn-small" @click="lastPage">
					<i class="x-btn-text x-tbar-page-last"></i>&nbsp;
				</button>
			</li>
			<li>
				<button type="button" class=" x-btn x-btn-small" @click="getList">
					<i class="x-btn-text x-tbar-loading"></i>&nbsp;
				</button>
			</li>
			<li>На странице: </li>
			<li>
				<input type="text" 
						class="x-form-text x-form-field x-form-num-field x-tbar-page-number" 
						v-model.number="limit"
						@keyup.enter="updLimit">
			</li>
		</ul>

	</div>
</template>

<script>
import { bus } from '../main.js'

export default {
	data () {
		return {
			ipList: {},
			page: 1,
			start: 0,
			limit: 20,
			total: 0,
			totalPage: 1,
			ids: [],
			allSelected: false
		}
	},
	methods: {
		getList (start, limit, queryItem) {
			start = start || this.start;
			limit = limit || this.limit;

			let bodyFormData = new FormData();
				bodyFormData.set('action', 'mgr/item/getlist');
				bodyFormData.set('start', start);
				bodyFormData.set('limit', limit);

			if (queryItem !== undefined && queryItem != '') {
				bodyFormData.set('query', queryItem);
			}

			this.$http.post(BanOnIP.connector_url, bodyFormData, this.$httpConfig)
				.then( response => {
					if (response.data.success === true) {
						this.total = response.data.total;
						this.ipList = response.data.results;
						this.start = Math.ceil(this.page * Object.keys(response.data.results).length);
						const count = Math.ceil(response.data.total/this.limit);
						this.totalPage = count === 0 ? 1 : count;
					} else {
						console.log(this.$lexicon(response.data.message));
					}
				})
				.catch( error => { console.log(error); });

		},
		callActioin (action, ids) {
			if (['disableItem','enableItem','removeItem'].indexOf(action) != -1){
				this.$emit('multiAction', {action, ids});
			} else if (action === 'updateItem') {
				this.$emit(action, ids); // отдаем родителю id`шку, чтоб вызвать ее в модалке
			}
		},
		selectAll () {
			this.ids = [];
			if (this.allSelected === false) {
				for (let item in this.ipList) {
					if (this.ipList[item].id != undefined) {
						this.ids.push(this.ipList[item].id);
					}
				}
			}
		},
		select (val) {
			this.allSelected = false;
		},
		prevPage () {
			if (this.page > 1) {
				this.page--;
				this.start = this.page === 1 ? 0 : Math.ceil(this.page / Object.keys(this.ipList).length);
				this.getList();
			}
		},
		nextPage () {
			if (this.page < this.totalPage || this.page < 1) {
				this.page++;
				this.getList();
			}
		},
		firstPage () {
			if(this.page !== 1){
				this.page = 1;
				this.start = 0;
				this.getList();
			}
		},
		lastPage () {
			if(this.page !== this.totalPage){
				this.page = this.totalPage;
				this.start = (this.totalPage-1) * this.limit;
				this.getList();
			}
		},
		updLimit () {
			this.start = 0;
			this.page = 1;
			this.getList();
		},
		selectPage () {
			if(this.page >= 1 && this.page <= this.totalPage){
				this.start = (this.page-1) * this.limit;
				this.getList();
			}
		}
	},
	mounted () {
		this.getList();
	},
	created () {
		bus.$on('updGdid', () => { 
			this.start = (this.page-1) * this.limit;
			this.getList(); 
		});

		this.$on('multiAction', val => {
			let {action, ids} = val;

			switch(action) {
				case 'disableItem': action = 'disable'; break;
				case 'enableItem': action = 'enable'; break;
				case 'removeItem': action = 'remove'; break;
			}

			let bodyFormData = new FormData();
				bodyFormData.set('action', `mgr/item/${action}`);
				bodyFormData.set('ids', `[${ids}]`); // '[7,6]'

			this.$http.post(BanOnIP.connector_url, bodyFormData, this.$httpConfig)
				.then( response => { if (response.data.success === true) { 
					this.start = (this.page-1) * this.limit; this.getList(this.start); } 
				})
				.catch( error => { console.log(error); });

		});
	}
}
</script>

<style lang="sass" scoped>
.banonip-btn
	padding: 5px
	margin-right: 2px
	min-width: 26px
	font-size: inherit

.table
	width: 100%
	border-spacing: 0px
	background-color: transparent
	background-image: none
	border: 1px solid #e4e9ee
	border-radius: 3px
	overflow: hidden
	padding: 0
	.table__chek
		display: block
		text-align: center
	& thead
		background: #e4e9ee
		& th:first-child
			width: 18px
			cursor: pointer
			padding: 12px 0px 12px 5px
			border-left: 0
			&:hover
				cursor: default
		& th
			color: #696969
			font-weight: bold
			padding: 12px 10px
			border-left: 1px solid #fff
			&:hover
				cursor: pointer
				background: #d3dce3
	& td
		padding-left: 5px

.pagination
	margin-top: 10px
	& li
		margin-right: 10px
		display: inline-block
	& .x-btn-text
		padding: 0 3px


</style>
