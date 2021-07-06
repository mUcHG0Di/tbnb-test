<template>
    <div>
        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
        </div>

        <div class="mb-4 text-sm font-medium text-green-600" v-if="verificationLinkSent" >
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-between mt-4">
                <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Resend Verification Email
                </breeze-button>

                <inertia-link :href="route('logout')" method="post" as="button" class="text-sm text-gray-600 underline hover:text-gray-900">Log Out</inertia-link>
            </div>
        </form>
    </div>
</template>

<script>
    import BreezeButton from '@/Components/Breeze/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
        },

        props: {
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form()
            }
        },

        methods: {
            submit() {
                this.form.post(route('verification.send'))
            },
        },

        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>
