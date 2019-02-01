<template>
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
        <h2>登录</h2>
        <img
          src="data:image/png;base64,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"
          alt="">
      </v-flex>
    </v-layout>
  </v-container>
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
  mounted() {
    window.Echo.channel('news').listen('News', (e) => {
      console.log(e.message);
    });
  },
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
