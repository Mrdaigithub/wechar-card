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
        @click="changePage(`/user/lottery?shopid=${$route.query.shopid}`)">
        <span>抽奖界面</span>
        <v-icon>card_giftcard</v-icon>
      </v-btn>
      <v-btn
        color="red"
        flat
        value="signIn"
        @click="changePage(`/user/signIn?shopid=${$route.query.shopid}`)">
        <span>签到中心</span>
        <v-icon>create</v-icon>
      </v-btn>
      <v-btn
        color="red"
        flat
        value="person"
        @click="changePage(`/user/person?shopid=${$route.query.shopid}`)">
        <span>个人中心</span>
        <v-icon>person</v-icon>
      </v-btn>
    </v-bottom-nav>
  </v-app>
</template>

<script>
import {mapState, mapMutations, mapActions} from 'vuex';
import {Loading, Message} from 'element-ui';

export default {
  data: () => ({
    bottomNav: 'lottery',
  }),
  computed: {
    ...mapState({
      bottomTab: state => state.assist.bottomTab ? state.assist.bottomTab : 'lottery',
    }),
  },
  watch: {
    bottomTab(val) {
      this.bottomNav = val;
    },
  },
  async created() {
    Loading.service({fullscreen: true});
    const openid = this.$route.query.openid;
    const shopId = this.$route.query.shopid;
    const location = this.$route.query.location;
    const address = this.$route.query.address;

    if (!shopId || shopId === '') {
      return Message.error('缺失参数,请退回公众号重新进入');
    }
    if ((openid || openid !== '') && !sessionStorage.token) {
      const {data} = await this.$axios.$get(`/auth/client/${openid}`);
      sessionStorage.setItem('token', data.token);
      sessionStorage.setItem('ttl', data.ttl);
    }
    this.$router.replace(`${this.$route.path}?shopid=${shopId}`); // 清除url上的openid
    this.addLocation(location);
    this.addAddress(address);
    this.addOneself();
    this.addSystemConfig();
    this.addCard({arg: shopId});
    this.addShopActivity({arg: shopId, cb: () => Loading.service({fullscreen: true}).close()});
    this.bottomNav = this.$route.path.split('/')[this.$route.path.split('/').length - 1];
  },
  methods: {
    changePage(url) {
      this.$router.push(url);
    },
    ...mapMutations({
      addLocation: 'oneself/addLocation',
      addAddress: 'oneself/addAddress',
    }),
    ...mapActions({
      addOneself: 'oneself/addOneself',
      addSystemConfig: 'systemConfig/addSystemConfig',
      addCard: 'card/addCardModelListByShopId',
      addShopActivity: 'shop/addShopActivity',
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
