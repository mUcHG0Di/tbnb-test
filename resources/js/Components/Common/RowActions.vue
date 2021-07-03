<template>
    <div class="grid flex-wrap grid-cols-2 w-52">
        <v-btn
            x-small
            elevation="1"
            color="primary"
            class="m-2"
            @click="$emit('show')"
        >
            <v-icon small class="mr-1">mdi-eye</v-icon>
            Show
        </v-btn>
        <v-btn
            x-small
            elevation="1"
            color="cyan darken-1"
            class="m-2 white--text"
            @click="$emit('history')"
        >
            <v-icon small class="mr-1">mdi-clipboard-list</v-icon>
            History
        </v-btn>
        <v-btn
            x-small
            elevation="1"
            color="orange darken-2"
            class="m-2 white--text"
            @click="$emit('edit')"
        >
            <v-icon small class="mr-1">mdi-pencil</v-icon>
            Update
        </v-btn>
        <v-btn
            x-small
            elevation="1"
            color="error"
            class="m-2"
            @click="$emit('destroy')"
        >
            <v-icon small class="mr-1">mdi-delete</v-icon>
            Delete
        </v-btn>
    </div>
</template>

<script>
export default {
    setup() {

    },
}
</script>

<style scoped>

</style>
