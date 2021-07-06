<template>
    <div>
        <v-app>

            <ToastMessage />

            <AppBar />

            <v-sheet
                id="scrolling-techniques-7"
                class="h-full overflow-y-auto"
            >
                <v-container style="padding-top: 15px; padding-bottom: 15px; height: 100%;">
                    <main>
                        <slot></slot>
                    </main>
                </v-container>
            </v-sheet>
        </v-app>
    </div>
</template>

<script>
    import ToastMessage from '@/Components/Common/ToastMessage';
    import AppBar from '@/Components/Common/AppBar';
    import BreezeDropdownLink from '@/Components/Breeze/DropdownLink';

    export default {
        name: 'AppLayout',
        components: {
            ToastMessage,
            AppBar,
            BreezeDropdownLink,
        },

        data() {
            return {
                drawer: false,
                group: null,
            };
        },
    }
</script>

<style>
#nprogress .spinner {
    top: calc(50vh - 12px) !important;
    right: calc(50vw - 12px) !important;
}

#nprogress .spinner-icon {
    width: 32px !important;
    height: 32px !important;
}
</style>
