<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>核销管理</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical/>
        <v-spacer/>
      </v-toolbar>
      <v-content class="px-4">
        <v-text-field
          v-model="search"
          append-icon="search"
          label="搜索"
          single-line
          hide-details/>
      </v-content>
      <v-data-table
        :headers="headers"
        :items="winningLogList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item.shop_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.activity_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.card_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.username || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item['real_name'] || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item['phone'] || '暂无' }}</td>
          <td class="text-xs-center">
            <v-avatar
              v-if="props.item['head_img_url']"
              slot="activator"
              size="48px">
              <v-img
                :src="props.item['head_img_url']"
                :lazy-src="props.item['head_img_url']"
                alt="Avatar"/>
            </v-avatar>
            <div v-else>暂无</div>
          </td>
          <td class="text-xs-center">{{ props.item.location }}</td>
          <td class="text-xs-center">{{ props.item['created_at'] }}</td>
          <td class="text-xs-center">{{ props.item['write_offer_name'] || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item['write_off_state'] ? '已核销' : '未核销' }}</td>
          <td class="text-xs-center">{{ props.item['write_off_date'] || '暂无' }}</td>
        </template>
        <template slot="no-data">
          <v-alert
            :value="true"
            color="error"
            icon="warning">
            暂无记录 :(
          </v-alert>
        </template>
        <v-alert
          slot="no-results"
          :value="true"
          color="error"
          icon="warning">
          没有找到{{ search }}
        </v-alert>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import {Loading} from 'element-ui';

export default {
  name: 'WriteOff',
  layout: 'shop',
  data: () => ({
    breadcrumbList: [
      {
        text: '主页',
        disabled: false,
        href: '/shop',
      },
      {
        text: '核销管理',
        disabled: true,
        href: '/shop/writeoff',
      },
    ],
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '商家名称', align: 'center', value: 'shop_name'},
      {text: '活动名称', align: 'center', value: 'activity_name'},
      {text: '卡卷名称', align: 'center', value: 'card_name'},
      {text: '用户名', align: 'center', value: 'username'},
      {text: '真实姓名', align: 'center', value: 'real_name'},
      {text: '手机号', align: 'center', value: 'phone'},
      {text: '用户头像', align: 'center', value: 'head_img_url'},
      {text: '用户位置', align: 'center', value: 'location'},
      {text: '中奖时间', align: 'center', value: 'created_at'},
      {text: '核销人员', align: 'center', value: 'write_offer_name'},
      {text: '核销状态', align: 'center', value: 'write_off'},
      {text: '核销时间', align: 'center', value: 'write_off_date'},
    ],
    search: '',
  }),
  computed: {
    ...mapState({
      winningLogList: state => state.log.winningLogList ? state.log.winningLogList : [],
      userList: state => state.user.userList ? state.user.userList : [],
      shopEmployeeList: state => state.user.shopEmployeeList ? state.user.shopEmployeeList : [],
      shop: state => state.shop.shop ? state.shop.shop : null,
    }),
  },
  watch: {
    shop(val) {
      if (!val) return;
      Loading.service({fullscreen: true});
      this.addWinningLogListByShopId({arg: val.id, cb: () => Loading.service({fullscreen: true}).close()});
    },
  },
  mounted() {
    if (this.shop) {
      this.addWinningLogListByShopId({arg: this.shop.id, cb: () => Loading.service({fullscreen: true}).close()});
    }
  },
  methods: {
    ...mapActions({
      addWinningLogListByShopId: 'log/addWinningLogListByShopId',
      addUserList: 'user/addUserList',
      addShopEmployeeList: 'user/addShopEmployeeList',
      addShopList: 'shop/addShopList',
    }),
  },
};
</script>
