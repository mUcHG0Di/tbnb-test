<template>
    <div>
        <breeze-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <breeze-label for="email" value="Email" />
                <breeze-input id="email" type="email" class="block w-full mt-1" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <breeze-label for="password" value="Password" />
                <breeze-input id="password" type="password" class="block w-full mt-1" v-model="form.password" @input="(value) => form.password = value" required autocomplete="current-password" />
            </div>

            <div class="flex flex-row justify-between mt-4">
                <label class="flex items-center">
                    <breeze-checkbox name="remember" v-model="form.remember"/>
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                <inertia-link v-if="canResetPassword" :href="route('password.request')" class="ml-4 text-sm text-gray-600 underline hover:text-gray-900">
                    Forgot your password?
                </inertia-link>
            </div>

            <div class="flex items-center justify-end mt-4">
                <inertia-link :href="route('register')" class="text-sm text-gray-600 underline hover:text-gray-900">
                    Not registered?
                </inertia-link>

                <breeze-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </breeze-button>
            </div>
        </form>
    </div>
</template>

<script>
    import BreezeButton from '@/Components/Breeze/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Breeze/Input'
    import BreezeCheckbox from '@/Components/Breeze/Checkbox'
    import BreezeLabel from '@/Components/Breeze/Label'
    import BreezeValidationErrors from '@/Components/Breeze/ValidationErrors'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeCheckbox,
            BreezeLabel,
            BreezeValidationErrors
        },

        props: {
            canResetPassword: Boolean,
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                console.log(this.$_.pick(this.form, ['email', 'password']));
                this.form.post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
            }
        }
    }
</script>
