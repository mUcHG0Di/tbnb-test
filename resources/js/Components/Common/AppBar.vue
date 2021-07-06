<template>
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
                                    {{ $t('appBar.logout') }}
                                </inertia-link>
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-row>
        </v-container>
    </v-app-bar>
</template>
