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
  export default {
    data: () => ({
      bottomNav: '',
    }),
    async mounted() {
      await this.$axios.$get("/user");
      this.bottomNav = this.$route.path.split("/")[this.$route.path.split("/").length - 1];
      if (this.$route.query.openid) {
        sessionStorage.openid = this.$route.query.openid;
      } else if (!this.$route.query.openid && sessionStorage.openid) {
      } else {
      
      }
    },
    methods: {
      changePage(url) {
        this.$router.push(url)
      }
    }
  }
</script>
<style scoped lang="stylus">
  .bottom_nav {
    position: fixed;
    bottom: 0;
    left: 0;
    background-color: #fff;
  }
</style>
