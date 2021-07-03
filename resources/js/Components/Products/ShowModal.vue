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
                        <img :src="product.image_path" class="w-3/5 h-auto mx-auto mb-7" />

                        <ProductForm
                                :product="$_.pick(form, ['name', 'description', 'price', 'quantity'])"
                                :busy="form.busy"
                                :errors="form.errors ? form.errors : {}"
                                :readonly="!editMode"
                                @syncForm="(newData) => { Object.assign(form, newData) }"
                            />

                        <div v-if="editMode">
                            <small>* Indicates required field</small>
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
            }),
        };
    },

    mounted: function() {
        Object.assign(this.form, this.product);
    },

    computed: {
        title: function() {
            return (!this.editMode) ? 'Show product' : 'Edit product';
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
            const message = `Are you sure you want to delete the product '${this.form.name}'`;
            this.$root.confirmDestroy('Remove product', message, () => {
                this.form.delete(route('products.destroy', this.form.uuid), {
                    onSuccess: () => { this.close(); },
                });
            })
        },
    },
}
</script>
