<template>
    <app-layout>
        <v-container>
            <!-- Header -->
            <v-row>
                <v-card-title>Products</v-card-title>

                <v-spacer></v-spacer>

                <v-btn
                    elevation="1"
                    color="primary"
                    class="m-2"
                    :disabled="bulkForm.products.length != 1"
                    @click="showDialog = !showDialog"
                >
                    <v-icon small class="mr-1">mdi-eye</v-icon>
                    Show
                </v-btn>
                <v-btn
                    elevation="1"
                    color="success"
                    class="m-2"
                    @click="editMode = false; formDialog = !formDialog"
                >
                    <v-icon small class="mr-1">mdi-plus</v-icon>
                    Add
                </v-btn>
                <v-btn
                    elevation="1"
                    color="orange darken-2"
                    class="m-2 white--text"
                    :disabled="bulkForm.products.length <= 0"
                    @click="editMode = true; formDialog = !formDialog"
                >
                    <v-icon small class="mr-1">mdi-pencil</v-icon>
                    Update
                </v-btn>
                <v-btn
                    elevation="1"
                    color="error"
                    class="m-2"
                    :disabled="bulkForm.products.length <= 0"
                    @click="destroy"
                >
                    <v-icon small class="mr-1">mdi-delete</v-icon>
                    Delete
                </v-btn>
            </v-row>

            <Table
                :filters="queryBuilderProps.filters"
                :search="queryBuilderProps.search"
                :columns="queryBuilderProps.columns"
                :on-update="setQueryBuilder"
                :meta="products"
            >
                <template #head>
                    <tr>
                        <th>&nbsp;</th>
                        <th @click.prevent="sortBy('uuid')">UUID</th>
                        <th v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
                        <th v-show="showColumn('description')" @click.prevent="sortBy('description')">Description</th>
                        <th v-show="showColumn('price')" @click.prevent="sortBy('price')">Price</th>
                        <th v-show="showColumn('quantity')" @click.prevent="sortBy('quantity')">Quantity</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="product in products.data" :key="product.uuid">
                        <td class="px-2 py-1">
                            <v-checkbox
                                :id="`product_${product.uuid}`"
                                v-model="bulkForm.products"
                                :value="product"
                                light
                            ></v-checkbox>
                        </td>
                        <td>{{ product.uuid }}</td>
                        <td v-show="showColumn('name')">{{ product.name }}</td>
                        <td v-show="showColumn('description')">{{ product.description }}</td>
                        <td v-show="showColumn('price')">{{ product.price }}</td>
                        <td v-show="showColumn('quantity')">{{ product.quantity }}</td>
                    </tr>
                </template>
            </Table>
        </v-container>

        <FormMultiple
            v-if="formDialog"
            :showing="formDialog"
            :selectedProducts="editMode ? bulkForm.products : [emptyProduct]"
            @close="formDialog = !formDialog; redirect();"
        />
    </app-layout>
</template>


<script>
    import AppLayout from '@/Layouts/AppLayout';
    import FormMultiple from './FormMultiple';
    import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

    export default {
        name: 'products-index',
        props: {
            products: Object,
            formOpened: Boolean,
        },
        mixins: [ InteractsWithQueryBuilder ],
        components: {
            AppLayout,
            FormMultiple,
            Table: Tailwind2.Table,
        },

        data: function() {
            return {
                showDialog: false,
                formDialog: false,
                emptyProduct: {
                    name: null,
                    description: null,
                    price: 0,
                    quantity: 0,
                },
                bulkForm: this.$inertia.form({
                    products: [],
                }, {
                    resetOnSuccess: true,
                })
            };
        },

        mounted: function() {
            this.formDialog = this.formOpened;
            this.bulkForm.products = [];
        },

        methods: {
            destroy: function() {
                const message = this.getFormattedMessage();
                this.$root.confirmDestroy('Remove products', message, () => {
                    this.bulkForm.transform((data) => ({
                        products_uuids: data.products.map((product) => (product.uuid)),
                    }))
                    .delete(route('products.destroy.multiple'), {
                        onSuccess: () => {
                            this.bulkForm.products = [];
                            this.redirect();
                        },
                        onError: () => {},
                    });
                })
            },

            getFormattedMessage: function() {
                let message = "Are you sure you want to remove the following products?";
                message += "<ul style=\"margin-top: 20px; list-style: disc; text-align: left; margin-left: 30px;\">";
                this.bulkForm.products.forEach((product) => {
                    message += `<li>${product.name}</li>`;
                });
                message += "</ul>";
                return message;
            },

            redirect: function() {
                this.$inertia.visit(route('products.index'), {
                    only: ['products'],
                })
            },
        },
    }
</script>

<style>
.v-input--selection-controls__input {
    margin-right: auto !important;
    margin-left: auto !important;
}
</style>
