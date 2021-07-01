<template>
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
</template>

<script>
export default {
    name: 'product-form',
    props: {
        product: Object,
    },

    data: function() {
        return {
            form: this.$inertia.form({
				name: null,
				description: null,
                price: 0,
                quantity: 0,
			}, {
                resetOnSuccess : true,
            }),
        };
    },

    mounted: function() {
        this.formFill();
    },

    methods: {
        formFill: function() {
            Object.assign(this.form, this.product);
        },

        store: function() {
            this.form.post(route('products.store'), {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
				onError: () => {},
			});
        },

        update: function() {
            this.form.put(route('products.update'), {
				preserveScroll: true,
				onSuccess: () => { this.$emit('close'); },
				onError: () => {},
			});
        }
    },
}
</script>

<style>
input[type="text"]:focus {
    border: none;
    -tw-ring-color: rgba(255, 255, 255, 0);
}

.col {
    padding-top: 0;
    padding-bottom: 0;
}


</style>
