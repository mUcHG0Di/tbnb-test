<template>
    <div>
        <v-app>

            <ToastMessage />

            <v-app-bar
                dark
                color="#29C2AF"
                elevation="2"
                scroll-target="#scrolling-techniques-7"
            >

                <v-container>
                    <v-row class="px-3">
                        <inertia-link :href="route('home')" class="self-center">
                            <img src="/images/tbnb-logo-white.png" alt="TurnoverBnB" class="w-auto ml-3 h-7">
                        </inertia-link>

                        <v-spacer></v-spacer>

                        <v-menu
                            left
                            bottom
                        >
                            <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="transparent"
                                class="shadow-none"
                                v-bind="attrs"
                                v-on="on"
                            >
                                {{ $page.props.auth.user.name }}

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </v-btn>
                            </template>

                            <v-list>
                                <v-list-item
                                >
                                    <v-list-item-title>
                                        <inertia-link :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </inertia-link>
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-row>
                </v-container>
            </v-app-bar>

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
    import BreezeDropdownLink from '@/Components/Breeze/DropdownLink';

    export default {
        name: 'AppLayout',
        components: {
            ToastMessage,
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
