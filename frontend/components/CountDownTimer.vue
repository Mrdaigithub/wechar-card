<template>
  <div>
    {{ `${day}天${hr}时${min}分${sec}秒 后过期` }}
  </div>
</template>

<script>
  import {mapState, mapMutations} from 'vuex';
  
  export default {
    name: 'CountDownTimer',
    props: {
      'endTime': {
        type: String,
        default: '',
      },
      'id': {
        type: Number,
        default: '0',
      },
    },
    data() {
      return {
        day: 0, hr: 0, min: 0, sec: 0,
        timer: null,
      };
    },
    computed: {
      ...mapState({
        cardList: state => state.oneself.cardList ? state.oneself.cardList : [], // 剩余抽奖次数
      }),
    },
    mounted() {
      this.countdown();
    },
    destroyed() {
      clearTimeout(this.timer);
    },
    methods: {
      ...mapMutations({
        addCardList: 'oneself/addCardList',
      }),
      countdown() {
        if (new Date(this.endTime).getTime() < new Date().getTime()) {
          const _cardList = JSON.parse(JSON.stringify(this.cardList));
          this.addCardList(_cardList.map(item => {
            if (item.id === this.id) {
              item.state = 0;
            }
            return item;
          }));
        }
        const end = Date.parse(new Date(this.endTime));
        const now = Date.parse(new Date());
        const msec = end - now;
        let day = parseInt(msec / 1000 / 60 / 60 / 24);
        let hr = parseInt(msec / 1000 / 60 / 60 % 24);
        let min = parseInt(msec / 1000 / 60 % 60);
        let sec = parseInt(msec / 1000 % 60);
        this.day = day;
        this.hr = hr > 9 ? hr : '0' + hr;
        this.min = min > 9 ? min : '0' + min;
        this.sec = sec > 9 ? sec : '0' + sec;
        const that = this;
        this.timer = setTimeout(function() {
          that.countdown();
        }, 1000);
      },
    },
  };
</script>

<style scoped lang="stylus">

</style>
