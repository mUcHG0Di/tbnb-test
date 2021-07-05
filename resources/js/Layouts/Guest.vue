<template>
    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0">
        <div class="mb-3">
            <inertia-link href="/">
                <breeze-application-logo class="h-auto text-gray-500 fill-current w-52" />
            </inertia-link>
        </div>

        <div class="px-6 py-4 mt-6 overflow-hidden bg-white shadow-md w-7/8 md:w-full sm:max-w-lg sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>

<script>
    import BreezeApplicationLogo from '@/Components/ApplicationLogo'

    export default {
        components: {
            BreezeApplicationLogo,
        }
    }
</script>
