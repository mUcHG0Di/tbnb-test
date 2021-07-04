<template>
    <v-row>
        <v-card-title class=" text-h5">{{ title }}</v-card-title>

        <v-spacer></v-spacer>


        <div class="flex flex-row flex-wrap justify-end w-full mb-5 sm:w-auto sm:mb-0">
            <div class="w-1/2 px-2 my-2 sm:w-auto">
                <v-btn
                    elevation="1"
                    color="success"
                    class="w-full"
                    @click="$emit('add')"
                >
                    <v-icon small class="mr-1">mdi-plus-box-outline</v-icon>
                    Add
                </v-btn>
            </div>
            <div class="w-1/2 px-2 my-2 sm:w-auto">
                <v-btn
                    elevation="1"
                    color="success"
                    class="w-full"
                    @click="$emit('addMultiple')"
                >
                    <v-icon small class="mr-1">mdi-plus-box-multiple-outline</v-icon>
                    Bulk add
                </v-btn>
            </div>
            <div class="w-1/2 px-2 my-2 sm:w-auto">
                <v-btn
                    elevation="1"
                    color="orange darken-2"
                    class="w-full white--text"
                    :disabled="selectedCount <= 0"
                    @click="$emit('edit')"
                >
                    <v-icon small class="mr-1">mdi-square-edit-outline</v-icon>
                    Bulk update
                </v-btn>
            </div>
            <div class="w-1/2 px-2 my-2 sm:w-auto">
                <v-btn
                    elevation="1"
                    color="error"
                    class="w-full"
                    :disabled="selectedCount <= 0"
                    @click="$emit('destroy')"
                >
                    <v-icon small class="mr-1">mdi-delete-sweep-outline</v-icon>
                    Bulk delete
                </v-btn>
            </div>
        </div>
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
