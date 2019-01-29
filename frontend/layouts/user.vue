<template>
  <v-app :style="{backgroundColor:'#eee'}">
    <nuxt :style="{paddingBottom:'56px'}"/>
    <v-bottom-nav
      :active.sync="bottomNav"
      :value="true"
      class="bottom_nav"
      absolute
      color="white">
      <v-btn
        color="red"
        flat
        value="lottery"
        @click="changePage('/user/lottery')">
        <span>抽奖界面</span>
        <v-icon>card_giftcard</v-icon>
      </v-btn>
      <v-btn
        color="red"
        flat
        value="signIn"
        @click="changePage('/user/signIn')">
        <span>签到中心</span>
        <v-icon>create</v-icon>
      </v-btn>
      <v-btn
        color="red"
        flat
        value="person"
        @click="changePage('/user/person')">
        <span>个人中心</span>
        <v-icon>person</v-icon>
      </v-btn>
    </v-bottom-nav>
  </v-app>
</template>

<script>
import {mapMutations, mapActions} from 'vuex';

export default {
  data: () => ({
    bottomNav: '',
  }),
  async created() {
    const openid = this.$route.query.openid;
    // Todo 清除url上的openid
    // this.$router.replace(`${this.$route.path}?shopid=${this.$route.query.shopid}`); // 清除url上的openid
    const {data} = await this.$axios.$get(`/auth/client/${openid}`);
    this.addToken(data);
    this.addOneself();
    this.addSystemConfig();
    this.addCard(this.$route.query.shopid);
    this.addActivity(this.$route.query.shopid);
    this.bottomNav = this.$route.path.split('/')[this.$route.path.split('/').length - 1];
  },
  methods: {
    changePage(url) {
      this.$router.push(url);
    },
    ...mapMutations({
      addToken: 'token/add',
    }),
    ...mapActions({
      addOneself: 'oneself/add',
      addSystemConfig: 'systemConfig/add',
      addCard: 'card/add',
      addActivity: 'activity/add',
    }),
  },
};
</script>
<style scoped lang="stylus">
  .bottom_nav {
    position: fixed;
    bottom: 0;
    left: 0;
    background-color: #fff;
  }
</style>
