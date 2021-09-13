<template>
	<div>
		<div v-if="!consent">
			<p>
				Use this page to <b>reset your K-Net password</b>.
			</p>

			<p>
				I confirm that I only will use this system to reset my own password. Furthermore that
				<ul>
					<li>Information about my visit will be saved, including (but not limited to) information about my browser and my IP-address</li>
					<li>Abuse will be reported and considered as a serious infringement of the the user declaration. If you have discoered a security problem then you must contact us <a href="https://k-net.dk/support">here</a>.</li>
				</ul>

				<center>
					<button @click="consent = true" type="button" class="btn btn-primary">Accept and continue</button>
				</center>
			</p>
		</div>
		<div v-if="consent">
			<form @submit.prevent="sendResetRequest">
			  <div class="form-group">
			    <label for="Email">E-mail</label>
			    <input type="email" @keydown="hasErrors = false" class="form-control" id="Email" v-model="email" aria-describedby="emailHelp" placeholder="E-mail" required :disabled="sendok || loading">
			    <span id="emailHelp" class="help-block">Enter your email address.</span>
			  </div>

			  <vue-recaptcha
                  ref="recaptcha"
                  @verify="onCaptchaVerified"
                  @expired="onCaptchaExpired"
                  size="invisible"
                  v-bind:sitekey="sitekey">
                </vue-recaptcha>

				<div class="alert alert-danger" v-if="hasErrors">
					<strong>Error!</strong> Please enter a valid email address.
				</div>
				<div class="alert alert-danger" v-if="sendok === false">
					<strong>Error!</strong> We cannot send you an email right now. Please try again later.
				</div>
			    <div class="alert alert-success" v-if="sendok">
			    	<strong>Success!</strong> We have sent you an email with further instructions.
			    </div>

				<center v-if="sendok !== true">
					<input type=submit v-if="loading" class="btn btn-secondary" disabled="" value="Please wait..">
				    <input type=submit v-else :class="(hasErrors) ? 'btn btn-secondary' : 'btn btn-primary'" :disabled="hasErrors" value="Reset password">
				</center>
			</form>
		</div>
	</div>
</template>

<script>
	import VueRecaptcha from 'vue-recaptcha';
	export default {
		props: ['sitekey'],
		components: { VueRecaptcha },
        data() {
	  		return {
	        	consent: false,
	        	loading: false,
	        	email: '',
	        	sendok: null,
	        	hasErrors: false,
	        	reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/
	        };
	    },
	    methods: {
	        sendResetRequest() {
	        	if (!this.loading && this.email != '' && this.consent && this.isEmailValid())
	        	{
	        		this.loading = true;
	        		this.$refs.recaptcha.execute();
	        	}
	        	else {
	        		this.hasErrors = true;
	        	}
	        },
	        onCaptchaVerified(recaptchaToken) {
	        	this.$refs.recaptcha.reset();
	        	axios.post('/resetPassword', {
	            	consent: this.consent,
	            	email: this.email,
	            	'g-recaptcha-response': recaptchaToken,
	            })
	            .then(reponse => {
	            	this.sendok = reponse.data['sendok'];
	            	this.loading = false;
	            })
	            .catch(error => {
	            	this.sendok = false;
	            	this.loading = false;
	            });
	        },
	        onCaptchaExpired() {
	        	this.$refs.recaptcha.reset();
	        },
	        isEmailValid: function() {
				return (this.email == "")? "" : this.reg.test(this.email);
			},
	    }
	}
</script>
