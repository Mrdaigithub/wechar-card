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
          v-if="loginQrCodeBase64"
          :src="`data:image/png;base64,${loginQrCodeBase64}`"
          alt="">
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import {mapMutations, mapActions} from 'vuex';
  import rules from '~/utils/rules';
  
  export default {
    name: 'AdminLogin',
    layout: 'empty',
    data: () => ({
      rules: rules,
      gradient: 'to top, #F44336 ,#fff',
      valid: true,
      hidePassword: true,
      loginQrCodeBase64: '',
    }),
    async mounted() {
      const {data} = await this.$axios.$get(`/qrcode/admin/login`);
      this.loginQrCodeBase64 = data;
      
      window.Echo.channel('publicChannel').listen('MessageEvent', async (e) => {
        if (e.message && JSON.parse(e.message).signal === 'allowLogin' && JSON.parse(e.message).openid) {
          const {data} = await this.$axios.$get(`/auth/client/${JSON.parse(e.message).openid}`);
          this.addToken(data);
          this.addOneself();
          this.$router.replace('/admin');
        }
      });
    },
    methods: {
      ...mapMutations({
        addToken: 'oneself/addToken',
      }),
      ...mapActions({
        addOneself: 'oneself/addOneself',
        addSystemConfig: 'systemConfig/addSystemConfig',
      }),
    },
  };
</script>

<style scoped>

</style>
