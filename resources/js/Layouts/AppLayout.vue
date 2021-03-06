<script setup>
import { ref, onMounted, reactive, defineEmits } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm, usePage } from '@inertiajs/inertia-vue3';
import JetApplicationMark from '@/Jetstream/ApplicationMark.vue';
import JetBanner from '@/Jetstream/Banner.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';
import JetDropdownLink from '@/Jetstream/DropdownLink.vue';
import JetNavLink from '@/Jetstream/NavLink.vue';
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue';
import axios from "axios";
import Footer from '../Components/Footer.vue';
import JetDialogModal from '@/Jetstream/DialogModal.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';

defineProps({
    title: String,
});

console.log(usePage().props.value.user)

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

const createNewCompany = ref(false);

const form = useForm({
    name: '',
    crn: '',
    location: '',
});

const createModalCompany = (transaction) => {
    createNewCompany.value = true;
};

const createCompany = async () => {
    form.post(route('companies.store'), {
        preserveScroll: true,
        onSuccess: (res) => console.log(res.props.data),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
}

const closeModalCompany = () => {
    createNewCompany.value = false;

    form.reset();
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
    console.log(city)
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

const isProfessional = () => {
    if (usePage().props.value.user == null) {
        return false;
    }

    return usePage().props.value.user.is_admin || usePage().props.value.user.company_id != null;
}

onMounted(() => {
    const city = usePage().props.value.localizations.current
    state.city = city
    emit('city-changed', city)
    console.log(city)
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

        <JetDialogModal :show="createNewCompany" @close="closeModalCompany">
            <template #title>
                Create your company
            </template>

            <template #content>
                You will be able to invite your team to join your company and create events.

                <div class="mt-4">
                    <JetInput v-model="form.name" type="text" class="mt-1 block w-full" placeholder="Name" />
                </div>
                <div class="mt-4">
                    <JetInput v-model="form.crn" type="text" class="mt-1 block w-full" placeholder="CRN" />
                </div>
                <div class="mt-4">
                    <JetInput v-model="form.location" type="text" class="mt-1 block w-full" placeholder="Location" />
                </div>
            </template>

            <template #footer>
                <JetSecondaryButton @click="closeModalCompany">
                    Abort
                </JetSecondaryButton>

                <JetButton class="ml-3" @click="createCompany">
                    Create your company
                </JetButton>
            </template>
        </JetDialogModal>

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
                <div class="grid grid-cols-3 gap-8 items-center mt-4">
                    <a v-bind:key="index" v-for="(event, index) in state.searchEvents" :href="'/events/' + event.slug" class="flex relative h-60 w-full shadow-md rounded">
                        <img class="inset-0 h-full w-full object-cover object-center absolute rounded-xl"
                                    :src="event.cover_url">
                        <div class="block min-h-full min-w-full bg-black absolute opacity-25 rounded-xl"></div>
                        <div class="ml-2 flex flex-col mt-auto mb-4">
                            <h5 class="text-lg font-bold text-white max-w-xs relative">{{ event.name }}</h5>
                            <p class="text-sm text-gray-300 relative">{{ event.beautiful_date }}</p>
                        </div>
                    </a>
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
                                    <button @click="state.dropdownResultsOpen = !state.dropdownResultsOpen"
                                        type="button"
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
                                        <div v-bind:key="country"
                                            v-for="(country, key) in $page.props.localizations.all" class="py-1"
                                            role="none">
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
                                    <JetNavLink :href="route('home')" :active="route().current('home')">
                                        Events
                                    </JetNavLink>
                                    <JetNavLink :href="route('articles.index')"
                                        :active="route().current('articles.index')">
                                        Articles
                                    </JetNavLink>
                                    <JetNavLink :href="route('shop.index')" :active="route().current('shop.index')"
                                        v-if="$page.props.user && $page.props.user.company_id != null">
                                        Shop
                                    </JetNavLink>
                                    <JetNavLink :href="route('user.tickets')" :active="route().current('user.tickets')"
                                        v-if="$page.props.user">
                                        My tickets
                                    </JetNavLink>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ml-6">
                                <!-- Settings Dropdown -->
                                <JetButton v-if="$page.props.user && $page.props.user.company_id == null"
                                    @click="createModalCompany">Create Company</JetButton>
                                <div class=" ml-3 relative" v-if="$page.props.user">
                                    <JetDropdown align="right" width="48">
                                        <template #trigger>
                                            <button v-if="$page.props.jetstream.managesProfilePhotos"
                                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                <img class="h-8 w-8 rounded-full object-cover"
                                                    :src="$page.props.user.profile_photo_url"
                                                    :alt="$page.props.user.name">
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

                                            <a class="block w-full px-4 py-2 text-sm leading-5 text-gray-300 text-left hover:text-white focus:outline-none focus:bg-gray-100 transition"
                                                :href="route('nova.login')" v-if="isProfessional()">
                                                Dashboard
                                            </a>

                                            <a class="block w-full px-4 py-2 text-sm leading-5 text-gray-300 text-left hover:text-white focus:outline-none focus:bg-gray-100 transition"
                                                :href="route('profile.show')">
                                                Profile
                                            </a>

                                            <a class="block w-full px-4 py-2 text-sm leading-5 text-gray-300 text-left hover:text-white focus:outline-none focus:bg-gray-100 transition"
                                                v-if="$page.props.user && $page.props.user.is_consumer"
                                                :href="route('organizers.validator')">
                                                Scan
                                            </a>

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
