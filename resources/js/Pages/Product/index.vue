<template>
    <app-layout>
        <v-container>
            <v-row>
                <v-card-title>Products</v-card-title>

                <v-spacer></v-spacer>

                <v-dialog
                    v-model="form"
                    persistent
                    max-width="600px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn dark color="success" class="m-2" v-bind="attrs" v-on="on">
                            <v-icon small class="mr-1">mdi-plus</v-icon>
                            Create
                        </v-btn>
                    </template>
                    <Form @close="form = !form" />
                </v-dialog>
                <v-btn dark color="primary" class="m-2">
                    <v-icon small class="mr-1">mdi-pencil</v-icon>
                    Update
                </v-btn>
                <v-btn dark color="error" class="m-2">
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
                        <th @click.prevent="sortBy('uuid')">UUID</th>
                        <th v-show="showColumn('name')" @click.prevent="sortBy('name')">Name</th>
                        <th v-show="showColumn('description')" @click.prevent="sortBy('description')">Description</th>
                        <th v-show="showColumn('price')" @click.prevent="sortBy('price')">Price</th>
                        <th v-show="showColumn('quantity')" @click.prevent="sortBy('quantity')">Quantity</th>
                    </tr>
                </template>

                <template #body>
                    <tr v-for="product in products.data" :key="product.id">
                        <td>{{ product.uuid }}</td>
                        <td v-show="showColumn('name')">{{ product.name }}</td>
                        <td v-show="showColumn('description')">{{ product.description }}</td>
                        <td v-show="showColumn('price')">{{ product.price }}</td>
                        <td v-show="showColumn('quantity')">{{ product.quantity }}</td>
                    </tr>
                </template>
            </Table>

        </v-container>

    </app-layout>
</template>


<script>
    import AppLayout from '@/Layouts/AppLayout';
    import Form from './Form';
    import { InteractsWithQueryBuilder, Tailwind2 } from '@protonemedia/inertiajs-tables-laravel-query-builder';

    export default {
        name: 'products-index',
        props: {
            products: Object,
        },
        mixins: [ InteractsWithQueryBuilder ],
        components: {
            AppLayout,
            Form,
            Table: Tailwind2.Table,
        },

        data: function() {
            return {
                form: false,
            };
        },
    }
</script>
