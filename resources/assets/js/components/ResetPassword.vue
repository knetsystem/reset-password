<template>
    <div>
        <form @submit.prevent="sendResetRequest">

            <div class="form-group">
                <label for="Password">Enter your new password</label>
                <input type="password" @keydown="hasErrors = false" :disabled="sendok || loading" required class="form-control" id="Password" v-model="password" aria-describedby="passwordHelp" placeholder="Enter your new password">
            </div>
            <div class="form-group">
                <label for="PasswordConfirmed">Repeat your new password</label>
                <input type="password" @keydown="hasErrors = false" :disabled="sendok || loading" required class="form-control" id="PasswordConfirmed" v-model="password_confirmation" aria-describedby="passwordConfirmedHelp" placeholder="Repeat your new password">
            </div>

            <div class="alert alert-danger" v-if="pwned">
                <strong>Error!</strong> The password you have chosen has been leaked and can be found in publicly available password databases. You must choose another password. Read more <a href="https://haveibeenpwned.com/Passwords">here</a>.
            </div>
            <div class="alert alert-danger" v-if="hasErrors">
                <strong>Error!</strong> The password must not be empty, must be at least 6 characters long and must be written the same twice.
            </div>
            <div class="alert alert-danger" v-if="sendok === false">
                <strong>Error!</strong> Please try again later. If you keep getting is issue please contact us <a href="https://k-net.dk/support">here</a>.
            </div>
            <div class="alert alert-success" v-if="sendok">
                <strong>Success!</strong> {{ submitText }} is completed .
            </div>

            <div class="form-group" v-if="sendok !== true">
                <input type=submit :class="(hasErrors || loading) ? 'btn btn-secondary' : 'btn btn-primary'" :disabled="hasErrors || loading" v-model="submitText">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['userinfo','token'],
        data() {
            return {
                password: '',
                password_confirmation: '',
                sendok: null,
                loading: false,
                hasErrors: false,
                pwned: false,
            };
        },
        computed: {
            submitText: {
                get: function() {
                    if (this.loading) {
                        return "Please wait...";
                    } else {
                        return "Reset password";
                    }
                },
                set: function() {
                    return "Reset password";
                },
            }
        },
        methods: {
            sendResetRequest() {
                if (this.password == this.password_confirmation && this.password != '' && this.password.length > 5) {
                    this.loading = true;
                    this.pwned = false;
                    axios.patch('/reset/'+this.token, {
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    })
                    .then(reponse => {
                        this.sendok = reponse.data['sendok'];
                        this.loading = false;
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.data['errors']['password'][0] == "validation.pwned") {
                                this.pwned = true;
                            }
                            else if (Object.keys(error.response.data['errors']).length > 0) {
                                this.hasErrors = true;
                            }
                            else {
                                this.sendok = false;
                            }
                        }
                        else {
                            this.sendok = false;
                        }
                        this.loading = false;
                    });
                } else {
                    this.hasErrors = true;
                }
            },
        },
    }
</script>
