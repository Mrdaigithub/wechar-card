<template>
  <v-app :style="{backgroundColor:'#eee'}">
    <v-toolbar
      class="white--text"
      color="red">
      <v-toolbar-side-icon
        class="white--text"
        @click.stop="drawer = !drawer"/>
      <v-toolbar-title>商家后台</v-toolbar-title>
      <v-spacer/>
    </v-toolbar>
    <v-navigation-drawer
      v-model="drawer"
      absolute
      temporary>
      <v-list v-if="oneself">
        <v-list-tile avatar>
          <v-list-tile-avatar>
            <v-img
              :src="oneself['head_img_url']"
              :lazy-src="oneself['head_img_url']"/>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{ oneself['username'] }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
      <v-list dense>
        <v-divider/>
        <v-list-tile @click="clickDrawerHandler('/shop')">
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>主页</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/shop/writeoff')">
          <v-list-tile-action>
            <v-icon>attach_money</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>核销管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <!--<v-list-tile @click="clickDrawerHandler('/shop/employeemanagement')">-->
        <!--<v-list-tile-action>-->
        <!--<v-icon>group</v-icon>-->
        <!--</v-list-tile-action>-->
        <!--<v-list-tile-content>-->
        <!--<v-list-tile-title>店员管理</v-list-tile-title>-->
        <!--</v-list-tile-content>-->
        <!--</v-list-tile>-->
      </v-list>
    </v-navigation-drawer>
    <v-container>
      <nuxt/>
    </v-container>
  </v-app>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import {Loading, Message} from 'element-ui';

export default {
  data: () => ({
    drawer: false,
  }),
  computed: {
    ...mapState({
      oneself: state => state.oneself.oneself ? state.oneself.oneself : null,
      shop: state => state.shop.shop ? state.shop.shop : null,
    }),
  },
  async mounted() {
    if (!this.oneself) {
      Loading.service({fullscreen: true});
      const openid = this.$route.query.openid;
      // Todo in dev
      if ((!openid || openid === '') && !sessionStorage.token) {
        // window.location.href = 'https://mrdaisite.club/wechat/authorize?url=https%3A%2F%2Fmrdaisite.club%2Fwechat%2Fgrant%2Fshop';
      }
      const {data} = await this.$axios.$get(`/auth/client/${openid}`);
      sessionStorage.token = data;
      this.$router.replace(`${this.$route.path}`); // 清除url上的openid
      this.addOneself();
      this.getShopByBoss(() => Loading.service({fullscreen: true}).close());
    }
  },
  methods: {
    ...mapActions({
      addOneself: 'oneself/addOneself',
      getShopByBoss: 'shop/getShopByBoss',
    }),
    clickDrawerHandler(url) {
      this.changePage(url);
      this.drawer = false;
    },
    changePage(url) {
      this.$router.push(url);
    },
  },
};
</script>

<style scoped lang="stylus">
</style>
