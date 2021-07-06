<template>
    <v-dialog
        v-model="showing"
        persistent
        max-width="600px"
    >
        <v-card>
            <v-card-title>
                <span class="mx-auto text-h5">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                    <v-container>
                        <template v-if="single">
                            <div
                                elevation="1"
                                class="mb-5"
                            >
                                <ProductForm
                                    :product="$_.first(selectedProducts)"
                                    :busy="singleForm.busy"
                                    :errors="singleForm.errors || {}"
                                    requiresImage
                                    @syncForm="(newData) => { Object.assign(singleForm, newData); }"
                                />
                            </div>
                        </template>
                        <template v-else>
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
                        </template>

                        <small>{{ $t('formModal.requiredObs') }}</small>
                        <br>
                        <br>

                        <v-btn
                            v-if="!single && !editMode"
                            x-small
                            color="primary"
                            @click="form.products.push({name: null, description: null, price: 0, quantity: 0})"
                        >
                            <v-icon x-small class="mr-1">mdi-plus</v-icon>
                            {{ $t('formModal.addAnotherProduct') }}
                        </v-btn>
                    </v-container>
            </v-card-text>

            <ModalActions
                :editMode="true"
                :processing="processing"
                @close="close"
                @cancel="close"
                @save="save"
                @editMode="(val) => { editMode = val; }"
            />
        </v-card>
    </v-dialog>
</template>

<script>
import ProductForm from '@/Components/Products/Form';
import ModalActions from '@/Components/Common/ModalActions';

export default {
    name: 'product-form',
    props: {
        showing: Boolean,
        single: Boolean,
        selectedProducts: {
            type: Array,
            default: () => ([{
                name: null,
                description: null,
                price: 0,
                quantity: 0,
                image: null,
            }]),
        },
    },
    components: {
        ProductForm,
        ModalActions,
    },

    data: function() {
        return {
            editMode: this.selectedProducts[0]?.name != null,
            form: this.$inertia.form({
                products: [],
            }),
            processing: false,
            singleForm: this.$inertia.form({
                name: null,
                description: null,
                image: null,
                price: 0,
                quantity: 0,
                image: null,
            }),
        };
    },

    mounted: function() {
        this.form.products = this.selectedProducts;
    },

    computed: {
        title: function() {
            return (!this.editMode) ?
                        this.$tc('formModal.createProduct', this.single ? 1 : 2)
                    :
                        this.$tc('formModal.editProduct', this.single ? 1 : 2);
        },
    },

    methods: {
        close: function() {
            this.form.clearErrors();
            this.form.reset();
            this.singleForm.clearErrors();
            this.singleForm.reset();

            // Redirect to index
            if (route().current('products.create')) {
                this.$inertia.visit(route('products.index'));
            }
            this.$emit('close');
        },

        save: function() {
            this.processing = true;
            const options = {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
                onFinish: () => { this.processing = false; },
			};

            if (this.editMode) {
                this.update(options);
            } else {
                this.store(options);
            }
        },

        store: function(options) {
            if (this.single) {
                this.singleForm.post(route('products.store'), options);
            } else  {
                this.form.post(route('products.store.multiple'), options);
            }
        },

        update: function(options) {
            if (this.single) {
                const selectedProductUUID = this.$_.first(this.selectedProducts).uuid;
                this.singleForm.patch(route('products.update', selectedProductUUID), options);
            } else {
                this.form.patch(route('products.update.multiple'), options);
            }
        },
    },
}
</script>
