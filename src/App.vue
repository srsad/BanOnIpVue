<template>
	<div id="banonip-panel-home-div">
		<div class="x-panel">
			<h2>{{$lexicon('banonip')}}</h2>
		</div>

		<div class="x-panel-body x-panel-body-noheader">
			<div class="x-panel panel-desc">
				<div class="x-panel-body x-panel-body-noheader">{{$lexicon('banonip_intro_msg')}}</div>
			</div>
			<div class="x-panel main-wrapper x-grid-panel">
				<div class="x-panel-bwrap row">
					<div class="col-6">
						<button type="button" class="x-btn x-btn-small" @click="showModal('create')">
							<i class="icon icon-plus"></i>
							&nbsp;{{$lexicon('create')}}
						</button>
					</div>
					<div class="col-6">

					</div>
				</div>

				<!--  -->
				<ip-grid @updateItem="onUpdateItem" />

			</div>
		</div>

		<!--  -->
		<window-item :modalction="modalction" :ipID="ipID" @clearModalAction="onClearModalAction" />
		<!-- -->

	</div>
</template>

<script>
export default {
	name: 'banonip-panel-home-div',
	data () {
		return {
			modalction: 'create',
			ipID: 0,
		}
	},
	methods: {
		showModal: function (val) {
			this.modalction = val;
			this.$modal.show('modalItem');
		},
		onUpdateItem: function (val) {
			this.modalction = 'update';
			this.ipID = val;
			this.$modal.show('modalItem');
		},
		onClearModalAction: function () {
			this.modalction = 'create';
			this.ipID = 0;
		}
	},

}
</script>

<style lang="sass" scoped>
.row
	padding: 5px 0

.x-panel>h2
	margin: 15px 0px

.x-form-text 
	width: 162px

.search
	float: right
	width: 250px
	background: #FFF
	border: 0
	border-radius: 3px
	box-shadow: 0 0 0 1px #E4E4E4
	cursor: pointer
	line-height: 1
	position: relative
	overflow: hidden
	transition: all .2s
	&.active
		box-shadow: 0 0 0 1px #3697cd
		border-color: #2d86b7
	& .search__btns
		position: absolute
		right: 0
		& .search__btn
			background-repeat: no-repeat
			border-left: 1px solid #e4e4e4
			color: #555555
			cursor: pointer
			display: inline-block
			line-height: 1
			padding: 11px 15px 12px 15px
			position: relative
			text-decoration: none
			zoom: 1
			transition: all .2s
			&:hover
				background-color: #2d86b7
				color: #FFF

</style>
