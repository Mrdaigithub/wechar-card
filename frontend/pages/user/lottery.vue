<template xmlns:v-swiper="http://www.w3.org/1999/xhtml">
  <div class="lucky-wrap text-xs-center">
    <div
      v-swiper:mySwiper="swiperOption"
      v-if="winningLogData.length"
      class="my-swiper">
      <div class="swiper-wrapper">
        <div
          v-for="winningLog in winningLogData"
          :key="winningLog"
          class="swiper-slide">
          <p>{{ winningLog }}</p>
        </div>
      </div>
    </div>
    <p class="lottery-user-num-text text-xs-center white--text">已有{{ customerNum }}人参与此活动</p>
    <div class="lottery-num-text text-xs-center white--text">
      <p v-if="lotteryNum > 0">您还有<span>{{ lotteryNum }}</span>次免费抽奖机会</p>
      <p v-else>您已用完了所有的抽奖次数</p>
    </div>
    <div class="more-lottery-num-btn-wrap">
      <v-btn
        outline
        round
        class="font-weight-black more-lottery-num-btn"
        color="white"
        @click="changePage(`/user/signIn?shopid=${$route.query.shopid}`)">点击获取更多抽奖次数
      </v-btn>
    </div>
    <div class="lucky-wheel">
      <div class="wheel-main">
        <div class="wheel-pointer-box">
          <div
            :style="{transform:rotate_angle_pointer,transition:rotate_transition_pointer}"
            class="wheel-pointer"
            @click="rotateHandle()"/>
        </div>
        <div
          :style="{transform:rotate_angle,transition:rotate_transition}"
          class="wheel-bg">
          <div class="prize-list">
            <div
              v-for="(item,index) in cardModelList"
              :key="index"
              class="prize-item">
              <div class="prize-pic">
                <img :src="item.icon">
              </div>
              <div class="prize-type">
                {{ item.name }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="rule text-xs-left">
      <p class="rule-content">
        抽奖说明：<br>
        每人可抽1次，第2次抽奖需要签到{{ howManyDaysHaveYouWonTheLotteryIn15Days }}天。<br><br>
        活动须知：<br>
        1、免单奖励使用时间以免单卷上有效期为准，过期
        无效。<br>
        2、每次限使用1张，此卷不可叠加使用。<br>
        3、活动过程中凡以恶意手段（包括但不限于作弊、
        攻击系统等）参与的用户，活动方有权终止其参与活
        动并取消其领卷/用卷的资格。
      </p>
    </div>
    <div
      v-show="toast_control"
      class="toast">
      <div
        class="toast-container"
        @click="writeForm">
        <img
          :src="hasPrize ? totalDialogBg1 : totalDialogBg2"
          alt="">
        <div
          v-if="hasPrize"
          class="toast-body white--text text-xs-center">
          <h3>{{ cardModelList[index].name }}</h3>
          <p>{{ cardModelList[index]['remarks'] }}</p>
        </div>
      </div
      >
    </div>
    <div
      v-show="toast_control"
      class="toast-mask"/>
    <v-dialog
      v-model="formDialog"
      persistent
      max-width="500px">
      <v-form
        ref="form"
        v-model="valid"
        lazy-validation>
        <v-card>
          <v-card-title>请输入你的信息领取奖励</v-card-title>
          <v-card-text>
            <v-text-field
              :rules="rules.nameRules"
              :counter="10"
              v-model="realName"
              label="姓名"
              required
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
              @click="formDialog=false">关闭
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
      <v-card class="qrcode-dialog-body">
        <div style="background-color:#fff;border-radius: 10px">
          <h3 class="text-xs-center ma-0">
            <span
              :style="{background: 'url(' + prizeIcon + ') no-repeat left center', backgroundSize: 'contain'}">
              {{ cardModelList[index].name }}
            </span>
          </h3>
          <p class="text-xs-center ma-0 sub-title">{{ cardModelList[index].remarks }}</p>
          <v-img
            v-if="writeOffQrCodeBase64"
            :src="`data:image/png;base64,${writeOffQrCodeBase64}`"/>
          <p class="text-xs-center ma-0 des">请让商家扫码核销优惠卷</p>
          <p
            v-if="!!currentCard"
            class="text-xs-center ma-0 time">
            {{ currentCard['end_time_0'] ? `有效期: ${currentCard['end_time_0']}` : null }}
            <CountDownTimer
              v-if="currentCard['end_time_1']"
              :id="currentCard.id"
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
import CountDownTimer from '~/components/CountDownTimer';
import surnameList from '~/utils/surnameList';
import rules from '~/utils/rules';
import randomSort from '~/utils/randomSort';
import rangeRandom from '~/utils/rangeRandom';
import phoneNumberHeaders from '~/utils/phoneNumberHeaders';
import totalDialogBg1 from '~/assets/images/total-dialog-bg-1.jpg';
import totalDialogBg2 from '~/assets/images/total-dialog-bg-2.jpg';
import prizeIcon from '~/assets/images/prize-icon.png';

export default {
  name: 'Lottery',
  layout: 'user',
  components: {
    CountDownTimer,
  },
  data: () => ({
    surnameList,
    totalDialogBg1,
    totalDialogBg2,
    prizeIcon,
    toast_control: false, //抽奖结果弹出框控制器
    hasPrize: false, //是否中奖
    start_rotating_degree: 0, //初始旋转角度
    rotate_angle: 0, //将要旋转的角度
    start_rotating_degree_pointer: 0, //指针初始旋转角度
    rotate_angle_pointer: 0, //指针将要旋转的度数
    rotate_transition: 'transform 6s ease-in-out', //初始化选中的过度属性控制
    rotate_transition_pointer: 'transform 12s ease-in-out', //初始化指针过度属性控制
    click_flag: true, //是否可以旋转抽奖
    index: 0,
    swiperOption: {
      loop: true,
      slidesPerView: 1,
      centeredSlides: true,
      direction: 'vertical',
      spaceBetween: 5,
      autoplay: 2000,
      mousewheel: 'disable',
      allowTouchMove: false,
    },
    formDialog: false,
    qrCodeDialog: false,
    rules: rules,
    valid: true,
    writeOffQrCodeBase64: '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',
    realName: '',
    phone: '',
    currentCard: null, // 抽到的卡券id
  }),
  computed: {
    ...mapState({
      location: state => state.oneself.location ? state.oneself.location : '', // 用户所在城市
      address: state => state.oneself.address ? state.oneself.address : '', // 用户所在区域
      oneself: state => state.oneself.oneself ? state.oneself.oneself : {}, // 当前用户
      howManyDaysHaveYouWonTheLotteryIn15Days: state => state.systemConfig.systemConfig
        ? state.systemConfig.systemConfig.filter(
          item => item['config_name'] === 'howManyDaysHaveYouWonTheLotteryIn15Days')[0]['config_value'] : 7,
      customerNum: state => state.shop.activity ? state.shop.activity['customer_num'] + 110 : 0, // 活动参与人数
      lotteryNum: state => state.oneself.oneself ? state.oneself.oneself['lottery_num'] : 0, // 剩余抽奖次数
      lotteryNeedsToFillInTheInformation: state => state.systemConfig.systemConfig ?
        /^true$/i.test(state.systemConfig.systemConfig.filter(
          item => item['config_name'] === 'lotteryNeedsToFillInTheInformation')[0]['config_value'])
        : true,// 抽奖填写信息配置
      cardModelList: state => {
        const cardList = state.card.cardModelList ?
          state.card.cardModelList.map(
            item => ({id: item['id'], name: item['card_name'], remarks: item['remarks'], isPrize: 1})) : [];
        if (cardList.length > 8) {
          cardList.splice(8, cardList.length - 8);
        } else {
          for (let i = 0; 8 - cardList.length; i++) {
            cardList.push({name: '未中奖', isPrize: 0});
          }
        }
        return randomSort(cardList);
      }, // 显示的奖品列表带未中奖
      cardList1: state => state.card.cardModelList ? state.card.cardModelList.map(
        item => ({id: item['id'], name: item['card_name'], isPrize: 1})) : [], // 显示的奖品列表带未中奖
    }),
    winningLogData() {
      if (!this.cardList1.length && !!surnameList.length) {
        return [];
      }
      const num = 10;
      const res = [];
      for (let i = 0; i < num; i++) {
        res.push(`中奖信息: 恭喜${surnameList[Math.floor(Math.random() * surnameList.length)]}...抽到免单券`);
      }
      return res;
    },
  },
  watch: {
    qrCodeDialog(val) {
      if (val) {
        this.getQrCode(`/qrcode/writeoff/${this.currentCard.id}`);
      } else {
        this.realName = '';
        this.phone = '';
        this.currentCard = null;
        this.closeFormDialog();
      }
    },
  },
  created() {
    this.initPrizeList();
  },
  mounted() {
    window.Echo.channel('publicChannel').listen('MessageEvent', async (e) => {
      if (e.message && this.oneself &&
        JSON.parse(e.message).signal === 'writeOff' &&
        this.oneself.id === JSON.parse(e.message)['user_id']) {
        Message.success('核销成功');
        this.formDialog = false;
        this.qrCodeDialog = false;
      }
    });
  },
  methods: {
    ...mapActions({
      addOneself: 'oneself/addOneself',
      updatePlainUserByOneself: 'oneself/updatePlainUserByOneself',
    }),
    changePage(url) {
      this.$router.push(url);
    },
    //此方法为处理奖品数据
    initPrizeList(list) {
    },
    rotateHandle() {
      this.currentCard = null;
      this.rotating();
    },
    async rotating() {
      if (!this.click_flag) return;
      Loading.service({fullscreen: true});
      const {data} = await this.$axios.$get(
        `/card/lottery/shop/${this.$route.query.shopid}?location=${this.location}&address=${this.address}`);
      Loading.service({fullscreen: true}).close();
      if (data.index) {
        this.currentCard = data['card'];
        this.cardModelList.forEach((item, index) => {
          if (item['id'] === data.index) {
            this.index = index;
          }
        });
      } else {
        this.cardModelList.forEach((item, index) => {
          if (item['isPrize'] === 0) {
            this.index = index;
          }
        });
      }
      const type = 0; // 默认为 0  转盘转动 1 箭头和转盘都转动(暂且遗留)
      const during_time = 5; // 默认为1s
      const result_index = this.index; // 最终要旋转到哪一块，对应prize_list的下标
      const result_angle = [337.5, 292.5, 247.5, 202.5, 157.5, 112.5, 67.5, 22.5]; //最终会旋转到下标的位置所需要的度数
      const rand_circle = 8; // 附加多转几圈，2-3
      this.click_flag = false; // 旋转结束前，不允许再次触发
      if (type === 0) {
        // 转动盘子
        const rotate_angle =
          this.start_rotating_degree +
          rand_circle * 360 +
          result_angle[result_index] -
          this.start_rotating_degree % 360;
        this.start_rotating_degree = rotate_angle;
        this.rotate_angle = 'rotate(' + rotate_angle + 'deg)';
        this.addOneself();
        // // //转动指针
        // this.rotate_angle_pointer = "rotate("+this.start_rotating_degree_pointer + 360*rand_circle+"deg)";
        // this.start_rotating_degree_pointer =360*rand_circle;
        // 旋转结束后，允许再次触发
        setTimeout(() => {
          this.click_flag = true;
          this.gameOver();
        }, during_time * 1000 + 1500); // 延时，保证转盘转完
      } else {
        //
      }
    },
    gameOver() {
      this.toast_control = true;
      this.hasPrize = this.cardModelList[this.index].isPrize;
    },
    closeToast() {
      this.toast_control = false;
    },
    writeForm() {
      if (!this.hasPrize) {
        this.closeToast();
        return false;
      }
      this.closeToast();
      if (!this.lotteryNeedsToFillInTheInformation || (this.oneself['real_name'] && this.oneself['phone'])) {
        this.qrCodeDialog = true;
      } else {
        this.formDialog = true;
      }
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.updatePlainUserByOneself({
          arg: {
            real_name: this.realName,
            phone: this.phone,
          },
          cb: () => this.addOneself(),
        });
        this.qrCodeDialog = !this.qrCodeDialog;
        this.$refs.form.reset();
      }
    },
    closeFormDialog() {
      this.formDialog = false;
      this.$refs.form.reset();
    },
    async getQrCode(url) {
      Loading.service({fullscreen: true});
      const {data} = await this.$axios.$get(url);
      Loading.service({fullscreen: true}).close();
      this.writeOffQrCodeBase64 = data;
    },
    getCountDownTimerString(date) {
      return `${date.getFullYear()}-${date.getMonth() +
      1}-${date.getDate()} ${date.getHours()}:${date.getMinutes()}:${date.getSeconds()}`;
    },
  },
};
</script>

