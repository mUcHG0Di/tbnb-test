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
                            v-for="product in selectedProducts"
                            :key="product.uuid"
                            elevation="1"
                            :class="selectedProducts.length > 1 ? 'border-b-2' : ''"
                        >
                            <ProductForm :product="product" />
                        </div>
                    </v-container>
                <small>* Indicates required field</small>
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
                    @click="store"
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
    },
    components: {
        ProductForm,
    },

    data: function() {
        return {
            products: [],
            processing: false,
        };
    },

    mounted: function() {
        this.products = this.selectedProducts;
    },

    computed: {
        title: function() {
            return (this.products[0]?.name == null) ? 'Create product' : 'Edit product';
        },
    },

    methods: {
        close: function() {
            // this.form.clearErrors();
            // this.form.reset();
            // if (route().current('products.create')) {
            //     this.$inertia.visit(route('products.index'));
            // }
            this.$emit('close');
        },

        store: function() {
            this.processing = true;
            // this.form.post(route('products.store'), {
			// 	preserveScroll: true,
			// 	onSuccess: () => { this.$emit('close'); },
			// 	onError: () => {},
			// });
        },
    },
}
</script>
