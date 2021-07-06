<template>
    <form @submit.prevent="store">
        <!-- Button to remove form if is not the first one -->
        <RemoveFormButton
            :index="index"
            @removeForm="$emit('removeForm', index)"
            @showBorder="(val) => showBorder = val"
        />

        <v-row
            :class="{'border-2 rounded-md border-red-400': showBorder, 'border-2 rounded-md border-transparent': !showBorder}"
        >
            <v-col cols="12">
                <v-text-field
                    :label="label('name')"
                    v-model="form.name"
                    required
                    :disabled="busy"
                    :readonly="readonly"
                    :error="hasError(index, 'name')"
                    :error-messages="errorMessage(index, 'name')"
                    @keyup="sync"
                ></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field
                    :label="label('description')"
                    v-model="form.description"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="hasError(index, 'description')"
                    :error-messages="errorMessage(index, 'description')"
                    @keyup="sync"
                ></v-text-field>
            </v-col>

            <v-col
                cols="12"
                sm="6"
                md="6"
            >
                <v-text-field
                    :label="label('price')"
                    v-model.number="form.price"
                    required
                    :append-icon="mdiPlusIcon"
                    :prepend-inner-icon="mdiMinusIcon"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="hasError(index, 'price')"
                    :error-messages="errorMessage(index, 'price')"
                    @keyup="numberKeyUp"
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
                    :label="label('quantity')"
                    v-model.number="form.quantity"
                    required
                    :append-icon="mdiPlusIcon"
                    :prepend-inner-icon="mdiMinusIcon"
                    :disabled="busy"
                    :readonly="readonly"
                    :error="hasError(index, 'quantity')"
                    :error-messages="errorMessage(index, 'quantity')"
                    @keyup="numberKeyUp"
                    @click:prepend-inner="sub('quantity');"
                    @click:append="add('quantity');"
                ></v-text-field>
            </v-col>

            <v-col cols="12" v-if="readonly">
                <v-text-field
                    label="Owner: "
                    :value="(product.owner) ? product.owner.name : ''"
                    :disabled="busy"
                    :readonly="readonly"
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
                    :label="label('product_image')"
                    :error="hasError(index, 'image')"
                    :error-messages="errorMessage(index, 'image')"
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
import RemoveFormButton from '@/Components/Products/RemoveFormButton';
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
    components: {
        RemoveFormButton,
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

    computed: {
        mdiPlusIcon: function() {
            return (!this.readonly) ? 'mdi-plus' : null;
        },
        mdiMinusIcon: function() {
            return (!this.readonly) ? 'mdi-minus' : null;
        },
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

        label: function(field) {
            if (field == 'description') return this.$options.filters.ucFirst(`${field}: `);

            return `${this.$options.filters.ucFirst(field.replaceAll('_', ' '))}: ${(!this.readonly) ? '*' : ''}`
        },
        hasError: function(index, field) {
            return this.errors[`products.${index}.${field}`] != null || this.errors[field] != null;s
        },
        errorMessage: function(index, field) {
            return this.errors[`products.${index}.${field}`] || this.errors[field];
        },

        // Number methods
        numberKeyUp: function(event) {
            event.target.value = this.$options.filters.onlyNumbers(event.target.value);
            sync();
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
