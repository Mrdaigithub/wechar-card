<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>用户管理</v-toolbar-title>
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
                        label="真实姓名"
                        prop="real_name">
                        <el-input
                          v-model="editedItem.real_name"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="电话号码"
                        prop="phone">
                        <el-input
                          v-model.number="editedItem.phone"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="抽奖次数"
                        prop="lottery_num">
                        <el-input-number
                          v-model="editedItem.lottery_num"
                          :min="0"/>
                      </el-form-item>
                    </v-flex>
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
        :items="userList"
        :search="search"
        :rows-per-page-items="[5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.username }}</td>
          <td class="text-xs-left">{{ props.item.real_name ? props.item.real_name : '暂无' }}</td>
          <td class="text-xs-left">{{ props.item.phone ? props.item.phone : '暂无' }}</td>
          <td class="text-xs-left">
            <v-avatar
              slot="activator"
              size="48px">
              <img
                :src="props.item.head_img_url"
                alt="Avatar">
            </v-avatar>
          </td>
          <td class="text-xs-left">{{ props.item.openid }}</td>
          <td class="text-xs-left">{{ props.item.sign_in_num }}</td>
          <td class="text-xs-left">{{ props.item.lottery_num }}</td>
          <td class="text-xs-left">{{ formatDate(props.item.created_at) }}</td>
          <td class="text-xs-left">{{ props.item.remarks }}</td>
          <td class="justify-center layout px-0">
            <v-icon
              small
              class="mr-2"
              @click="editItem(props.item)">
              edit
            </v-icon>
            <v-icon
              small
              @click="deleteItem(props.item)">
              delete
            </v-icon>
          </td>
        </template>
        <template slot="no-data">
          <v-alert
            :value="true"
            color="error"
            icon="warning">
            暂无用户 :(
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
import qs from 'qs';

export default {
  name: 'AdminUser',
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
        text: '用户管理',
        disabled: true,
        href: '/admin/user',
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'left', sortable: true, value: 'id'},
      {text: '用户名称', align: 'left', value: 'username'},
      {text: '真实姓名', align: 'left', value: 'real_name'},
      {text: '电话号码', align: 'left', value: 'phone'},
      {text: '用户头像', align: 'left', value: 'head_img_url'},
      {text: 'open ID', align: 'left', value: 'openid'},
      {text: '本月签到次数', align: 'left', value: 'sign_in_num'},
      {text: '抽奖次数', align: 'left', value: 'lottery_num'},
      {text: '创建时间', align: 'left', value: 'created_at'},
      {text: '备注', align: 'left', value: 'remarks'},
      {text: '操作', align: 'left', value: 'username', sortable: false},
    ],
    rules: {
      real_name: [
        {min: 1, max: 20, message: '长度在 1 到 20 个字符', trigger: 'change'},
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      phone: [
        {
          pattern: /^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/,
          message: '格式不正确',
          trigger: 'change',
        },
      ],
      remarks: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
    },
    editedIndex: -1,
    editedItem: {
      real_name: '',
      phone: '',
      lottery_num: 0,
      remarks: '',
    },
    defaultItem: {
      real_name: '',
      phone: '',
      lottery_num: 0,
      remarks: '',
    },
    search: '',
  }),
  computed: {
    ...mapState({
      userList: state => state.user.userList ? state.user.userList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加用户' : '修改用户';
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  mounted() {
    this.addUserList();
  },
  methods: {
    ...mapActions({
      addUserList: 'user/addUserList',
    }),
    editItem(item) {
      this.editedIndex = this.userList.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      const index = this.userList.indexOf(item);
      confirm(`确定要删除 ${item.username} ?`) && this.userList.splice(index, 1);
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
      this.$refs.editedItem.validate(async (valid) => {
        if (valid) {
          const _editedItem = {};
          _editedItem.real_name = this.editedItem.real_name;
          _editedItem.phone = this.editedItem.phone;
          _editedItem.lottery_num = this.editedItem.lottery_num;
          _editedItem.remarks = this.editedItem.remarks;
          if (this.editedIndex > -1) {
            // 编辑
            await this.$axios.$put(`/user/plain_user/${this.editedItem.id}`, qs.stringify(_editedItem));
          } else {
          }
          this.close();
          this.addUserList();
        }
      });
    },
    formatDate(dateTimeObj) {
      if (!dateTimeObj) {
        return '暂无';
      }
      dateTimeObj = new Date(dateTimeObj);
      return dateTimeObj
        ? `${dateTimeObj.getFullYear()}-${dateTimeObj.getMonth() +
        1}-${new Date().getDate()} ${dateTimeObj.getHours()}:${dateTimeObj.getMinutes()}:${dateTimeObj.getSeconds()}`
        : '暂无';
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
