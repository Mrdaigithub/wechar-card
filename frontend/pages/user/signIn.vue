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
          v-model="dates"
          full-width
          locale="zh-cn"
          color="red"
          readonly
          no-title
          multiple/>
      </v-flex>
    </v-layout>
    <v-dialog
      v-model="msgDialog"
      max-width="300px">
      <v-card>
        <v-card-title>
          <span>签到成功{{ lotteryAdd?', 抽奖次数+1':null }}</span>
          <v-spacer/>
        </v-card-title>
        <v-card-actions>
          <v-btn
            flat
            class="red--text"
            @click="msgDialog=false">我知道了
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import {mapState, mapMutations, mapActions} from 'vuex';

export default {
  name: 'SignIn',
  layout: 'user',
  data: () => ({
    msgDialog: false,
    lotteryAdd: true,
  }),
  computed: {
    ...mapState({
      dates: state => state.signIn.signInLogList ? state.signIn.signInLogList : [],
    }),
  },
  mounted() {
    this.asyncAddSignInLog(0);
  },
  methods: {
    ...mapMutations({
      addSignInLog: 'signIn/add',
    }),
    ...mapActions({
      asyncAddSignInLog: 'signIn/add',
    }),
    async signIn() {
      const {data} = await this.$axios.$put(`/signin/user/0`);
      this.msgDialog = true;
      this.addSignInLog(data);
    },
  },
};
</script>

<style lang="stylus" scoped>
  .v-btn--floating .v-btn__content
    color: #fff !important
</style>
