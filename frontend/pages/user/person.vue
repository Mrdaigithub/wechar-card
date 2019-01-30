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
                :end-time="new Date(new Date(item['created_at']).getTime() + item['end_time_0'] * 60).toString()"/>
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
              :counter="10"
              label="姓名"
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
  import CountDownTimer from '~/components/CountDownTimer';
  
  export default {
    name: 'Person',
    layout: 'user',
    components: {
      CountDownTimer,
    },
    data: () => ({
      items: [
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝500元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          endTime: '2018-12-25',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: false,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: false,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: false,
          startTime: '2018-12-21',
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: true,
          endTime: '2018-12-30',
        },
        {
          card_thumbnail: 'https://cdn.vuetifyjs.com/images/employeeList/foster.jpg',
          card_name: '海底涝5元免单卷',
          description: '海底涝5元免单卷介绍海底涝5元免单卷介绍',
          state: false,
          endTime: '2018-12-30',
        },
      ],
      formDialog: false,
      qrCodeDialog: false,
      rules: rules,
      valid: true,
      viewState: true, // 当前查看为有效券或失效券
    }),
    computed: {
      ...mapState({
        oneselfCardList: state => state.oneself.cardList ? state.oneself.cardList : [], // 剩余抽奖次数
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
        this.formDialog = true;
      },
      submit() {
        if (this.$refs.form.validate()) {
          this.qrCodeDialog = !this.qrCodeDialog;
        }
      },
      closeFormDialog() {
        this.formDialog = false;
        this.$refs.form.reset();
      },
      closeQrCodeDialog() {
        this.qrCodeDialog = false;
        this.closeFormDialog();
      },
    },
  };
</script>
