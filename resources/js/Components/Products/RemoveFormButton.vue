<template>
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
                    @mouseenter="$emit('showBorder', true)"
                    @mouseleave="$emit('showBorder', false)"
                >
                    <v-icon dark x-small>
                        mdi-minus
                    </v-icon>
                </v-btn>
            </template>
            {{ $t('removeFormButton.tooltip') }}
        </v-tooltip>
    </v-row>
</template>

<script>
export default {
    props: {
        index: Number,
    },
    emits: ['showBorder'],
}
</script>
