<template>
    <app-layout>
        <v-container>
            <!-- Actions -->
            <TableActions
                :title="$t('indexPage.title')"
                :selectedCount="bulkForm.products.length"
                @add="editMode = false; singleMode = formDialog = true;"
                @addMultiple="editMode = singleMode = false; formDialog = true;"
                @edit="editMode = formDialog = true; singleMode = false;"
                @destroy="destroyMultiple"
            />

            <!-- Table -->
            <Table
                ref="table"
                :filters="queryBuilderProps.filters"
                :search="queryBuilderProps.search"
                :columns="queryBuilderProps.columns"
                :on-update="setQueryBuilder"
                :meta="products"
            >
                <template #head>
                    <tr>
                        <th class="pr-2">&nbsp;</th>
                        <th v-show="showColumn('uuid')" @click.prevent="sortBy('uuid')">UUID</th>
                        <th v-show="showColumn('name')" @click.prevent="sortBy('name')">{{ $t('indexPage.tableColumns.name') }}</th>
                        <th v-show="showColumn('description')" @click.prevent="sortBy('description')">{{ $t('indexPage.tableColumns.description') }}</th>
                        <th v-show="showColumn('price')" @click.prevent="sortBy('price')" class="px-4">{{ $t('indexPage.tableColumns.price') }}</th>
                        <th v-show="showColumn('quantity')" @click.prevent="sortBy('quantity')" class="px-4">{{ $t('indexPage.tableColumns.quantity') }}</th>
                        <th v-show="showColumn('owner.name')" @click.prevent="sortBy('owner.name')">{{ $t('indexPage.tableColumns.owner') }}</th>
                        <th class="px-1 text-center">Actions</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="product in products.data" :key="product.uuid">
                        <td class="py-1 pr-2">
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
                        <td v-show="showColumn('description')">
                            <div class="break-words whitespace-normal w-36">{{ product.description }}</div>
                        </td>
                        <td v-show="showColumn('price')" class="px-4">{{ product.price }}</td>
                        <td v-show="showColumn('quantity')" class="px-4">{{ product.quantity }}</td>
                        <td v-show="showColumn('owner.name')">{{ (product.owner) ? product.owner.name : '' }}</td>
                        <td class="px-1">
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
            :selectedProducts="selectedProducts"
            @close="formDialog = false;"
        />

        <ShowModal
            v-if="showDialog"
            :showing="showDialog"
            :product="selectedSingleProduct"
            @close="showDialog = false"
        />

        <HistoryModal
            v-if="historyDialog"
            :showing="historyDialog"
            :product="selectedSingleProduct"
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
    import { InteractsWithQueryBuilder, Tailwind2, Components } from '@protonemedia/inertiajs-tables-laravel-query-builder';

    export default {
        name: 'products-index',
        props: {
            model: String,
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

        created: function() {
            Components.Pagination.setTranslations({
                no_results_found: this.$t('indexPage.tablePagination.no_results_found'),
                previous: this.$t('indexPage.tablePagination.previous'),
                next: this.$t('indexPage.tablePagination.next'),
                to: this.$t('indexPage.tablePagination.to'),
                of: this.$t('indexPage.tablePagination.of'),
                results: this.$t('indexPage.tablePagination.results'),
            });
        },

        computed: {
            selectedSingleProduct: function() {
                const selectedProduct = this.$_.filter(this.products.data, (product) => product.uuid == this.bulkForm.products[0])[0];
                return selectedProduct;
            },
            selectedProducts: function() {
                const selectedProducts = this.editMode ? this.$_.filter(this.products.data, (product) => this.bulkForm.products.includes(product.uuid)) : [this.getEmptyProduct()]
                return selectedProducts;
            },
        },

        mounted: function() {
            this.formDialog = this.formDialogOpened;
            this.bulkForm.products = [];

            // Load "selected" product
            if (this.showDialogOpened || this.historyDialogOpened) {
                const matchingProduct = this.$_.first(this.products.data, (prod) => (prod.uuid == this.product.uuid));
                this.bulkForm.products.push(matchingProduct);
                this.showDialog = this.showDialogOpened;
                this.historyDialog = this.historyDialogOpened;
            }

            this.configDatatable();
        },

        methods: {
            configDatatable: function() {
                // Move navigation buttons to improve mobile layout
                const nav = document.querySelector('.bg-white.px-4.py-3');
                nav.parentNode.parentNode.parentNode.parentNode.parentNode.append(nav);
                nav.classList.add('mt-3');

                if (this.$props.products.data.length > 0) {
                    this.$refs.table.changeColumnStatus('description', false);
                }
            },

            getEmptyProduct: function() {
                return {
                    name: null,
                    description: null,
                    price: 0,
                    quantity: 0,
                    image: null,
                }
            },

            destroySingle: function(product) {
                const title = this.$tc('indexPage.deleteConfirmation.title', 1);
                const message = this.$tc('indexPage.deleteConfirmation.message', 1, { product_name: product.name });
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
                const title = this.$tc('indexPage.deleteConfirmation.title', 2);
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
                let message = this.$tc('indexPage.deleteConfirmation.message', 2);
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
