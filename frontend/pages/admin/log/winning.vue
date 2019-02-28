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
        <v-dialog
          v-model="dialog"
          max-width="1200px">
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
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
                        label="备注"
                        prop="remarks">
                        <el-input
                          v-model="editedItem.remarks"/>
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
                @click="save">保存
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
    rules: {
      remarks: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
    },
    editedIndex: -1,
    editedItem: {
      remarks: '',
    },
    defaultItem: {
      remarks: '',
    },
    search: '',
  }),
  computed: {
    ...mapState({
      winningLogList: state => state.log.winningLogList ? state.log.winningLogList : [],
      userList: state => state.user.userList ? state.user.userList : [],
      shopEmployeeList: state => state.user.shopEmployeeList ? state.user.shopEmployeeList : [],
      shopList: state => state.shop.shopList ? state.shop.shopList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加中奖核销记录' : '修改中奖核销记录';
    },
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
      this.editedIndex = this.winningLogList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
        this.$refs.editedItem.resetFields();
        this.valid = true;
      }, 100);
    },
    save() {
      this.$refs.editedItem.validate((valid) => {
        if (valid) {
          if (this.editedIndex > -1) {
            Object.assign(this.winningLogList[this.editedIndex], this.editedItem);
          } else {
            this.winningLogList.push(JSON.parse(JSON.stringify(this.editedItem)));
          }
          this.close();
        }
      });
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
