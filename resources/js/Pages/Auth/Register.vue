<template>
    <div>
        <breeze-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <breeze-label for="name" value="Name" />
                <breeze-input id="name" type="text" class="block w-full mt-1" v-model="form.name" required autofocus autocomplete="name" @input="(value) => form.name = value" />
            </div>

            <div class="mt-4">
                <breeze-label for="email" value="Email" />
                <breeze-input id="email" type="email" class="block w-full mt-1" v-model="form.email" required autocomplete="username" @input="(value) => form.username = value" />
            </div>

            <div class="mt-4">
                <breeze-label for="password" value="Password" />
                <breeze-input id="password" type="password" class="block w-full mt-1" v-model="form.password" required autocomplete="new-password" @input="(value) => form.password = value" />
            </div>

            <div class="mt-4">
                <breeze-label for="password_confirmation" value="Confirm Password" />
                <breeze-input id="password_confirmation" type="password" class="block w-full mt-1" v-model="form.password_confirmation" required autocomplete="new-password" @input="(value) => form.password_confirmation = value" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link :href="route('login')" class="text-sm text-gray-600 underline hover:text-gray-900">
                    Already registered?
                </inertia-link>

                <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </breeze-button>
            </div>
        </form>
    </div>
</template>

<script>
    import BreezeButton from '@/Components/Button'
    import BreezeGuestLayout from '@/Layouts/Guest'
    import BreezeInput from '@/Components/Input'
    import BreezeLabel from '@/Components/Label'
    import BreezeValidationErrors from '@/Components/ValidationErrors'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            submit() {
                this.form.post(route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
