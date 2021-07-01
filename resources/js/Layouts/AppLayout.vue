<template>
    <div>
        <div>
            <v-card
                class="mx-auto overflow-hidden"
            >
                <v-app-bar
                    color="deep-purple"
                    dark
                >
                <v-app-bar-nav-icon @click="drawer = true"></v-app-bar-nav-icon>

                <v-toolbar-title>TurnoverBnB</v-toolbar-title>
                </v-app-bar>

                <v-navigation-drawer
                    v-model="drawer"
                    absolute
                    temporary
                >
                    <v-list
                        nav
                        dense
                    >
                        <v-list-item-group
                            v-model="group"
                            active-class="deep-purple--text text--accent-4"
                        >
                            <v-list-item>
                                <v-list-item-icon>
                                <v-icon>mdi-briefcase</v-icon>
                                </v-list-item-icon>
                                <v-list-item-title>Products</v-list-item-title>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-navigation-drawer>

                <main>
                <slot></slot>
            </main>
            </v-card>

        </div>
    </div>
</template>

<script>
    import ToastMessage from '../Components/Common/ToastMessage';

    export default {
        name: 'AppLayout',
        components: {
            ToastMessage,
        },

        mounted: function() {

        },

        data() {
            return {
                drawer: false,
                group: null,
                items: [
                    {
                        title: "Fee Request",
                        icon: "mdi-cash-multiple",
                        method: () => this.feeRequest()
                    },
                    {
                        title: "Profile",
                        icon: "mdi-account-badge-horizontal",
                        method: () => this.profile()
                    }
                ]
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
