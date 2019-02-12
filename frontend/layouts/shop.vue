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
import {mapState, mapMutations, mapActions} from 'vuex';
import {Loading} from 'element-ui';

export default {
  data: () => ({
    drawer: false,
  }),
  computed: {
    ...mapState({
      oneself: state => state.oneself.oneself ? state.oneself.oneself : null,
    }),
  },
  async mounted() {
    if (!this.oneself) {
      Loading.service({fullscreen: true});
      // Todo in dev
      const {data} = await this.$axios.$get(`/auth/client/${'ocYxcuBt0mRugKZ7tGAHPnUaOW7Y'}`);
      // const {data} = await this.$axios.$get(`/auth/client/${this.oneself.openid}`);
      this.addToken(data);
      this.addOneself(() => Loading.service({fullscreen: true}).close());
    }
  },
  methods: {
    ...mapMutations({
      addToken: 'oneself/addToken',
    }),
    ...mapActions({
      addOneself: 'oneself/addOneself',
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
