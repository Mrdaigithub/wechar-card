<template>
  <div>
    <v-jumbotron
      :gradient="gradient"
      dark
      style="height: 100vh">
      <v-container fill-height>
        <v-layout
          align-center
          justify-center>
          <v-flex
            text-xs-center
            lg4
            md6
            sm8
            xs12>
            <v-form
              ref="loginForm"
              v-model="valid"
              lazy-validation>
              <v-text-field
                :rules="rules.usernameRules"
                label="用户名"
                prepend-icon="account_circle"/>
              <v-text-field
                :type="hidePassword ? 'password' : 'text'"
                :append-icon="hidePassword ? 'visibility' : 'visibility_off'"
                :rules="rules.passwordRules"
                label="密码"
                required
                prepend-icon="lock"
                @click:append="hidePassword = !hidePassword"/>
              <v-btn
                :disabled="!valid"
                :class="valid ? 'white--text' : 'black--text'"
                style="width: 100%"
                @click="submit">登录
              </v-btn>
            </v-form>
          </v-flex>
        </v-layout>
      </v-container>
    </v-jumbotron>
  </div>
</template>

<script>
  import rules from '~/utils/rules';
  
  export default {
    name: 'AdminLogin',
    layout: 'empty',
    data: () => ({
      rules: rules,
      gradient: 'to top, #F44336 ,#fff',
      valid: true,
      hidePassword: true,
    }),
    methods: {
      submit() {
        if (this.$refs.loginForm.validate()) {
          console.log(1);
        }
      },
    },
  };
</script>

<style scoped>

</style>
