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
          color="deep-orange lighten-3"
          readonly
          disabled
          no-title
          multiple/>
      </v-flex>
    </v-layout>
  </div>
</template>

<script>
import {mapState, mapMutations, mapActions} from 'vuex';
import {Message, Loading} from 'element-ui';

export default {
  name: 'SignIn',
  layout: 'user',
  data: () => ({}),
  computed: {
    ...mapState({
      signInLogList: state => state.oneself.signInLogList ? state.oneself.signInLogList : [],
      oneself: state => state.oneself.oneself ? state.oneself.oneself : {},
    }),
  },
  mounted() {
    Loading.service({fullscreen: true});
    this.asyncAddSignInLog({arg: 0, cb: () => Loading.service({fullscreen: true}).close()});
  },
  methods: {
    ...mapMutations({
      addSignInLog: 'oneself/addSignInLogList',
      addOneself: 'oneself/addOneself',
    }),
    ...mapActions({
      asyncAddSignInLog: 'oneself/addSignInLogList',
      asyncAddOneself: 'oneself/addOneself',
    }),
    async signIn() {
      Loading.service({fullscreen: true});
      const {data, message} = await this.$axios.$put(`/signin/user/0`);
      this.addSignInLog(data);
      this.asyncAddOneself(Loading.service({fullscreen: true}).close());
      Message.success(message);
    },
  },
};
</script>

<style lang="stylus" scoped>
</style>
