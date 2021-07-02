<template>
    <app-layout>
        <v-container>
            <!-- Actions -->
            <TableActions
                title="Products"
                :selectedCount="bulkForm.products.length"
                @show="showDialog = !showDialog"
                @add="editMode = false; formDialog = !formDialog"
                @edit="editMode = true; formDialog = !formDialog"
                @destroy="destroy"
            />

            <!-- Table -->
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

        <Show
            v-if="showDialog"
            :showing="showDialog"
            :product="bulkForm.products[0]"
            @close="showDialog = !showDialog"
        />

        <History />
    </app-layout>
</template>


<script>
    import AppLayout from '@/Layouts/AppLayout';
    import TableActions from '@/Components/Common/TableActions';
    import Show from './Show';
    import FormMultiple from './FormMultiple';
    import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

    export default {
        name: 'products-index',
        props: {
            products: Object,
            product: Object,
            formDialogOpened: Boolean,
            showDialogOpened: Boolean,
        },
        mixins: [ InteractsWithQueryBuilder ],
        components: {
            AppLayout,
            TableActions,
            Show,
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
            this.formDialog = this.formDialogOpened;
            this.bulkForm.products = [];
            if (this.showDialogOpened) {
                this.showDialog = this.showDialogOpened;
                this.bulkForm.products.push(this.products.data.filter((prod) => (prod.uuid == this.product.uuid))[0]);
            }
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
