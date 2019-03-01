<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>中奖核销记录管理</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical/>
        <v-spacer/>
        <div>
          <v-btn
            fab
            small
            dark
            @click="reset">
            <v-icon>refresh</v-icon>
          </v-btn>
        </div>
        <v-dialog
          v-model="dialog"
          max-width="1200px">
          <v-btn
            slot="activator"
            color="primary"
            dark>筛选数据
          </v-btn>
          <v-card>
            <v-card-title>
              <span class="headline">筛选数据</span>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <el-form
                  ref="editedItem"
                  :model="editedItem"
                  :rules="rules"
                  label-width="80px"
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="商铺"
                        prop="shop">
                        <el-select
                          v-model="editedItem.shop"
                          class="w100"
                          filterable>
                          <el-option
                            v-for="item in shopList"
                            :key="item.id"
                            :label="item.shop_name"
                            :value="item.id"/>
                        </el-select>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="时间范围"
                        prop="dateRange">
                        <el-date-picker
                          v-model="editedItem.dateRange"
                          type="datetimerange"
                          range-separator="至"
                          start-placeholder="开始日期"
                          end-placeholder="结束日期"/>
                      </el-form-item>
                    </v-flex>
                  </v-layout>
                </el-form>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer/>
              <v-btn
                flat
                @click="close">关闭
              </v-btn>
              <v-btn
                :disabled="!valid"
                color="red"
                flat
                @click="save">确定
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
        :items="filterData(winningLogList)"
        :search="search"
        :pagination.sync="pagination"
        :rows-per-page-items="[10]">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item.shop_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.activity_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.card_name || '暂无' }}</td>
          <td class="text-xs-center">
            {{ userList.filter(item=>item.id===props.item['user_id'])[0] ?
            userList.filter(item=>item.id===props.item['user_id'])[0]['username'] : '暂无' }}
          </td>
          <td class="text-xs-center">
            {{ userList.filter(item=>item.id===props.item['user_id'])[0] ?
            userList.filter(item=>item.id===props.item['user_id'])[0]['real_name'] || '暂无': '暂无' }}
          </td>
          <td class="text-xs-center">
            {{ userList.filter(item=>item.id===props.item['user_id'])[0] ?
            userList.filter(item=>item.id===props.item['user_id'])[0]['phone'] || '暂无' : '暂无' }}
          </td>
          <td class="text-xs-center">
            <v-avatar
              slot="activator"
              size="48px">
              <v-img
                v-if="userList.filter(item=>item.id===props.item['user_id'])[0]"
                :src="userList.filter(item=>item.id===props.item['user_id'])[0]['head_img_url']"
                alt="Avatar"/>
              <div v-else>暂无</div>
            </v-avatar>
          </td>
          <td class="text-xs-center">{{ props.item.location || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.address || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item['created_at'] || '暂无' }}</td>
          <td class="text-xs-center">
            {{ shopEmployeeList.filter(item=>item.id===props.item['write_offer_id'])[0] ?
            shopEmployeeList.filter(item=>item.id===props.item['write_offer_id'])[0]['username'] : '暂无' }}
          </td>
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
  name: 'AdminLogWinning',
  layout: 'admin',
  data: () => ({
    valid: true,
    breadcrumbList: [
      {
        text: '主页',
        disabled: false,
        href: '/admin',
      },
      {
        text: '中奖核销记录管理',
        disabled: true,
        href: '/admin/log/winning',
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '商家名称', align: 'center', value: 'shop_name'},
      {text: '活动名称', align: 'center', value: 'activity_name'},
      {text: '卡卷名称', align: 'center', value: 'card_name'},
      {text: '用户名', align: 'center', value: 'username'},
      {text: '真实姓名', align: 'center', value: 'real_name'},
      {text: '手机号', align: 'center', value: 'phone'},
      {text: '用户头像', align: 'center', value: 'head_img_url'},
      {text: '所在城市', align: 'center', value: 'location'},
      {text: '所在地址', align: 'center', value: 'address'},
      {text: '中奖时间', align: 'center', value: 'created_at'},
      {text: '核销人员', align: 'center', value: 'write_offer_name'},
      {text: '核销状态', align: 'center', value: 'write_off'},
      {text: '核销时间', align: 'center', value: 'write_off_date'},
    ],
    pagination: {'sortBy': 'id', 'descending': true, 'rowsPerPage': -1},
    rules: {
      shop: [
        {required: true, message: '请选择商铺', trigger: 'blur'},
      ],
      dateRange: [
        {required: true, message: '请选择时间范围', trigger: 'blur'},
      ],
    },
    editedItem: {
      shop: null,
      dateRange: [],
    },
    defaultItem: {
      shop: null,
      dateRange: [],
    },
    search: '',
    filterState: false, // 是否处在筛选状态
  }),
  computed: {
    ...mapState({
      winningLogList: state => state.log.winningLogList ? state.log.winningLogList : [],
      userList: state => state.user.userList ? state.user.userList : [],
      shopEmployeeList: state => state.user.shopEmployeeList ? state.user.shopEmployeeList : [],
      shopList: state => state.shop.shopList ? state.shop.shopList : [],
    }),
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  mounted() {
    Loading.service({fullscreen: true});
    this.addWinningLogList();
    this.addUserList();
    this.addShopEmployeeList();
    this.addShopList(Loading.service({fullscreen: true}).close());
  },
  methods: {
    ...mapActions({
      addWinningLogList: 'log/addWinningLogList',
      addUserList: 'user/addUserList',
      addShopEmployeeList: 'user/addShopEmployeeList',
      addShopList: 'shop/addShopList',
    }),
    editItem(item) {
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    close() {
      this.dialog = false;
      if (!this.filterState) {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.$refs.editedItem.resetFields();
        this.valid = true;
      }
    },
    save() {
      this.$refs.editedItem.validate((valid) => {
        if (valid) {
          this.filterState = true;
          this.close();
        }
      });
    },
    filterData(data) {
      if (!this.filterState) return data;
      const shopName = this.shopList.filter(item => item.id === this.editedItem.shop)[0]['shop_name'];
      return this.winningLogList.filter(item =>
        (item['shop_name'] === shopName)
        && (item['write_off_state'])
        && (new Date(item['write_off_date']).getTime() >= this.editedItem.dateRange[0].getTime())
        && (new Date(item['write_off_date']).getTime() <= this.editedItem.dateRange[1].getTime()));
    },
    reset() {
      this.filterState = false;
      this.close();
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
