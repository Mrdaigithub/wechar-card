<template>
  <v-app :style="{backgroundColor:'#eee'}">
    <v-toolbar
      class="white--text"
      color="red">
      <v-toolbar-side-icon
        class="white--text"
        @click.stop="drawer = !drawer"/>
      <v-toolbar-title>管理员后台</v-toolbar-title>
      <v-spacer/>
    </v-toolbar>
    <v-navigation-drawer
      v-model="drawer"
      absolute
      temporary>
      <v-list>
        <v-list-tile avatar>
          <v-list-tile-avatar>
            <img src="https://randomuser.me/api/portraits/men/85.jpg">
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>Root</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
      <v-list dense>
        <v-divider/>
        <v-list-tile @click="clickDrawerHandler('/admin')">
          <v-list-tile-action>
            <v-icon>home</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>主页</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/system/config')">
          <v-list-tile-action>
            <v-icon>settings</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>系统设置</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/card')">
          <v-list-tile-action>
            <v-icon>card_giftcard</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>卡券管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/activity')">
          <v-list-tile-action>
            <v-icon>loyalty</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>抽奖活动管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/user')">
          <v-list-tile-action>
            <v-icon>group</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>用户管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/shop')">
          <v-list-tile-action>
            <v-icon>shopping_cart</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>商家管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/shop/employee')">
          <v-list-tile-action>
            <v-icon>people_outline</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>商家人员管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="clickDrawerHandler('/admin/log/winning')">
          <v-list-tile-action>
            <v-icon>book</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>中奖记录管理</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-container>
      <nuxt/>
    </v-container>
  </v-app>
</template>

<script>
  import {mapState, mapMutations, mapActions} from 'vuex';
  
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
      // Todo in dev
      const openid = 'oWqQa6K2egw4ijKVOAC-tffxhxKg';
      if (!this.oneself) {
        const {data} = await this.$axios.$get(`/auth/client/${openid}`);
        this.addToken(data);
        this.addOneself();
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
