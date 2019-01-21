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
        <v-list-tile @click="clickDrawerHandler('/shop/employeemanagement')">
          <v-list-tile-action>
            <v-icon>group</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>店员管理</v-list-tile-title>
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
  export default {
    data: () => ({
      drawer: false,
    }),
    mounted() {
    },
    methods: {
      clickDrawerHandler(url) {
        this.changePage(url);
        this.drawer = false;
      },
      changePage(url) {
        this.$router.push(url)
      }
    }
  }
</script>

<style scoped lang="stylus">
</style>
