<template>
    <v-card>
            <v-card-title>
                <span class="text-h5">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <form @submit.prevent="store">
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Name: *"
                                    v-model="form.name"
                                    required
                                    :error="form.errors.name != null"
                                    :error-messages="form.errors.name"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                label="Description: "
                                v-model="form.description"
                                :error="form.errors.description != null"
                                :error-messages="form.errors.description"
                                ></v-text-field>
                            </v-col>

                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                    label="Price: *"
                                    type="number"
                                    v-model="form.price"
                                    required
                                    :error="form.errors.price != null"
                                    :error-messages="form.errors.price"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="6"
                                md="6"
                            >
                                <v-text-field
                                    label="Quantity: *"
                                    type="number"
                                    v-model="form.quantity"
                                    required
                                    :error="form.errors.quantity != null"
                                    :error-messages="form.errors.quantity"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </form>
                </v-container>
            <small>* Indicates required field</small>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                :disabled="form.processing"
                color="blue darken-1"
                text
                @click="$emit('close')"
            >
                Close
            </v-btn>
            <v-btn
                :loading="form.processing"
                :disabled="form.processing"
                color="blue darken-1"
                text
                @click="store"
            >
                Save
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: 'product-form',
    props: {
        product: {
            type: Object,
            default: () => (null),
        },
    },

    data: function() {
        return {
            form: this.$inertia.form({
				_method: 'POST',
				name: null,
				description: null,
                price: 0,
                quantity: 0,
			}),
        };
    },

    computed: {
        title: function() {
            return (this.product == null) ? 'Create product' : 'Edit product';
        },
    },

    methods: {
        store: function() {
            this.form.post(route('products.store'), {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
				onError: () => {},
			});
        },
    },
}
</script>

<style scoped>
input[type="text"]:focus {
    border: none !important;
}
</style>
