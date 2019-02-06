<template xmlns:v-swiper="http://www.w3.org/1999/xhtml">
  <div>
    <div class="lucky-wheel">
      <h1 class="lucky-title text-xs-center">{{ activityName }}</h1>
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
    </div>
    <div class="main">
      <div class="main-bg"/>
      <div class="bg-p"/>
      <div class="content">
        <div class="lottery_ticket">本月剩余抽奖次数： {{ lotteryNum }}</div>
      </div>
      <div class="tip">
        <div class="tip-title">活动规则</div>
        <div class="tip-content"><p>{{ activityDescription }}</p></div>
      </div>
    </div>
    <div
      v-show="toast_control"
      class="toast">
      <div class="toast-container">
        <v-img
          :src="toastPicture"
          class="toast-picture"/>
        <div
          class="close"
          @click="closeToast()"/>
        <div
          class="toast-title"
          v-html="toastTitle"/>
        <div class="toast-btn">
          <div
            class="toast-cancel"
            @click="writeForm">{{ !!hasPrize? '点击领取':'再接再厉' }}
          </div>
        </div>
      </div>
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
          <v-card-title>请输入你的信息以领取卡券</v-card-title>
          <v-card-text>
            <v-text-field
              :rules="rules.nameRules"
              :counter="10"
              label="姓名"
              required
              append-icon="account_circle"/>
            <v-text-field
              :rules="rules.phoneRules"
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
        <v-img src="http://upload.mnw.cn/2016/0126/1453768615784.jpg"/>
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
import rules from '~/utils/rules';
import randomSort from '~/utils/randomSort';
import rangeRandom from '~/utils/rangeRandom';
import phoneNumberHeaders from '~/utils/phoneNumberHeaders';

