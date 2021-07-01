<template>
	<div class="hidden">&nbsp;</div>
</template>

<script>
	// import { usePage } from '@inertiajs/inertia-vue3';
	// import { computed, onMounted } from 'vue';

	export default {
		name: 'ToastMessage',

		watch: {
			"$page.props.flash": function() {
				if (!this.$page.props.flash) {
					return;
				}

				if (this.$page.props.flash.success) {
					Toast.fire({icon: 'success', title: this.$page.props.flash.success});
				} else if (this.$page.props.flash.error) {
					Toast.fire({icon: 'error', title: this.$page.props.flash.error});
				}
			}
		},
	}
</script>
