<template>
	<modal class="window" name="modalItem" width="400" height="auto" draggable=".window__header" @closed="onClose" @opened="clearData">
		<div class="window__header">
			<div v-if="modalction === 'update'" class="title">
				{{$lexicon('update')}}
			</div>
			<div v-else class="title">
				{{$lexicon('banonip_item_create')}}
			</div>
			<div class="window__btns">
				<div class="x-tool x-tool-close" @click="$modal.hide('modalItem')">&nbsp;</div>
			</div>
		</div>
		<div class="window__content">
			<form action="">
				<div class="x-form-item">
					<label for="createIp" class="x-form-item-label">{{$lexicon('banonip_item_name')}}</label>
					<input type="text" id="createIp" autofocus 
										@blur="blur()" 
										v-model="form.name" 
										:class="[nameEmpty ? 'x-form-invalid' : '', 'x-form-text']">
					<div class="x-form-invalid-msg" v-if="nameEmpty === true">{{$lexicon('field_required')}}</div>
				</div>

				<div class="x-form-item">
					<label class="x-form-item-label">{{$lexicon('banonip_item_desc_masc')}}</label>
					<div style="display: block;">
						<div class="d-inline-block" style="width: 55px;">
							<label>
								<input type="checkbox" name="a" class="x-checkbox" disabled >
								<span class="x-checkbox-custom disabled"></span>
								<span class="x-form-cb-label disabled">{{$lexicon('banonip_item_a')}}</span>
							</label>
						</div>
						
						<div class="d-inline-block" style="width: 55px;">
							<label for="createNode-b" @click="checkB()">
								<input type="checkbox" name="b" id="createNode-b" v-model="form.b" class="x-checkbox">
								<span class="x-checkbox-custom"></span>
								<span class="x-form-cb-label">{{$lexicon('banonip_item_b')}}</span>
							</label>
						</div>

						<div class="d-inline-block" style="width: 55px;">
							<label for="createNode-c" @click="checkC()">
								<input type="checkbox" name="c" id="createNode-c" v-model="form.c" class="x-checkbox">
								<span class="x-checkbox-custom"></span>
								<span class="x-form-cb-label">{{$lexicon('banonip_item_c')}}</span>
							</label>
						</div>

						<div class="d-inline-block" style="width: 55px;">
							<label for="createNode-d" @click="checkD()">
								<input type="checkbox" name="d" id="createNode-d" v-model="form.d" class="x-checkbox">
								<span class="x-checkbox-custom"></span>
								<span class="x-form-cb-label">{{$lexicon('banonip_item_d')}}</span>
							</label>
						</div>
						
						<div class="d-inline-block" style="float: right;">
							<label for="createActive" @click="form.active = !form.active">
								<input type="checkbox" name="active" id="createActive" v-model="form.active" class="x-checkbox">
								<span class="x-checkbox-custom"></span>
								<span class="x-form-cb-label">{{$lexicon('banonip_item_active')}}</span>
							</label>
						</div>
					</div>
				</div>

				<div class="x-form-item">
					<label for="createDescription" class="x-form-item-label">{{$lexicon('banonip_item_description')}}</label>
					<textarea class="x-form-textarea" id="createDescription"  v-model="form.description" cols="30" rows="4"></textarea>
				</div>

			</form>
		</div>
		<div class="window__footer">

			<span class="x-btn x-btn-small x-btn-icon-small-left x-btn-noicon" 
					@click="$modal.hide('modalItem')"
					style="margin-right:10px">
					<button type="button" class=" x-btn-text">{{$lexicon('cancel')}}</button>
			</span>
			
			<span class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon" 
					v-if="modalction === 'update'"
					@click="windowAction('update')"
					style="width:auto">
					<button type="button" id="ext-gen146" class=" x-btn-text">{{$lexicon('update')}}</button>
			</span>
			
			<span class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon" 
					v-else
					@click="windowAction('create')"
					style="width:auto">
					<button type="button" id="ext-gen146" class=" x-btn-text">{{$lexicon('save')}}</button>
			</span>

		</div>
	</modal>
</template>

<script>
import { bus } from '../main.js'

export default {
	props: { 
		ipID: { type: Number },
		modalction: { 
			type: String, 
			default: 'create' // create/update
		},
	},
	data () {
		return {
			form: {
				name: '',
				b: false,
				c: false,
				d: false,
				active: true,
				description: ''
			},
			nameEmpty: false,
		}
	},
	methods: {
		windowAction (action) {
			if (this.form.name == '') {
				this.nameEmpty = true;
				return;
			}

			let bodyFormData = new FormData();
				bodyFormData.set('action', `mgr/item/${action}`);

			for (let val in this.form) { 
				if (typeof(this.form[val]) == 'boolean') {
					bodyFormData.set(val, Number(this.form[val])); 
				} else {
					bodyFormData.set(val, this.form[val]); 
				}
			}

			this.$http.post(BanOnIP.connector_url, bodyFormData, this.$httpConfig)
				.then( response => {
					if (response.data.success === true) {
						bus.$emit('updGdid');
						this.clearData();
						this.$modal.hide('modalItem');
					} else {
						this.$noty.error(response.data.data[0].msg)
					}
				})
				.catch( error => { console.log(error); });

		},
		checkB () {
			this.form.b = !this.form.b;
			if (this.form.b === true) {
				this.form.c = true;
				this.form.d = true;
			}
		},
		checkC () {
			this.form.c = !this.form.c;
			if (this.form.c === true) {
				this.form.d = true;
			} else {
				this.form.b = false;
			}
		},
		checkD () {
			this.form.d = !this.form.d;
			if (this.form.d === false) {
				this.form.c = false;
				this.form.b = false;
			}
		},
		clearData () {
			this.form.name = '';
			this.form.b = false;
			this.form.c = false;
			this.form.d = false;
			this.form.active = true;
			this.form.description = '';
			this.nameEmpty = false;
		},
		blur () {
			this.nameEmpty = this.form.name != '' ? false : true;
		},
		onClose () {
			this.$emit('clearModalAction'); // обнуляем modalction
		}
	},
	watch: {
		form: {
			handler: function () {
				this.nameEmpty = this.form.name != '' ? false : true;
			},
			deep: true
		},
		modalction: function () {
			if (this.modalction === 'update') {
				let bodyFormData = new FormData();
					bodyFormData.set('action', 'mgr/item/get');
					bodyFormData.set('id', this.ipID);

				this.$http.post(BanOnIP.connector_url, bodyFormData, this.$httpConfig)
					.then( response => { 
						if (response.data.success === true) { 
							for (let val in response.data.object) { this.form[val] = response.data.object[val]; }
						}
					})
					.catch( error => { console.log(error); });
			}
		}
	}
}	
</script>

<style lang="sass" scoped>
.x-form-item-label
	width: 100% !important

</style>