export default {
  name: 'Lottery',
  layout: 'user',
  data: () => ({
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
      slidesPerView: 3,
      centeredSlides: true,
      direction: 'vertical',
      spaceBetween: 15,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      mousewheel: 'disable',
      allowTouchMove: false,
    },
    formDialog: false,
    qrCodeDialog: false,
    rules: rules,
    valid: true,
  }),
  computed: {
    ...mapState({
      location: state => state.oneself.location ? state.oneself.location : '', // 用户所在区域
      lotteryNum: state => state.oneself.oneself ? state.oneself.oneself['lottery_num'] : 0, // 剩余抽奖次数
      lotteryNeedsToFillInTheInformation: state => state.systemConfig.systemConfig ?
        /^true$/i.test(state.systemConfig.systemConfig.filter(
          item => item['config_name'] === 'lotteryNeedsToFillInTheInformation')[0]['config_value'])
        : true,// 抽奖填写信息配置
      cardModelList: state => {
        const cardList = state.card.cardModelList ?
          state.card.cardModelList.map(item => ({id: item['id'], name: item['card_name'], isPrize: 1})) : [];
        if (cardList.length > 8) {
          cardList.splice(8, cardList.length - 8);
        } else {
          for (let i = 0; 8 - cardList.length; i++) {
            cardList.push({name: '未中奖', isPrize: 0});
          }
        }
        return randomSort(cardList);
      }, // 显示的奖品列表带未中奖
      cardList1: state => state.card.cardModelList ?
        state.card.cardModelList.map(item => ({id: item['id'], name: item['card_name'], isPrize: 1})) : [], // 显示的奖品列表带未中奖
      activityName: state => state.shop.activity ? state.shop.activity['activity_name'] : '', // 剩余抽奖次数
      activityDescription: state => state.shop.activity ? state.shop.activity['activity_description'] : '', // 剩余抽奖次数
    }),
    toastTitle() {
      return this.hasPrize
        ? `恭喜您，获得${this.cardModelList[this.index].name}<br>请进入个人中心兑换奖励` : '未中奖';
    },
    toastPicture() {
      return this.hasPrize
        ? require('../../assets/images/congratulation.png')
        : require('../../assets/images/sorry.png');
    },
    winningLogData() {
      if (!this.cardList1.length) {
        return [];
      }
      const num = 10;
      const res = [];
      for (let i = 0; i < num; i++) {
        res.push(`${phoneNumberHeaders[rangeRandom(0, phoneNumberHeaders.length)]}
        ******${rangeRandom(0, 8)}${rangeRandom(0, 8)}${rangeRandom(0, 8)}抽中了
          ${this.cardList1[rangeRandom(0, this.cardList1.length)]['name']}`);
      }
      return res;
    },
  },
  created() {
    this.initPrizeList();
  },
  methods: {
    ...mapActions({
      addOneself: 'oneself/addOneself',
    }),
    //此方法为处理奖品数据
    initPrizeList(list) {},
    rotateHandle() {
      this.rotating();
    },
    async rotating() {
      if (!this.click_flag) return;
      const {data} = await this.$axios.$get(`/card/lottery/shop/${this.$route.query.shopid}?location=${this.location}`);
      if (data) {
        this.cardModelList.forEach((item, index) => {
          if (item['id'] === data) {
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
      if (this.lotteryNeedsToFillInTheInformation) {
        this.formDialog = true;
      } else {
        this.qrCodeDialog = true;
      }
    },
    submit() {
      if (this.$refs.form.validate()) {
        this.qrCodeDialog = !this.qrCodeDialog;
      }
    },
    closeQrCodeDialog() {
      this.qrCodeDialog = false;
      this.formDialog = false;
      this.$refs.form.reset();
    },
  },
};
</script>

<style scoped lang="stylus">
  .lucky-wheel {
    width: 100%;
    background rgb(252, 207, 133)
    background-size: 100%;
    padding-top: 1.5625rem;
    padding-bottom: 75px;
    box-sizing content-box

    .lucky-title {
      color: #f36d56;
      font-size 48px;
      background-size: 100%;
      margin-bottom 20px
      text-shadow 5px 5px 5px #f36d5670
    }
  }

  .wheel-main {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  .wheel-bg {
    width: 18.4375rem;
    height: 18.4375rem;
    background: url("../../assets/images/draw_wheel.png") no-repeat center top;
    background-size: 100%;
    color: #fff;
    font-weight: 500;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-content: center;
    transition: transform 3s ease;
  }

  .wheel-pointer-box {
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: 100;
    transform: translate(-50%, -60%);
    width: 5.3125rem;
    height: 5.3125rem;
  }

  .wheel-pointer {
    width: 5.3125rem;
    height: 5.3125rem;
    background: url("../../assets/images/draw_btn.png") no-repeat center top;
    background-size: 100%;
    transform-origin: center 60%;
  }

  .wheel-bg div {
    text-align: center;
  }

  .prize-list {
    width: 100%;
    height: 100%;
    position: relative;
  }

  .prize-list .prize-item {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
  }

  .prize-list .prize-item:first-child {
    top: 0;
    left: 8.3125rem;
    transform: rotate(20deg);
  }

  .prize-list .prize-item:nth-child(2) {
    top: 2.8rem;
    left: 10.8rem;
    transform: rotate(67deg);
  }

  .prize-list .prize-item:nth-child(3) {
    top: 6.4rem;
    left: 10.6rem;
    transform: rotate(-250deg);
  }

  .prize-list .prize-item:nth-child(4) {
    top: 9rem;
    left: 8.2125rem;
    transform: rotate(-210deg);
  }

  .prize-list .prize-item:nth-child(5) {
    top: 9.2125rem;
    left: 4.4rem;
    transform: rotate(-160deg);
  }

  .prize-list .prize-item:nth-child(6) {
    top: 6.3875rem;
    left: 1.9rem;
    transform: rotate(-111deg);
  }

  .prize-list .prize-item:nth-child(7) {
    top: 2.8rem;
    left: 1.8125rem;
    transform: rotate(-69deg);
  }

  .prize-list .prize-item:nth-child(8) {
    top: 0;
    left: 4.5rem;
    transform: rotate(-20deg);
  }

  .prize-item {
    width: 5.875rem;
    height: 9rem;
  }

  .prize-pic img {
    height: 2.5rem;
  }

  .prize-type {
    font-size: 1rem;
  }

  .main {
    position: relative;
    width: 100%;
    min-height: 14.25rem;
    background: rgb(243, 109, 86);
  }

  .main-bg {
    width: 100%;
    height: 6.5625rem;
    position: absolute;
    top: -3.4375rem;
    left: 0;
    background: url("../../assets/images/luck_bg.png") no-repeat center top;
    background-size: 100%;
  }

  .bg-p {
    width: 100%;
    height: 2.95rem;
    background: rgb(252, 207, 133);
  }

  .content {
    position: absolute;
    top: 0.175rem;
    left: 0;
    background: rgb(243, 109, 86);
    width: 100%;
    height: 5.1875rem;
    font-size: 1.125rem;
    color: #ffeb39;
    text-align: center;
  }

  .tip {
    position: relative;
    margin: 1.5rem auto 0;
    width: 17.5rem;
    border: 1px solid #fbc27f;
  }

  .tip-title {
    position: absolute;
    top: -1rem;
    left: 50%;
    transform: translate(-50%, 0);
    font-size: 1rem;
    color: #fccc6e;
    background: rgb(243, 109, 86);
    padding: 0.3125rem 0.625rem;
  }

  .tip-content {
    padding: 1.5625rem 0.625rem;
    font-size: 0.875rem;
    color: #fff8c5;
    line-height: 1.5;

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
    position: fixed;
    top: 50%;
    left: 50%;
    z-index: 20000;
    transform: translate(-50%, -50%);
    width: 15.4375rem;
    background: #fff;
    border-radius: 0.3125rem;
    padding: 0.3125rem;
    background-color: rgb(252, 244, 224);
  }

  .toast-container {
    position: relative;
    width: 100%;
    height: 100%;
    border: 1px dotted #fccc6e;
  }

  .toast-picture {
    position: absolute;
    top: -6.5rem;
    left: -1.5rem;
    width: 18.75rem;
    height: 8.5625rem;
  }

  .toast-title {
    padding: 2.8125rem 0;
    font-size: 18px;
    color: #fc7939;
    text-align: center;
  }

  .toast-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.9375rem;
  }

  .toast-btn div {
    background-image: -moz-linear-gradient(
      -18deg,
      rgb(242, 148, 85) 0%,
      rgb(252, 124, 88) 51%,
      rgb(252, 124, 88) 99%
    );
    background-image: -ms-linear-gradient(
      -18deg,
      rgb(242, 148, 85) 0%,
      rgb(252, 124, 88) 51%,
      rgb(252, 124, 88) 99%
    );
    background-image: -webkit-linear-gradient(
      -18deg,
      rgb(242, 148, 85) 0%,
      rgb(252, 124, 88) 51%,
      rgb(252, 124, 88) 99%
    );
    box-shadow: 0px 4px 0px 0px rgba(174, 34, 5, 0.7);
    border-radius: 1.875rem;
    text-align: center;
    line-height: 1.875rem;
    color #fff
    padding 2px 15px
  }

  .close {
    position: absolute;
    top: -0.9375rem;
    right: -0.9375rem;
    width: 2rem;
    height: 2rem;
    background: url("../../assets/images/close_store.png") no-repeat center top;
    background-size: 100%;
  }

  .my-swiper {
    height 95px
    width: 100%;
    margin-top 30px
    overflow hidden
    padding 5px 0
    background-color #fccf85

    .swiper-slide {
      display: flex;
      justify-content: center;
      align-items: center;

      p {
        display inline-block
        text-align center
        background-color rgba(0, 0, 0, 0.6)
        line-height: 20px;
        color #ffffff
        border-radius 5000px
        padding 3px 13px
        margin 0
      }
    }

    .swiper-pagination > .swiper-pagination-bullet {
      background-color: red;
    }
  }
</style>
