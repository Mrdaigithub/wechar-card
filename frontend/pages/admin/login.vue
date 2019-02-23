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
        <h2>管理员登录</h2>
        <v-content style="height: 300px">
          <v-img
            v-if="loginQrCodeBase64"
            :src="`data:image/png;base64,${loginQrCodeBase64}`"
            style="margin: auto;"
            width="300"
            alt=""/>
        </v-content>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import {mapMutations, mapActions} from 'vuex';
import {Loading, Message} from 'element-ui';

export default {
  name: 'AdminLogin',
  layout: 'empty',
  data: () => ({
    loginQrCodeBase64: '',
  }),
  async mounted() {
    Loading.service({fullscreen: true});
    const {data} = await this.$axios.$get(`/qrcode/admin/login`);
    this.loginQrCodeBase64 = data;
    Loading.service({fullscreen: true}).close();

    window.Echo.channel('adminChannel').listen('AdminLoginEvent', async (e) => {
      if (e.message &&
        JSON.parse(e.message).signal === 'allowAdminLogin' &&
        JSON.parse(e.message).openid &&
        this.$route.name === 'admin-login') {
        const {data} = await this.$axios.$get(`/auth/client/${JSON.parse(e.message).openid}`);
        sessionStorage.setItem('token', data.token);
        sessionStorage.setItem('ttl', data.ttl);
        Message.success('登录成功');
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