<style scoped lang="stylus">
  .lucky-wrap
    padding-top 25px
    background rgb(249, 29, 72)
    background -moz-linear-gradient(90deg, rgb(249, 29, 72) 20%, rgb(143, 17, 139) 80%)
    background -webkit-linear-gradient(90deg, rgb(249, 29, 72) 20%, rgb(143, 17, 139) 80%)
    background -o-linear-gradient(90deg, rgb(249, 29, 72) 20%, rgb(143, 17, 139) 80%)
    background -ms-linear-gradient(90deg, rgb(249, 29, 72) 20%, rgb(143, 17, 139) 80%)
    background linear-gradient(180deg, rgb(249, 29, 72) 20%, rgb(143, 17, 139) 80%)
    background-size 100%
    min-height 100vh

    .lottery-user-num-text
      font-size 18px

    .lottery-num-text
      font-size 16px

      span
        color: #ecff02
        font-size 18px
        font-weight 700

    .more-lottery-num-btn-wrap
      height: 40px
      margin-bottom: 15px;

      .more-lottery-num-btn
        font-size 16px
        height: 40px
        padding-left: 25px;
        padding-right: 25px;

  .lucky-wheel
    width 100%
    padding-top 15px
    box-sizing content-box

    .wheel-main
      display flex
      align-items center
      justify-content center
      position relative

      .wheel-bg
        width 18.4375rem
        height 18.4375rem
        background url("../../assets/images/draw_wheel.png") no-repeat center top
        background-size 100%
        color #fff
        font-weight 500
        display flex
        flex-direction column
        justify-content center
        align-content center
        transition transform 3s ease

        div
          text-align center

      .wheel-pointer-box
        position absolute
        top 50%
        left 50%
        z-index 100
        transform translate(-50%, -60%)
        width 5.3125rem
        height 5.3125rem

        .wheel-pointer
          width 5.3125rem
          height 5.3125rem
          background url("../../assets/images/draw_btn.png") no-repeat center top
          background-size 100%
          transform-origin center 60%

      .prize-list
        width 100%
        height 100%
        position relative

        .prize-item
          position absolute
          top 0
          left 0
          z-index 2
          width 5.875rem
          height 9rem

          &:first-child
            top 0
            left 8.3125rem
            transform rotate(20deg)


          &:nth-child(2)
            top 2.8rem
            left 10.8rem
            transform rotate(67deg)


          &:nth-child(3)
            top 6.4rem
            left 10.6rem
            transform rotate(-250deg)


          &:nth-child(4)
            top 9rem
            left 8.2125rem
            transform rotate(-210deg)


          &:nth-child(5)
            top 9.2125rem
            left 4.4rem
            transform rotate(-160deg)


          &:nth-child(6)
            top 6.3875rem
            left 1.9rem
            transform rotate(-111deg)


          &:nth-child(7)
            top 2.8rem
            left 1.8125rem
            transform rotate(-69deg)


          &:nth-child(8)
            top 0
            left 4.5rem
            transform rotate(-20deg)


          .prize-pic img
            height 2.5rem


          .prize-type
            font-size 1rem

  .rule {
    position relative
    margin 15px 0
    width 100%
  }

  .rule-content {
    background-color rgba(255, 255, 255, .4)
    padding 22px 25px
    margin 0 10px
    border-radius 5px
    font-size 14px
    color #ffffff
    line-height 1.5

    p {
      margin 0
    }
  }

  .toast-mask {
    position: fixed;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    width: 100%;
    height: 100%;
  }

  .toast {
    position: fixed
    top: 50%
    left: 50%
    z-index: 20000
    transform: translate(-50%, -50%)
    background-size contain
    border-radius: 0.3125rem
    padding: 0.3125rem
    width 90%
  }

  .toast-container {
    position: relative;

    img {
      width 100%
    }

    .toast-body {
      width 100%
      position: absolute
      top: 110px
      left: 0
      font-size: 18px
      box-sizing border-box

      h3 {
        font-size: 22px
        color: #eeff18
        margin-bottom: 25px
      }
    }
  }

  .my-swiper
    background-color rgba(255, 255, 255, .4)
    height 40px
    width 80%
    border-radius 5px
    overflow hidden
    margin: auto;
    margin-bottom 10px

    .swiper-slide
      display flex
      justify-content center
      align-items center

      p
        display inline-block
        text-align center
        font-size 14px
        color #ffffff
        margin 0

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
