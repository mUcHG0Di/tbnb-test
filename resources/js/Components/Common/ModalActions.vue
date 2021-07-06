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
            {{ $t('modalActions.edit') }}
        </v-btn>

        <v-btn
            v-if="!editMode && actionable"
            :disabled="processing"
            color="red darken-1"
            text
            class="text--white"
            @click="$emit('destroy')"
        >
            {{ $t('modalActions.delete') }}
        </v-btn>

        <v-spacer></v-spacer>

        <v-btn
            v-show="!editMode"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('close')"
        >
            {{ $t('modalActions.close') }}
        </v-btn>
        <v-btn
            v-show="editMode"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('cancel')"
        >
            {{ $t('modalActions.cancel') }}
        </v-btn>
        <v-btn
            v-show="editMode"
            :loading="processing"
            :disabled="processing"
            color="blue darken-1"
            text
            @click="$emit('save')"
        >
            {{ $t('modalActions.save') }}
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
