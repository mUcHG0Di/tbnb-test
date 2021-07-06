<template>
    <v-dialog
        v-model="showing"
        persistent
        max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="mx-auto text-h5">History of "{{ product.name }}"</span>
            </v-card-title>

            <v-card-text
                class="pt-5 text-center"
            >
                <HistoryTable :product="product" :loading="loading" />
            </v-card-text>

            <ModalActions
                :editMode="false"
                @close="$emit('close')"
            />
        </v-card>
    </v-dialog>
</template>

<script>
import HistoryTable from '@/Components/Products/HistoryTable';
import ModalActions from '@/Components/Common/ModalActions';

export default {
    name: 'history-modal',
    props: {
        showing: Boolean,
        product: Object,
    },
    components: {
        HistoryTable,
        ModalActions,
    },

    data: function() {
        return {
            loading: false,
        };
    },

    mounted: function() {
        // Request product history if empty
        if (this.product.history == null || this.product.history.length <= 0) {
            this.loading = true;
            axios.get(route('products.history.get', this.product.uuid))
                .then(({data}) => {
                    this.loading = false;

                    if (data.status != 'success') {
                        Toast.fire({icon: data.status, title: data.message});
                        return;
                    }

                    this.product.history = data.data;
                });
        }
    }
}
</script>

<style scoped>

</style>
