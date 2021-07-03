<template>
    <v-card-actions>
        <v-btn
            v-if="!editMode && actionable"
            :disabled="processing"
            color="orange darken-1"
            text
            class="text--white"
            @click="$emit('editMode', true);"
        >
            Edit
        </v-btn>

        <v-btn
            v-if="!editMode && actionable"
            :disabled="processing"
            color="red darken-1"
            text
            class="text--white"
            @click="$emit('destroy')"
        >
            Delete
        </v-btn>

        <v-spacer></v-spacer>

        <v-btn
            v-show="!editMode"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('close')"
        >
            Close
        </v-btn>
        <v-btn
            v-show="editMode"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('cancel')"
        >
            Cancel
        </v-btn>
        <v-btn
            v-show="editMode"
            :loading="processing"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('save')"
        >
            Save
        </v-btn>
    </v-card-actions>
</template>

<script>
export default {
    props: {
        actionable: Boolean,
        editMode: Boolean,
        processing: Boolean,
    },

    data: function() {
        return {};
    },
}
</script>

<style scoped>

</style>
