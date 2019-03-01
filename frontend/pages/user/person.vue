<template>
  <div>
    <v-toolbar
      fixed
      flat
      color="white">
      <v-toolbar-title>优惠券</v-toolbar-title>
      <v-spacer/>
      <v-tabs
        slot="extension"
        v-model="tab"
        grow>
        <v-tabs-slider color="#ff823e"/>
        <v-tab
          v-for="item in tabNameItems"
          :key="item">
          {{ item }}
        </v-tab>
      </v-tabs>
    </v-toolbar>
    <v-tabs-items
      v-model="tab"
      class="pt-200">
      <v-tab-item
        v-for="k in tabNameItems"
        :key="k">
        <v-container
          fluid
          grid-list-lg
          class="pa-2">
          <v-layout
            row
            wrap>
            <v-flex
              v-for="(item, index) in oneselfCardList"
              v-show="(tab === 0 && item.view === 'unused') || (tab === 1 && item.view === 'used') || (tab === 2 && item.view === 'expired')"
              :key="index"
              xs12>
              <v-card
                elevation="0"
                @click="openFormDialog(item)">
                <v-layout row>
                  <v-flex
                    xs5
                    class="pa-1">
                    <v-img
                      :src="item.card_thumbnail"
                      height="125px"
                      contain/>
                  </v-flex>
                  <v-flex
                    xs7
                    class="pa-1">
                    <v-card-title class="pa-0 pt-2 card-title">
                      <span
                        :class="`font-weight-black ${item.state ? null : 'grey--text'}`"
                        :style="{background: 'url(' + prizeIcon + ') no-repeat left center', backgroundSize: 'contain'}">
                        {{ item.card_name }}
                      </span>
                      <v-spacer/>
                      <v-btn
                        :disabled="!item.state"
                        :class="`${ item.state ? 'white--text btn' : null}`"
                        round
                        depressed
                        small>点击使用
                      </v-btn>
                    </v-card-title>
                    <v-card-text class="pa-0">
                      <div
                        :class="`card-description ${item.state ? null : 'grey--text'}`">{{ item.remarks }}
                      </div>
                      <div
                        :class="`card-shop pt-1 ${item.state ? null : 'grey--text'}`">活动商家: {{ shopName }}
                      </div>
                      <div
                        v-show="!!item.state"
                        class="`card_time ${item.state ? null : 'grey--text'`">
                        有效期:
                        {{ !!item.state && item['end_time_0'] ? `${new Date(item['end_time_0']).getFullYear()}-
                        ${new Date(item['end_time_0']).getMonth()+1}-${new Date(item['end_time_0']).getDate()}到期` :
                        null }}
                        <CountDownTimer
                          v-if="item.state && item['end_time_1']"
                          :id="item.id"
                          :end-time="getCountDownTimerString(new Date(new Date(item['created_at']).getTime() + item['end_time_1'] * 1000))"/>
                      </div>
                      <div
                        v-show="!item.state"
                        class="grey--text">
                        活动时间:
                        {{ !item.state && item['end_time_0'] ? getEndTimeString(item['end_time_0']) : null }}
                        {{ !item.state && item['end_time_1'] ? getEndTimeString(getCountDownTimerString(new Date(new
                        Date(item['created_at']).getTime() + item['end_time_1'] * 1000))) : null }}
                      </div>
                    </v-card-text>
                  </v-flex>
                </v-layout>
                <v-divider light/>
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
          <v-card-title>请输入你的信息领取奖励</v-card-title>
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
              @click="submit">领取奖励
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
    </v-dialog>
    <v-dialog
      v-model="qrCodeDialog"
      max-width="400px">
      <v-card
        v-if="currentCard"
        class="qrcode-dialog-body">
        <div style="background-color:#fff;border-radius: 10px">
          <h3 class="text-xs-center ma-0">
            <span
              :style="{background: 'url(' + prizeIcon + ') no-repeat left center', backgroundSize: 'contain'}">
              {{ currentCard['card_name'] }}
            </span>
          </h3>
          <p class="text-xs-center ma-0 sub-title">{{ currentCard.remarks }}</p>
          <v-img
            v-if="writeOffQrCodeBase64"
            :src="`data:image/png;base64,${writeOffQrCodeBase64}`"/>
          <p class="text-xs-center ma-0 des">请让商家扫码核销优惠卷</p>
          <p
            class="text-xs-center ma-0 time">
            {{ currentCard['end_time_0']
            ? `有效期:${currentCard['end_time_0']}` : null }}
            <CountDownTimer
              v-if="currentCard['end_time_1']"
              :id="cardId"
              :end-time="getCountDownTimerString(new Date(new Date(currentCard['created_at']).getTime() + currentCard['end_time_1'] * 1000))"/>
          </p>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import {Message, Loading} from 'element-ui';
import rules from '~/utils/rules';
import CountDownTimer from '~/components/CountDownTimer';
import prizeIcon from '~/assets/images/prize-icon.png';

export default {
  name: 'Person',
  layout: 'user',
  components: {
    CountDownTimer,
  },
  data: () => ({
    prizeIcon,
    formDialog: false,
    qrCodeDialog: false,
    rules: rules,
    valid: true,
    writeOffQrCodeBase64: '',
    realName: '',
    phone: '',
    cardId: null, // 抽到的卡券id

    tab: 0,
    tabNameItems: [
      '待使用', '已使用', '已失效',
    ],
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
    currentCard() {
      if (!this.cardId) return null;
      return this.oneselfCardList.filter(item => item.id === this.cardId)[0];
    },
  },
  watch: {
    qrCodeDialog(val) {
      if (val) {
        this.getQrCode(`/qrcode/writeoff/${this.cardId}`);
      } else {
        this.realName = '';
        this.phone = '';
        this.cardId = null;
        this.closeFormDialog();
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
    getCountDownTimerString(date) {
      return `${date.getFullYear()}-${date.getMonth() +
      1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    },
    getEndTimeString(date) {
      return `${new Date(date).getFullYear()}-${new Date(date).getMonth() + 1}-${new Date(date).getDate()} 已到期`;
    },
  },
};
</script>

<style scoped lang="stylus">
  .card-title
    span
      font-size 18px
      padding-left: 26px
      color: #f4ac3a

    .btn
      background: rgb(244, 172, 58);
      background: -webkit-linear-gradient(0deg, rgb(244, 172, 58) 10%, rgb(255, 65, 53) 63%);
      background: linear-gradient(90deg, rgb(244, 172, 58) 10%, rgb(255, 65, 53) 63%);


  .card-description
    font-size 18px
    color: #1f1f1f

  .card-shop, .card-time {
    color: #585858
    font-size: 16px
  }

  .pt-200
    padding-top 120px

  .qrcode-dialog-body {
    background-color: #fde1b1
    padding: 17px

    h3 {
      span {
        color: #f4ac3a
        font-size: 24px
        padding-left 26px
      }
      padding: 20px 0 10px 0
    }

    .sub-title {
      font-size: 20px;
    }

    .des, .time {
      font-size: 18px;
      padding-bottom: 15px;
    }
  }
</style>
