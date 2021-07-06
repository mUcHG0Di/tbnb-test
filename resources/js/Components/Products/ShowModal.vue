<template>
    <v-dialog
        v-model="showing"
        persistent
        max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="mx-auto text-h6">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                    <v-container>
                        <img :src="product.image_url" class="w-3/5 h-auto mx-auto mb-7" />

                        <ProductForm
                                :product="$_.pick(form, ['name', 'description', 'price', 'quantity', 'owner'])"
                                :busy="form.busy"
                                :errors="form.errors ? form.errors : {}"
                                :readonly="!editMode"
                                @syncForm="(newData) => { Object.assign(form, newData) }"
                            />

                        <div v-if="editMode">
                            <small>{{ $t('showModal.requiredObs') }}</small>
                        </div>
                    </v-container>
            </v-card-text>

            <ModalActions
                :editMode="editMode"
                :processing="form.busy"
                actionable
                @close="close"
                @cancel="() => { editMode = false; Object.assign(form, product) }"
                @destroy="destroy"
                @save="save"
                @editMode="(newEditModeVal) => { editMode = newEditModeVal; }"
            />
        </v-card>
    </v-dialog>
</template>

<script>
import ProductForm from '@/Components/Products/Form';
import ModalActions from '@/Components/Common/ModalActions';

export default {
    name: 'product-show',
    props: {
        showing: Boolean,
        product: Object,
        single: Boolean,
    },
    components: {
        ProductForm,
        ModalActions,
    },

    data: function() {
        return {
            editMode: false,
            form: this.$inertia.form({
                name: null,
                description: null,
                price: 0,
                quantity: 0,
                owner: null,
            }),
        };
    },

    mounted: function() {
        Object.assign(this.form, this.product);
    },

    computed: {
        title: function() {
            return (!this.editMode) ? this.$t('showModal.showProduct') : this.$t('showModal.editProduct');
        },
    },

    methods: {
        close: function() {
            this.form.clearErrors();
            this.form.reset();

            // Redirect to index
            this.$inertia.visit(route('products.index'));
            this.$emit('close');
        },

        save: function() {
            this.form.put(route('products.update', this.form.uuid), {
				preserveScroll: true,
				onSuccess: () => { this.close(); },
                onFinish: () => { this.processing = false; },
			});
        },

        destroy: function() {
            const title = this.$t('showModal.deleteConfirmation.title');
            const message = this.$t('showModal.deleteConfirmation.message', {product_name: this.form.name});
            // `Are you sure you want to delete the product '${this.form.name}'`;
            this.$root.confirmDestroy(title, message, () => {
                this.form.delete(route('products.destroy', this.form.uuid), {
                    onSuccess: () => { this.close(); },
                });
            })
        },
    },
}
</script>
