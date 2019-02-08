<template>
  <div>
    <v-toolbar
      class="white--text"
      color="red">
      <v-toolbar-title>我的奖品</v-toolbar-title>
      <v-spacer/>
      <v-btn
        flat
        class="white--text"
        @click="viewState=!viewState">
        {{ viewState ? '失效券' : '有效券' }}
      </v-btn>
    </v-toolbar>
    <v-container
      fluid
      grid-list-lg>
      <v-layout
        row
        wrap>
        <v-flex
          v-for="(item, index) in oneselfCardList"
          v-if="!!item.state === viewState"
          :key="index"
          xs12>
          <v-card
            :color="item.state ? 'white' : 'grey lighten-1'"
            @click="openFormDialog(item)">
            <v-layout row>
              <v-flex xs7>
                <v-card-title primary-title>
                  <div>
                    <div class="headline">{{ item.card_name }}</div>
                    <div>{{ item.remarks }}</div>
                  </div>
                </v-card-title>
              </v-flex>
              <v-flex xs5>
                <v-img
                  :src="item.card_thumbnail"
                  height="125px"
                  contain
                />
              </v-flex>
            </v-layout>
            <v-divider light/>
            <v-card-actions class="pa-3">
              {{ !!item.state && item['end_time_0'] ? `${item['end_time_0']}到期` : null }}
              <CountDownTimer
                v-if="item.state && item['end_time_1']"
                :id="item.id"
                :end-time="getCountDownTimerString(new Date(new Date(item['created_at']).getTime() + item['end_time_1'] * 1000))"/>
              {{ !item.state ? item.endTime : null }}
              <v-spacer/>
              {{ item.state ? '有效' : '已失效' }}
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-dialog
      v-model="formDialog"
      persistent
      max-width="300px">
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation>
        <v-card>
          <v-card-title>请输入你的信息以领取卡券</v-card-title>
          <v-card-text>
            <v-text-field
              :rules="rules.nameRules"
              v-model="realName"
              :counter="10"
              label="姓名"
              append-icon="account_circle"/>
            <v-text-field
              :rules="rules.phoneRules"
              v-model="phone"
              label="电话号码"
              required
              append-icon="phone_iphone"/>
          </v-card-text>
          <v-card-actions>
            <v-btn
              color="primary"
              flat
              @click="closeFormDialog">关闭
            </v-btn>
            <v-btn
              :disabled="!valid"
              flat
              class="red--text"
              @click="submit">赢取奖励
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog
      v-model="qrCodeDialog"
      persistent
      max-width="300px">
      <v-card>
        <v-card-title>
          <span>请将二维码交予老板核销</span>
          <v-spacer/>
        </v-card-title>
        <v-img
          v-if="writeOffQrCodeBase64"
          :src="`data:image/png;base64,${writeOffQrCodeBase64}`"/>
        <v-card-actions>
          <v-btn
            color="primary"
            flat
            @click="closeQrCodeDialog">完成
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import qs from 'qs';
import rules from '~/utils/rules';
import CountDownTimer from '~/components/CountDownTimer';

export default {
  name: 'Person',
  layout: 'user',
  components: {
    CountDownTimer,
  },
  data: () => ({
    formDialog: false,
    qrCodeDialog: false,
    writeOffQrCodeBase64: '',
    rules: rules,
    valid: true,
    realName: '',
    phone: '',
    viewState: true, // 当前查看为有效券或失效券
  }),
  computed: {
    ...mapState({
      oneselfCardList: state => state.oneself.cardList ? state.oneself.cardList : [], // 剩余抽奖次数
      oneself: state => state.oneself.oneself ? state.oneself.oneself : {}, // 当前用户
      lotteryNeedsToFillInTheInformation: state => state.systemConfig.systemConfig ?
        /^true$/i.test(state.systemConfig.systemConfig.filter(
          item => item['config_name'] === 'lotteryNeedsToFillInTheInformation')[0]['config_value'])
        : true,// 抽奖填写信息配置
    }),
  },
  mounted() {
    this.addOneself(this.$route.query.shopid);
  },
  methods: {
    ...mapActions({
      addOneself: 'oneself/addCardList',
    }),
    openFormDialog(item) {
      if (!item.state) return false;
      if (!this.lotteryNeedsToFillInTheInformation || (this.oneself['real_name'] && this.oneself['phone'])) {
        this.getQrCode(`/qrcode/writeoff`);
        return this.qrCodeDialog = true;
      }
      this.formDialog = true;
    },
    async submit() {
      if (this.$refs.form.validate()) {
        this.getQrCode(`/qrcode/writeoff?real_name=${qs.stringify(this.realName)}&phone=${this.phone}`);
        this.qrCodeDialog = !this.qrCodeDialog;
      }
    },
    closeFormDialog() {
      this.formDialog = false;
      this.$refs.form.reset();
    },
    async getQrCode(url) {
      const {data} = await this.$axios.$get(url);
      this.writeOffQrCodeBase64 = data;
    },
    closeQrCodeDialog() {
      this.qrCodeDialog = false;
      this.closeFormDialog();
    },
    getCountDownTimerString(date) {
      return `${date.getFullYear()}-${date.getMonth() +
      1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    },
  },
};
</script>
