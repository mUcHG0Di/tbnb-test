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
                <v-simple-table
                    v-if="!loading"
                >
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-center">
                                    Quantity
                                </th>
                                <th class="text-center">
                                    Date
                                </th>
                                <th class="text-center">
                                    User involved
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="history in product.history"
                                :key="history.id"
                            >
                                <td>{{ history.quantity }}</td>
                                <td>{{ history.date }}</td>
                                <td>{{ (history.user) ? history.user.name : '' }}</td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>

                <v-progress-circular
                    v-if="loading"
                    :size="50"
                    :width="7"
                    color="primary mt-5"
                    indeterminate
                ></v-progress-circular>
            </v-card-text>

            <ModalActions
                :editMode="false"
                @close="$emit('close')"
            />
        </v-card>
    </v-dialog>
</template>

<script>
import ModalActions from '@/Components/Common/ModalActions';

export default {
    name: 'history-modal',
    props: {
        showing: Boolean,
        product: Object,
    },
    components: {
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
