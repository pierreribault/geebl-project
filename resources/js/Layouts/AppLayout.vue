<script setup>
import { ref, onMounted, reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import axios from "axios";
import Footer from '../Components/Footer.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    Inertia.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    Inertia.post(route('logout'));
};


const searchInput = ref(null);

const emit = defineEmits(['city-changed'])

const state = reactive({
    queryText: "",
    city: {},
    loading: false,
    dropdownResultsOpen: false,
    searchEventsModalOpen: false,
    searchEvents: {},
    upcomingEvents: {},
    promotedEvents: {},
});

const setCity = async (city) => {
    state.city = city;
    state.dropdownResultsOpen = false;
    emit('city-changed', city)
}

const search = async () => {
    state.loading = true;
    const response = await axios.get("/api/events/search", {
        params: {
            query: `${state.queryText}`,
            city: `${state.city.id}`
        }
    });

    state.loading = false;
    state.searchEvents = response.data.data;
}


onMounted(() => {
    const city = usePage().props.value.localizations.current
    state.city = city
    emit('city-changed', city)
    //searchInput.value.focus()
})

const openSearchEventsModal = async () => {
    state.searchEventsModalOpen = true
}

</script>

<template>
    <div>

        <Head :title="title" />

        <JetBanner />

        <div v-bind:class="{ invisible: !state.searchEventsModalOpen }"
            class="h-full w-full z-50 absolute bg-slate-900">
            <div class="drop-shadow-md bg-back">
                <div class="flex container mx-auto items-center px-4 py-4">
                    <span class="text-white hover:text-blue-600 h-5 w-5" @click="state.searchEventsModalOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input ref="searchInput"
                        class="focus:border-transparent focus:ring-0 caret-slate-50 inline-flex border-none justify-center w-full text-sm font-medium text-white bg-transparent focus:outline-none  placeholder:text-xl"
                        type="text" v-model="state.queryText" placeholder="Event, venue, artist..." @input="search" />
                </div>
            </div>
            <div class="container mx-auto text-white mt-4" v-if="state.searchEvents && state.queryText">
                <h3 class="text-2xl font-bold">Results</h3>
                <div class="mt-4 grid grid-cols-3 gap-4">
                    <div v-bind:key="index" v-for="(event, index) in state.searchEvents">
                        <a :href="'/events/' + event.slug"
                            class="block p-10 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
                                event.name
                                }}</h5>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 dark:text-white">{{ event.date }}</span>
                                <a :href="'/events/' + event.slug"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Book</a>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div v-if="state.loading" class="container mx-auto mt-4">
                <svg role="status" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
            </div>
        </div>

        <div class="min-h-screen bg-slate-900 ">
            <nav class="bg-slate-900 border border-back">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                <JetApplicationMark class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <div class="shrink-0 flex items-center sm:ml-10 relative">
                                <button @click="state.dropdownResultsOpen = !state.dropdownResultsOpen" type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-back hover:text-gray-300 text-white focus:outline-none transition"
                                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                                    {{ state.city.name }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div v-bind:class="{ invisible: !state.dropdownResultsOpen }"
                                    class="origin-top-left z-10 absolute w-56 rounded-md shadow-lg bg-back ring-1 ring-black ring-opacity-5 divide-y divide-gray-700 focus:outline-none"
                                    style="top: 60px;" role="menu" aria-orientation="vertical"
                                    aria-labelledby="menu-button" tabindex="-1">
                                    <div v-bind:key="country" v-for="(country, key) in $page.props.localizations.all"
                                        class="py-1" role="none">
                                        <a class="text-gray-500 font-bold uppercase block px-4 py-2 text-sm"
                                            role="menuitem">{{ country.name }}</a>
                                        <a v-bind:key="city" v-for="(city) in country.cities" @click="setCity(city)"
                                            class="text-white hover:text-gray-300 block px-4 py-2 text-sm cursor-pointer"
                                            role="menuitem" tabindex="-1" id="menu-item-0">{{ city.name }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 flex items-center sm:ml-4">
                                <input @click="openSearchEventsModal()"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-medium rounded-md text-gray-500 bg-back hover:text-gray-300 text-white focus:outline-none transition"
                                    type="text" placeholder="Event, venue, artist..." />
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <JetNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Events
                                </JetNavLink>
                                <JetNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                    Profile
                                </JetNavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative" v-if="$page.props.user">
                                <JetDropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos"
                                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover"
                                                :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-back hover:text-gray-300 text-white focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-white">
                                            Manage Account
                                        </div>

                                        <JetDropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                            :href="route('api-tokens.index')">
                                            API Tokens
                                        </JetDropdownLink>

                                        <div class="border-t border-gray-600" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <JetDropdownLink as="button">
                                                Log Out
                                            </JetDropdownLink>
                                        </form>
                                    </template>
                                </JetDropdown>
                            </div>
                            <div v-else>
                                <JetNavLink :href="route('login')">
                                    Login
                                </JetNavLink>
                                <JetNavLink :href="route('register')">
                                    Register
                                </JetNavLink>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
                                @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
                    class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <JetResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </JetResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200" v-if="$page.props.user">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover"
                                    :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <JetResponsiveNavLink :href="route('profile.show')"
                                :active="route().current('profile.show')">
                                Profile
                            </JetResponsiveNavLink>

                            <JetResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </JetResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <JetResponsiveNavLink as="button">
                                    Log Out
                                </JetResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <JetResponsiveNavLink :href="route('teams.show', $page.props.user.current_team)"
                                    :active="route().current('teams.show')">
                                    Team Settings
                                </JetResponsiveNavLink>

                                <JetResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams"
                                    :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </JetResponsiveNavLink>

                                <div class="border-t border-gray-200" />

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Switch Teams
                                </div>

                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <JetResponsiveNavLink as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.user.current_team_id"
                                                    class="mr-2 h-5 w-5 text-green-400" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </JetResponsiveNavLink>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-slate-900 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

        <Footer />

    </div>
</template>
