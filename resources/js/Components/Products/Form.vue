<template>
    <form @submit.prevent="store">
        <!-- Button to remove form if is not the first one -->
        <v-row v-if="index > 0">
            <v-spacer></v-spacer>

            <v-tooltip
                bottom
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                        class="mx-2"
                        fab
                        dark
                        x-small
                        color="red"
                        v-on="on"
                        @click="$emit('removeForm', index)"
                        @mouseenter="showBorder = true"
                        @mouseleave="showBorder = false"
                    >
                        <v-icon dark x-small>
                            mdi-minus
                        </v-icon>
                    </v-btn>
                </template>
                Remove this form
            </v-tooltip>
        </v-row>

        <v-row
            :class="{'border-2 border-red-400': showBorder, 'border-2 border-transparent': !showBorder}"
        >
            <v-col cols="12">
                <v-text-field
                    :label="`Name: ${(!readonly) ? '*' : ''}`"
                    v-model="form.name"
                    required
                    :disabled="busy"
                    :readonly="readonly"
                    :error="errors[`products.${index}.name`] != null || errors['name'] != null"
                    :error-messages="errors[`products.${index}.name`] || errors['name']"
                    @keyup="sync"
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field
                    label="Description: "
                    v-model="form.description"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="errors[`products.${index}.description`] != null || errors['description'] != null"
                    :error-messages="errors[`products.${index}.description`] || errors['description']"
                    @keyup="sync"
                ></v-text-field>
            </v-col>

            <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                    :label="`Price: ${(!readonly) ? '*' : ''}`"
                    v-model.number="form.price"
                    required
                    :append-icon="(!readonly) ? 'mdi-plus' : null"
                    :prepend-inner-icon="(!readonly) ? 'mdi-minus' : null"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="errors[`products.${index}.price`] != null || errors['price'] != null"
                    :error-messages="errors[`products.${index}.price`] || errors['price']"
                    @keyup="$event.target.value = $options.filters.onlyNumbers($event.target.value); sync()"
                    @click:prepend-inner="sub('price');"
                    @click:append="add('price');"
                ></v-text-field>
            </v-col>
            <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                    :label="`Quantity: ${(!readonly) ? '*' : ''}`"
                    v-model.number="form.quantity"
                    required
                    :append-icon="(!readonly) ? 'mdi-plus' : null"
                    :prepend-inner-icon="(!readonly) ? 'mdi-minus' : null"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="errors[`products.${index}.quantity`] != null || errors['quantity'] != null"
                    :error-messages="errors[`products.${index}.quantity`] || errors['quantity']"
                    @keyup="$event.target.value = $options.filters.onlyNumbers($event.target.value); sync()"
                    @click:prepend-inner="sub('quantity');"
                    @click:append="add('quantity');"
                ></v-text-field>
            </v-col>

            <v-col
                v-if="requiresImage"
                cols="12"
            >
                <v-file-input
                    v-model="form.image"
                    show-size
                    accept="image/*"
                    :label="`Product image: ${(!readonly) ? '*' : ''}`"
                    :error="errors[`products.${index}.image`] != null || errors['image'] != null"
                    :error-messages="errors[`products.${index}.image`] || errors['image']"
                ></v-file-input>

                <div
                    v-if="imageSelected"
                    class="w-full"
                >
                    <h3 class="mb-5">Image selected:</h3>

                    <img
                        :src="imageSelected"
                        alt="Selected image"
                        title="Selected image"
                        class="w-3/5 h-auto mx-auto"
                    >
                </div>
            </v-col>
        </v-row>
    </form>
</template>

<script>
export default {
    name: 'product-form',
    props: {
        product: Object,
        index: {
            type: Number,
            default: () => 0,
        },
        errors: Object,
        busy: Boolean,
        readonly: Boolean,
        requiresImage: Boolean,
    },
    emits: ['syncForm', 'removeForm'],

    data: function() {
        return {
            showBorder: false,
            imageSelected: null,
            form: {
				name: null,
				description: null,
                price: 0,
                quantity: 0,
                image: null,
			}
        };
    },

    mounted: function() {
        this.formFill();
    },

    watch: {
        "product": function() {
            this.formFill();
        },
        "form.image": function(newVal, oldVal) {
            if (newVal == null) {
                this.imageSelected = null;
            } else {
                this.imageSelected = URL.createObjectURL(newVal) || null;
            }

            this.sync();
        },
    },

    methods: {
        formFill: function() {
            // Assign values, except for image
            Object.assign(this.form, this.$_.pick(this.product, ['name', 'description', 'price', 'quantity']));
        },

        sub: function(property) {
            if (this.readonly) return;
            if (this.form[property] > 0) {
                this.form[property] = parseInt(this.form[property], 10) - 1;
                this.sync();
            }
        },
        add: function(property) {
            if (this.readonly) return;
            this.form[property] = parseInt(this.form[property], 10) + 1
            this.sync();
        },

        sync: function() {
            this.$emit('syncForm', this.form);
        },
    },
}
</script>

<style>
.col {
    padding-top: 0;
    padding-bottom: 0;
}
</style>

<style lang="sass">
input[type="text"]:focus
    @apply focus:ring-0
</style>
