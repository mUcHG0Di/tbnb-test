<template>
	<div class="hidden">&nbsp;</div>
</template>

<script>
	// import { usePage } from '@inertiajs/inertia-vue3';
	// import { computed, onMounted } from 'vue';

	export default {
		name: 'ToastMessage',
		props: {
			class: {
				type: String,
				default: () => ('w-10/12'),
			},
		},

		watch: {
			"$page.props.flash": function(newVal, oldVal) {
				if (!this.$page.props.flash) {
					console.log('No value for success')
					return;
				}

				if (this.$page.props.flash.success) {
					Toast.fire({icon: 'success', title: this.$page.props.flash.success});
				} else if (this.$page.props.flash.error) {
					Toast.fire({icon: 'error', title: this.$page.props.flash.error});
				}
			}
		},

		data() {
			// const $page = computed(() => (usePage().props.value));
			return { 
				// $page,
			};
		},

		computed: function() {

		},
	}
</script>