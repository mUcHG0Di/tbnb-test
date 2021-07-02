<template>
    <v-dialog
        v-model="showing"
        persistent
        max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                    <v-container>
                        <div
                            v-for="(product, index) in form.products"
                            :key="product.uuid"
                            elevation="1"
                            :class="selectedProducts.length > 1 ? 'border-b-2 mb-5' : 'mb-5'"
                        >
                            <ProductForm
                                :product="product"
                                :index="index"
                                :busy="processing"
                                :errors="form.errors"
                                @syncForm="(newData) => { form.products[index] = newData; }"
                                @removeForm="(selectedFormIndex) => { form.products.splice(selectedFormIndex, 1) }"
                            />
                        </div>

                        <small>* Indicates required field</small>
                        <br>
                        <br>

                        <v-btn
                            x-small
                            color="primary"
                            @click="form.products.push({name: null, description: null, price: 0, quantity: 0})"
                        >
                            <v-icon x-small class="mr-1">mdi-plus</v-icon>
                            Add another product
                        </v-btn>
                    </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    :disabled="processing"
                    color="blue darken-1"
                    text
                    @click="close"
                >
                    Close
                </v-btn>
                <v-btn
                    :loading="processing"
                    :disabled="processing"
                    color="blue darken-1"
                    text
                    @click="save"
                >
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import ProductForm from '@/Components/Products/Form';

export default {
    name: 'product-form',
    props: {
        showing: Boolean,
        selectedProducts: {
            type: Array,
            default: () => ([{
                name: null,
                description: null,
                price: 0,
                quantity: 0,
            }]),
        },
        single: Boolean,
    },
    components: {
        ProductForm,
    },

    data: function() {
        return {
            editMode: this.selectedProducts[0]?.name != null,
            form: this.$inertia.form({
                products: [],
            }),
            processing: false,
        };
    },

    mounted: function() {
        this.form.products = this.selectedProducts;
    },

    computed: {
        title: function() {
            return (!this.editMode) ? 'Create product' : 'Edit product';
        },
    },

    methods: {
        close: function() {
            this.form.clearErrors();
            this.form.reset();

            // Redirect to index
            if (route().current('products.create')) {
                this.$inertia.visit(route('products.index'));
            }
            this.$emit('close');
        },

        save: function() {
            this.processing = true;
            if (this.editMode) {
                this.update();
            } else {
                this.store();
            }
        },

        store: function() {
            this.form.post(route('products.store.multiple'), {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
				onError: () => {},
                onFinish: () => { this.processing = false; },
			});
        },

        update: function() {
            this.form.patch(route('products.update.multiple'), {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
				onError: () => {},
                onFinish: () => { this.processing = false; },
			});
        },
    },
}
</script>
