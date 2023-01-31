<template>
    <div class="page-content">
        <div class="back-btn d-flex align-top">
        
                <v-btn @click="back" color="primary">Back</v-btn>
           
        </div>

        <div style="justify-content: center; display: flex; flex-grow: 1; ">
            <div>
                <v-snackbar
                        :timeout="2000"
                        :value="alert.success"
                        absolute
                        right
                        top
                        color="success"
                        >
                    <strong>{{alert.message}}</strong> 
                    </v-snackbar>

                <v-card id="loginSection" class="contactblock px-5 py-3 mt-4">
                    <h3>Create point of contact</h3>
                    <v-card-text>
                        <v-form v-model="loginForm" ref="loginForm">
                            <v-text-field
                                v-model="contact.name"
                                :disabled="loading"
                                :error-messages="logInError"
                                :rules="[isRequired]"
                                label="Name"
                                validate-on-blur
                                @input="logInError = null"
                                @keydown.enter="logIn"/>

                            <v-text-field
                                v-model="contact.surname"
                                :disabled="loading"
                                :error-messages="logInError"
                                :rules="[isRequired]"
                                label="Surname"
                                validate-on-blur
                                @input="logInError = null"
                                @keydown.enter="logIn"/>

                            <v-text-field
                                v-model="contact.email"
                                :disabled="loading"
                                :error-messages="logInError"
                                :rules="[isRequired]"
                                label="Email"
                                validate-on-blur
                                @input="logInError = null"
                                @keydown.enter="logIn"/>
                            <v-text-field
                                v-model="contact.cellphone"
                                :disabled="loading"
                                :error-messages="logInError"
                                :rules="[isRequired]"
                                label="Cellphone"
                                validate-on-blur
                                @input="logInError = null"
                                @keydown.enter="logIn"/>

                                <v-select
                                v-model="contact.gender"
                                :items="gender"
                                label="Gender"
                                />

                                <url-autocomplete
                                label="Stores"
                                url="api_fetch_all_stores"
                                v-model="contact.store"
                                :rules="[isRequired]"
                                />
                        </v-form>
                    </v-card-text>
                    <div class="d-flex justify-end">
                        <v-btn :loading="loading" color="primary" text @click="create">Create</v-btn>
                    </div>
                </v-card>
            </div>
        </div>
    </div>
</template>

<script>
import {validationRulesMixin} from "~/mixins/validation-rules-mixin";
import httpClient from "~/classes/httpClient";
import UrlAutocomplete from "~/components/general/UrlAutocomplete";


export default {
    name: "ContactIndex",
    components: {UrlAutocomplete},
    mixins: [validationRulesMixin],
    data() {
        return {
            contact: {
                name: '',
                surname: '',
                email: '',
                cellphone: '',
                gender: ''

            },
            loginForm: true,
            loading: false,
            logInError: null,
            alert:{success: null,
                   message: ''
            },
            gender: ['Male', 'Female', 'Other'],
             
        }
    },
    methods: {
        async create() {
            this.loading = true;
            if (!this.$refs.loginForm.validate()) {
                this.loading = false;
                return;
            }
            try {
                // The way Symfony is handling the internals of the api/login route we can't use FormData
                await httpClient.axiosClient.post('api/contacts/create',this.contact).then(()=>{ console.log('yes')})
                this.alert.success = true;
                this.alert.message = 'Contact created';
             
            } catch (error) {
                this.logInError = error.response.data.error;
            } finally {
                this.loading = false;
            }
        },
        back(){
            //this.$router.push('dashboard');
            this.$router.go(-1);
        }
    },
}
</script>

<style lang="scss" scoped>
.page-content {
    height: 100%;
    display: flex;
    align-items: center;
}



.back-btn {
    height: 90%;
    padding-left: 10%;
    
}

.contactblock {
    width: 500px;
}

</style>
