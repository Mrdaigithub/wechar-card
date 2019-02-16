<template>
  <div>
    <v-toolbar
      fixed
      class="white--text"
      color="red">
      <v-toolbar-title>优惠券</v-toolbar-title>
      <v-spacer/>
      <!--<v-btn-->
      <!--flat-->
      <!--class="white&#45;&#45;text"-->
      <!--@click="viewState=!viewState">-->
      <!--{{ viewState ? '失效券' : '有效券' }}-->
      <!--</v-btn>-->
      <v-tabs
        slot="extension"
        v-model="tab"
        color="red"
        grow>
        <v-tabs-slider color="yellow"/>
        <v-tab
          v-for="item in tabNameItems"
          :key="item"
          class="white--text">
          {{ item }}
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <v-tabs-items
      v-model="tab"
      class="pt-200">
      <v-tab-item
        v-for="item in tabNameItems"
        :key="item">
        <v-container
          fluid
          grid-list-lg
          class="pa-2">
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
                  <v-flex
                    xs5
                    class="pa-1">
                    <v-img
                      :src="item.card_thumbnail"
                      height="125px"
                      contain
                    />
                  </v-flex>
                  <v-flex
                    xs7
                    class="pa-1">
                    <v-card-title class="pa-0 pt-2 card-title orange--text font-weight-black">
                      <span class="pa-0 pr-1">
                        <v-icon color="orange">card_giftcard</v-icon>
                      </span>
                      {{ item.card_name }}
                    </v-card-title>
                    <v-card-text class="pa-0 pt-2">
                      <div class="card-description font-weight-black">{{ item.remarks }}</div>
                      <div class="grey--text pt-1">活动商家: {{ shopName }}</div>
                      <div class="grey--text">
                        活动时间:
                        {{ !!item.state && item['end_time_0'] ? `${new Date(item['end_time_0']).getFullYear()}-
                        ${new Date(item['end_time_0']).getMonth()+1}-${new Date(item['end_time_0']).getDate()}到期` :
                        null }}
                        <CountDownTimer
                          v-if="item.state && item['end_time_1']"
                          :id="item.id"
                          :end-time="getCountDownTimerString(new Date(new Date(item['created_at']).getTime() + item['end_time_1'] * 1000))"/>
                        {{ !item.state ? item.endTime : null }}
                      </div>
                    </v-card-text>
                  </v-flex>
                </v-layout>
                <v-divider light/>
                <v-card-actions class="pa-3">
                  <v-btn
                    round
                    depressed
                    small
                    color="deep-orange"
                    class="white--text">点击使用
                  </v-btn>
                  <v-spacer/>
                  {{ item.state ? '待使用' : '已失效' }}
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-tab-item>
    </v-tabs-items>
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
        <v-content style="height: 300px">
          <v-img
            v-if="writeOffQrCodeBase64"
            :src="`data:image/png;base64,${writeOffQrCodeBase64}`"/>
        </v-content>
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
import {Message, Loading} from 'element-ui';
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
    rules: rules,
    valid: true,
    viewState: true, // 当前查看为有效券或失效券
    writeOffQrCodeBase64: '',
    realName: '',
    phone: '',
    cardId: null, // 抽到的卡券id

    tab: null,
    tabNameItems: [
      '待使用', '已使用', '已过期',
    ],
    text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
  }),
  computed: {
    ...mapState({
      oneselfCardList: state => state.oneself.cardList ? state.oneself.cardList : [], // 剩余抽奖次数
      oneself: state => state.oneself.oneself ? state.oneself.oneself : {}, // 当前用户
      lotteryNeedsToFillInTheInformation: state => state.systemConfig.systemConfig ?
        /^true$/i.test(state.systemConfig.systemConfig.filter(
          item => item['config_name'] === 'lotteryNeedsToFillInTheInformation')[0]['config_value'])
        : true,// 抽奖填写信息配置
      shopName: state => state.shop.shop ? state.shop.shop['shop_name'] : '暂无', // 当前用户
    }),
  },
  watch: {
    qrCodeDialog(val) {
      if (val) {
        this.getQrCode(`/qrcode/writeoff/${this.cardId}`);
      } else {
        this.realName = '';
        this.phone = '';
        this.cardId = null;
      }
    },
  },
  mounted() {
    Loading.service({fullscreen: true});
    this.getShopById({arg: this.$route.query.shopid});
    this.addCardList({arg: this.$route.query.shopid, cb: Loading.service({fullscreen: true}).close()});
    window.Echo.channel('publicChannel').listen('MessageEvent', async (e) => {
      if (e.message && this.oneself &&
        JSON.parse(e.message).signal === 'writeOff' &&
        this.oneself.id === JSON.parse(e.message)['user_id']) {
        Loading.service({fullscreen: true});
        this.addCardList({arg: this.$route.query.shopid, cb: () => Loading.service({fullscreen: true}).close()});
        Message.success('核销成功');
        this.formDialog = false;
        this.qrCodeDialog = false;
      }
    });
  },
  methods: {
    ...mapActions({
      addCardList: 'oneself/addCardList',
      addOneself: 'oneself/addOneself',
      updatePlainUserByOneself: 'oneself/updatePlainUserByOneself',
      getShopById: 'shop/getShopById',
    }),
    openFormDialog(item) {
      if (!item.state) return false;
      this.cardId = item.id;
      if (!this.lotteryNeedsToFillInTheInformation || (this.oneself['real_name'] && this.oneself['phone'])) {
        return this.qrCodeDialog = true;
      }
      this.formDialog = true;
    },
    async submit() {
      if (this.$refs.form.validate()) {
        this.updatePlainUserByOneself({
          arg: {
            real_name: this.realName,
            phone: this.phone,
          },
          cb: () => this.addOneself(),
        });
        this.qrCodeDialog = !this.qrCodeDialog;
      }
    },
    closeFormDialog() {
      this.formDialog = false;
      this.$refs.form.reset();
    },
    async getQrCode(url) {
      Loading.service({fullscreen: true});
      const {data} = await this.$axios.$get(url);
      this.writeOffQrCodeBase64 = data;
      Loading.service({fullscreen: true}).close();
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

<style scoped lang="stylus">
  .card-title
    font-size 18px

  .card-description
    font-size 18px

  .pt-200
    padding-top 120px
</style>
