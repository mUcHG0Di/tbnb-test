<template>
    <v-row>
        <v-card-title>{{ title }}</v-card-title>

        <v-spacer></v-spacer>

        <v-btn
            elevation="1"
            color="primary"
            class="m-2"
            :disabled="selectedCount != 1"
            @click="$emit('show')"
        >
            <v-icon small class="mr-1">mdi-eye</v-icon>
            Show
        </v-btn>
        <v-btn
            elevation="1"
            color="success"
            class="m-2"
            @click="$emit('add')"
        >
            <v-icon small class="mr-1">mdi-plus</v-icon>
            Add
        </v-btn>
        <v-btn
            elevation="1"
            color="orange darken-2"
            class="m-2 white--text"
            :disabled="selectedCount <= 0"
            @click="$emit('edit')"
        >
            <v-icon small class="mr-1">mdi-pencil</v-icon>
            Update
        </v-btn>
        <v-btn
            elevation="1"
            color="error"
            class="m-2"
            :disabled="selectedCount <= 0"
            @click="$emit('destroy')"
        >
            <v-icon small class="mr-1">mdi-delete</v-icon>
            Delete
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
