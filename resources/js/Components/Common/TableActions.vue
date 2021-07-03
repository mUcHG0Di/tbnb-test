<template>
    <v-row>
        <v-card-title class=" text-h5">{{ title }}</v-card-title>

        <v-spacer></v-spacer>

        <v-btn
            elevation="1"
            color="success"
            class="m-2"
            @click="$emit('add')"
        >
            <v-icon small class="mr-1">mdi-plus-box-outline</v-icon>
            Add
        </v-btn>
        <v-btn
            elevation="1"
            color="success"
            class="m-2"
            @click="$emit('addMultiple')"
        >
            <v-icon small class="mr-1">mdi-plus-box-multiple-outline</v-icon>
            Bulk add
        </v-btn>
        <v-btn
            elevation="1"
            color="orange darken-2"
            class="m-2 white--text"
            :disabled="selectedCount <= 0"
            @click="$emit('edit')"
        >
            <v-icon small class="mr-1">mdi-square-edit-outline</v-icon>
            Bulk update
        </v-btn>
        <v-btn
            elevation="1"
            color="error"
            class="m-2"
            :disabled="selectedCount <= 0"
            @click="$emit('destroy')"
        >
            <v-icon small class="mr-1">mdi-delete-sweep-outline</v-icon>
            Bulk delete
        </v-btn>
    </v-row>
</template>

<script>
export default {
    props: {
        title: String,
        selectedCount: Number,
    },

    setup() {

    },
}
</script>

<style scoped>

</style>
