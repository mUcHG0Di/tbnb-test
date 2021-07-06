<template>
    <div>
        <div class="mb-4 text-sm text-gray-600">
            {{ $t('auth.forgotPassword.message') }}
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <breeze-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <breeze-label for="email" value="Email" />
                <breeze-input id="email" type="email" class="block w-full mt-1" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ $t('auth.forgotPassword.confirm') }}
                </breeze-button>
            </div>
        </form>
    </div>
</template>

<script>
    import BreezeButton from '@/Components/Breeze/Button'
    import BreezeGuestLayout from "@/Layouts/Guest"
    import BreezeInput from '@/Components/Breeze/Input'
    import BreezeLabel from '@/Components/Breeze/Label'
    import BreezeValidationErrors from '@/Components/Breeze/ValidationErrors'

    export default {
        layout: BreezeGuestLayout,

        components: {
            BreezeButton,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
        },

        props: {
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(route('password.email'))
            }
        }
    }
</script>
