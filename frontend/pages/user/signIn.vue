<template>
  <div>
    <v-card>
      <v-toolbar color="red">
        <v-toolbar-title class="white--text">每日签到</v-toolbar-title>
      </v-toolbar>
    </v-card>
    <v-layout
      row
      wrap>
      <v-flex
        xs12
        text-xs-center>
        <div :style="{paddingTop:'30px'}">
          <v-btn
            class="white--text"
            color="red"
            @click="signIn">
            <v-icon
              left
              dark>border_color
            </v-icon>
            签到
          </v-btn>
        </div>
      </v-flex>
      <v-flex
        :style="{marginTop:'50px'}"
        xs12
        text-xs-center>
        <v-date-picker
          v-model="signInLogList"
          full-width
          locale="zh-cn"
          color="red"
          readonly
          no-title
          multiple/>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
  import {mapState, mapMutations, mapActions} from 'vuex';
  import {Message} from 'element-ui';
  
  export default {
    name: 'SignIn',
    layout: 'user',
    data: () => ({}),
    computed: {
      ...mapState({
        signInLogList: state => state.signIn.signInLogList ? state.signIn.signInLogList : [],
        oneself: state => state.oneself.oneself ? state.oneself.oneself : {},
      }),
    },
    mounted() {
      this.asyncAddSignInLog(0);
    },
    methods: {
      ...mapMutations({
        addSignInLog: 'signIn/add',
        addOneself: 'oneself/add',
      }),
      ...mapActions({
        asyncAddSignInLog: 'signIn/add',
      }),
      async signIn() {
        const {data, message} = await this.$axios.$put(`/signin/user/0`);
        Message.success(message);
        this.addSignInLog(data);
        const oneself = JSON.parse(JSON.stringify(this.oneself));
        oneself['lottery_num']++;
        this.addOneself(oneself);
      },
    },
  };
</script>

<style lang="stylus" scoped>
</style>
