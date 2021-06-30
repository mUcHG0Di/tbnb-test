<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex px-4">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <inertia-link :href="route('dashboard')">
                                    <jet-application-mark class="block h-9 w-auto" />
                                </inertia-link>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Navigation Links -->
                            <div class="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('reportes.familiaCliente')" :active="route().current('reportes.familiaCliente')">
                                    Rep. Cliente
                                </jet-nav-link>

                                <jet-nav-link :href="route('reportes.familiaCorporacion')" :active="route().current('reportes.familiaCorporacion')">
                                    Rep. Corporación
                                </jet-nav-link>

                                <!-- <jet-dropdown align="right" width="48" class="inline-flex py-4" managementClasses="mt-10" scrollClass="max-h-60 overflow-y-scroll">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center px-6 py-2 bg-primary-400 text-sm leading-4 font-medium text-white hover:text-white-700 focus:outline-none transition ease-in-out duration-150">
                                            Reportes

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <jet-dropdown-link :href="route('reportes.familiaCliente')">
                                                Familia Cliente
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('reportes.familiaCorporacion')">
                                                Familia Corporación
                                        </jet-dropdown-link>
                                    </template>
                                </jet-dropdown> -->

                                <!-- <jet-nav-link :href="route('reportes.familiaCliente')" :active="route().current('reportes.familiaCliente')">
                                    Reportes Clientes
                                </jet-nav-link>

                                <jet-nav-link :href="route('reportes.familiaCorporacion')" :active="route().current('reportes.familiaCorporacion')">
                                    Reportes Corporacion
                                </jet-nav-link> -->

                                <jet-dropdown align="right" width="48" class="inline-flex py-4" managementClasses="mt-10" scrollClass="overflow-y-hidden">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center px-6 py-2 bg-primary-400 text-sm leading-4 font-medium text-white hover:text-white-700 focus:outline-none transition ease-in-out duration-150">
                                            Adm. Cliente

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <!-- <div class="m-2">
                                            <input type="text" placeholder="Buscar" class="searcher max-w-max object-contain w-full border-t-0 border-l-0 border-r-0" @click.capture.stop @keyup="filterClienteAbms($event)" >
                                        </div> -->
                                        <template v-for="(curRoute, index) in filteredClienteAbms" :key="index">
                                        <!-- <template v-for="(curRoute, index) in $page.props.rolAdministradorRoutes" :key="index"> -->
                                            <jet-dropdown-link :href="route(`management.${((index == 'dolar_historial') ? 'dolar_historiales' : index)}.index`)">
                                                {{ index.replaceAll('_', ' ')
                                                    .replaceAll('mclientes', 'clientes')
                                                    .replaceAll('corporacion', 'corporación')
                                                    .replaceAll('presupuestos', 'carga de presupuestos')
                                                    .split(' ')
                                                    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
                                                    .join(' ') }}
                                            </jet-dropdown-link>

                                        </template>
                                          <jet-dropdown-link :href="route('management.presupuestos.index.readOnly')">
                                                Ver Presupuestos
                                            </jet-dropdown-link>
                                    </template>
                                </jet-dropdown>

                                <jet-dropdown align="right" width="48" class="inline-flex py-4" managementClasses="mt-10" scrollClass="overflow-y-hidden">
                                    <template #trigger>
                                        <button type="button" class="inline-flex items-center px-6 py-2 bg-primary-400 text-sm leading-4 font-medium text-white hover:text-white-700 focus:outline-none transition ease-in-out duration-150">
                                            Adm. Mambo

                                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <!-- <div class="m-2">
                                            <input type="text" placeholder="Buscar" class="searcher max-w-max object-contain w-full border-t-0 border-l-0 border-r-0" @click.capture.stop @keyup="filterMamboAbms($event)" >
                                        </div> -->

                                        <!-- <template v-for="(curRoute, index) in $page.props.rolMamboRoutes" :key="index"> -->
                                        <template v-for="(curRoute, index) in filteredMamboAbms" :key="index">
                                            <jet-dropdown-link :href="route(`management.${((index == 'dolar_historial') ? 'dolar_historiales' : index)}.index`)">
                                                {{ index.replaceAll('_', ' ').replaceAll('mclientes', 'clientes').split(' ')
                                                    .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
                                                    .join(' ') }}
                                            </jet-dropdown-link>
                                        </template>
                                    </template>
                                </jet-dropdown>
                            </div>

                            <div class="ml-3 relative">
                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <jet-dropdown-link as="button" custom-class="text-primary-400 font-semibold">
                                        {{$page.props.user.name}}
                                        <br>
                                        <div class="inline-flex">
                                            Salir
                                            <img
                                            src="/images/navbar/navbar-logout-icon.png"
                                            alt="Salir"
                                            title="Salir"
                                            class="w-3 h-3 mt-1 ml-2">
                                        </div>
                                    </jet-dropdown-link>
                                </form>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <!-- <div class="pt-2 pb-3 space-y-1">
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </jet-responsive-nav-link>
                    </div> -->

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3" >
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                API Tokens
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link as="button">
                                    Log Out
                                </jet-responsive-nav-link>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <jet-responsive-nav-link :href="route('teams.show', $page.props.user.current_team)" :active="route().current('teams.show')">
                                    Team Settings
                                </jet-responsive-nav-link>

                                <jet-responsive-nav-link :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </jet-responsive-nav-link>

                                <div class="border-t border-gray-200"></div>

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Switch Teams
                                </div>

                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <jet-responsive-nav-link as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </jet-responsive-nav-link>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <ToastMessage />

            <!-- Page Heading -->
            <header class="bg-white shadow px-4" v-if="showCorpSelect">
                <div v-if="false" class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl font-semibold px-4">
                    <!-- <slot name="header"></slot> -->
                    {{ route().current().split('.')[1].replaceAll('_', ' ').split(' ')
                            .map((s) => s.charAt(0).toUpperCase() + s.substring(1))
                            .join(' ') }}
                </div>

                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8 text-xl font-semibold px-4">
                    <MyMultiSelect label="Corporación:" valueProp="id" labelSelect="nombre" :canDeselect="false" :options="corporaciones" :error="null" :value="form.corporacion_id" @input="(newVal) => {changeCorp(newVal)}" />
                </div>
            </header>

            <!-- <header class="bg-white shadow px-4" v-if="route().current().includes('reportes')">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl font-semibold px-4">
                    Reportes
                </div>
            </header> -->

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script>
    import { usePage } from '@inertiajs/inertia-vue3';
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

    import MyMultiSelect from '@/Components/Common/MultiSelect';
    import ToastMessage from '@/Components/Common/ToastMessage';

    export default {
        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,

            ToastMessage,
            MyMultiSelect,
        },

        mounted: function() {
            if (this.showCorpSelect) {
                this.getCorps();
            }
        },

        data() {
            return {
                showingNavigationDropdown: false,
                filteredMamboAbms: this.$page.props.rolMamboRoutes,
                filteredClienteAbms: this.$page.props.rolAdministradorRoutes,
                corporaciones: [],
                form: this.$inertia.form({
                    _method: 'PUT',
                    corporacion_id: this.$page.props.user.corporacion_id,
                }, {
                    resetOnSuccess: false,
                }),
            }
        },
    }
</script>

<style lang="sass">
.common-link
    @apply text-blue-400 hover:text-blue-600 no-underline hover:underline
</style>

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
