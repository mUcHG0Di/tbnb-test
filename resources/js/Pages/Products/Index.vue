<template>
    <app-layout>
        <v-container>
            <!-- Actions -->
            <TableActions
                title="Products"
                :selectedCount="bulkForm.products.length"
                @add="editMode = false; singleMode = formDialog = true;"
                @addMultiple="editMode = singleMode = false; formDialog = true;"
                @edit="editMode = formDialog = true; singleMode = false;"
                @destroy="destroyMultiple"
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
                        <th v-show="showColumn('uuid')" @click.prevent="sortBy('uuid')">UUID</th>
                        <th v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
                        <th v-show="showColumn('description')" @click.prevent="sortBy('description')">Description</th>
                        <th v-show="showColumn('price')" @click.prevent="sortBy('price')">Price</th>
                        <th v-show="showColumn('quantity')" @click.prevent="sortBy('quantity')">Quantity</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="product in products.data" :key="product.uuid">
                        <td class="px-2 py-1">
                            <v-checkbox
                                :id="`product_${product.uuid}`"
                                v-model="bulkForm.products"
                                :value="product.uuid"
                                light
                            ></v-checkbox>
                        </td>
                        <td v-show="showColumn('uuid')">
                            <div class="break-words whitespace-normal w-36">{{ product.uuid }}</div>
                        </td>
                        <td v-show="showColumn('name')">{{ product.name }}</td>
                        <td v-show="showColumn('description')">{{ product.description }}</td>
                        <td v-show="showColumn('price')">{{ product.price }}</td>
                        <td v-show="showColumn('quantity')">{{ product.quantity }}</td>
                        <td>
                            <RowActions
                                @show="() => { bulkForm.products = [product.uuid]; showDialog = true; }"
                                @history="() => { bulkForm.products = [product.uuid]; historyDialog = true; }"
                                @edit="() => { bulkForm.products = [product.uuid]; formDialog = editMode = singleMode = true; }"
                                @destroy="() => { destroySingle(product) }"
                            />
                        </td>
                    </tr>
                </template>
            </Table>
        </v-container>

        <!-- Modals -->
        <FormModal
            v-if="formDialog"
            :showing="formDialog"
            :single="singleMode"
            :selectedProducts="editMode ? $_.filter(products.data, (product) => bulkForm.products.includes(product.uuid)) : [getEmptyProduct()]"
            @close="formDialog = false;"
        />

        <ShowModal
            v-if="showDialog"
            :showing="showDialog"
            :product="products.data.find((product) => product.uuid == $_.first(bulkForm.products))"
            @close="showDialog = false"
        />

        <HistoryModal
            v-if="historyDialog"
            :showing="historyDialog"
            :product="products.data.find((product) => product.uuid ==  $_.first(bulkForm.products))"
            @close="historyDialog = false"
        />
    </app-layout>
</template>


<script>
    import AppLayout from '@/Layouts/AppLayout';
    import TableActions from '@/Components/Common/TableActions';
    import RowActions from '@/Components/Common/RowActions';
    import ShowModal from '@/Components/Products/ShowModal';
    import FormModal from '@/Components/Products/FormModal';
    import HistoryModal from '@/Components/Products/HistoryModal';
    import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

    export default {
        name: 'products-index',
        props: {
            products: Object,
            product: Object, // If it loads from SHOW or EDIT
            formDialogOpened: Boolean,
            showDialogOpened: Boolean,
            historyDialogOpened: Boolean,
        },
        mixins: [ InteractsWithQueryBuilder ],
        components: {
            AppLayout,
            TableActions,
            RowActions,
            ShowModal,
            FormModal,
            HistoryModal,
            Table: Tailwind2.Table,
        },

        data: function() {
            return {
                // Dialogs
                showDialog: false,
                formDialog: false,
                historyDialog: false,

                // Form modes
                singleMode: false,
                editMode: false,

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
            if (this.showDialogOpened || this.historyDialogOpened) {
                this.showDialog = this.showDialogOpened;
                this.historyDialog = this.historyDialogOpened;
                this.bulkForm.products.push(this.products.data.filter((prod) => (prod.uuid == this.product.uuid))[0]);
            }
        },

        methods: {
            getEmptyProduct: function() {
                return {
                    name: null,
                    description: null,
                    price: 0,
                    quantity: 0,
                }
            },

            destroySingle: function(product) {
                const title = 'Remove product';
                const message = `Are you sure you want to remove the product "${product.name}?"`;
                this.$root.confirmDestroy(title, message, () => {
                    this.bulkForm.transform((data) => ({
                        products_uuids: data.products,
                    }))
                    .delete(route('products.destroy', product.uuid), {
                        onSuccess: () => {
                            this.bulkForm.products = [];
                            this.redirect();
                        },
                        onError: () => {},
                    });
                });
            },

            destroyMultiple: function() {
                const title = 'Remove products';
                const message = this.getFormattedMessage();
                this.$root.confirmDestroy(title, message, () => {
                    this.bulkForm.transform((data) => ({
                        products_uuids: data.products,
                    }))
                    .delete(route('products.destroy.multiple'), {
                        onSuccess: () => {
                            this.bulkForm.products = [];
                            this.redirect();
                        },
                        onError: () => {},
                    });
                });
            },

            getFormattedMessage: function() {
                let message = "Are you sure you want to remove the following products?";
                message += "<ul style=\"margin-top: 20px; list-style: disc; text-align: left; margin-left: 30px;\">";
                this.$_.filter(this.products.data, (product) => (this.bulkForm.products.includes(product.uuid)))
                    .forEach((product) => {
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
