<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>商家人员管理</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical/>
        <v-spacer/>
        <v-dialog
          v-model="addDialog"
          persistent
          max-width="300px">
          <v-btn
            slot="activator"
            color="primary"
            dark
            @click="addItem">添加店长
          </v-btn>
          <v-card>
            <v-card-title>
              <span>店长扫描此二维码以添加</span>
              <v-spacer/>
            </v-card-title>
            <v-content style="height: 300px">
              <v-img
                v-if="addBossQrCodeBase64"
                :src="`data:image/png;base64,${addBossQrCodeBase64}`"
                alt=""/>
            </v-content>
            <v-card-actions>
              <v-btn
                color="primary"
                flat
                @click="addDialog = false">关闭
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
                          v-model="editedItem.phone"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="店名"
                        prop="shop_name">
                        <el-select
                          v-model="editedItem.shop_id"
                          class="w100"
                          filterable>
                          <el-option
                            v-for="item in shopList"
                            :key="item.id"
                            :label="item['shop_name']"
                            :value="item.id"/>
                        </el-select>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="人员岗位"
                        prop="identity">
                        <el-select
                          v-model="editedItem.identity"
                          class="w100">
                          <el-option
                            v-for="item in identityOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"/>
                        </el-select>
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
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="人员状态"
                        prop="state">
                        <el-switch v-model="editedItem.state"/>
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
        :items="shopEmployeeList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item.username }}</td>
          <td class="text-xs-center">{{ props.item['real_name'] ? props.item['real_name'] : '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.phone ? props.item.phone : '暂无' }}</td>
          <td class="text-xs-center">
            <v-avatar
              slot="activator"
              size="48px">
              <v-img
                :src="props.item['head_img_url']"
                alt="Avatar"/>
            </v-avatar>
          </td>
          <td class="text-xs-center">{{ props.item['openid'] }}</td>
          <td class="text-xs-center">
            <v-btn
              v-if="!!props.item.shop_id && shopList.filter(e=>props.item.shop_id ===e.id).length >=1"
              small
              flat
              @click="changePage(`/admin/shop?shop=${shopList.filter(e=>props.item.shop_id ===e.id)[0]['shop_name']}`)">
              {{ shopList.filter(e=>props.item.shop_id ===e.id)[0]['shop_name'] }}
            </v-btn>
            <div v-else>暂无</div>
          </td>
          <td class="text-xs-center">{{ props.item.state ? '启用' : '未启用' }}</td>
          <td class="text-xs-center">{{ props.item.identity === 1 ? '老板' : '员工' }}</td>
          <td class="text-xs-center">{{ props.item['created_at'] || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.remarks || '暂无' }}</td>
          <td class="text-xs-center">
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
            暂无店员 :(
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
import {Message, Loading} from 'element-ui';

export default {
  name: 'AdminShopEmployee',
  layout: 'admin',
  data: () => ({
    addBossQrCodeBase64: '',
    valid: true,
    breadcrumbList: [
      {
        text: '主页',
        disabled: false,
        href: '/admin',
      },
      {
        text: '商家人员管理',
        disabled: true,
        href: '/admin/shop/employee',
      },
    ],
    dialog: false,
    addDialog: false,
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '店员名称', align: 'center', value: 'username'},
      {text: '真实姓名', align: 'center', value: 'real_name'},
      {text: '电话号码', align: 'center', value: 'phone'},
      {text: '店员头像', align: 'center', value: 'head_img_url'},
      {text: 'openid', align: 'center', value: 'openid'},
      {text: '店名', align: 'center', value: 'shop_name'},
      {text: '人员状态', align: 'center', value: 'state'},
      {text: '人员岗位', align: 'center', value: 'identity'},
      {text: '创建时间', align: 'center', value: 'created_at'},
      {text: '备注', align: 'center', value: 'remarks'},
      {text: '操作', align: 'center', value: 'action', sortable: false},
    ],
    rules: {
      real_name: [
        {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
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
      shop_id: null,
      identity: 1,
      state: true,
      remarks: '',
    },
    defaultItem: {
      real_name: '',
      phone: '',
      shop_id: null,
      identity: 1,
      state: true,
      remarks: '',
    },
    search: '',
    identityOptions: [
      {value: 1, label: '老板'},
      {value: 2, label: '店员'},
    ],
  }),
  computed: {
    ...mapState({
      shopEmployeeList: state => state.user.shopEmployeeList ? state.user.shopEmployeeList : [],
      shopList: state => state.shop.shopList ? state.shop.shopList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加店员' : '修改店员';
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  mounted() {
    this.addShopEmployeeList();
    this.addShopList();
  },
  methods: {
    ...mapActions({
      addShopEmployeeList: 'user/addShopEmployeeList',
      addShopList: 'shop/addShopList',
    }),
    changePage(url) {
      this.$router.push(url);
    },
    async addItem() {
      Loading.service({fullscreen: true});
      this.addDialog = true;
      const {data} = await this.$axios.$get(`/qrcode/add/boss`);
      this.addBossQrCodeBase64 = data;
      Loading.service({fullscreen: true}).close();
      window.Echo.channel('publicChannel').listen('MessageEvent', async (e) => {
        if (e.message && JSON.parse(e.message).signal === 'allowAddBoss') {
          Message.success('添加成功');
          this.addDialog = false;
          this.addShopEmployeeList();
        }
      });
    },
    editItem(item) {
      const _item = JSON.parse(JSON.stringify(item));
      _item.state = _item.state === 1;
      this.editedIndex = this.shopEmployeeList.indexOf(item);
      this.editedItem = Object.assign({}, _item);
      this.dialog = true;
    },
    async deleteItem(item) {
      if (confirm(`确定要删除 ${item.identity === 1 ? '老板' : '员工'}--${item.username} ?`)) {
        Loading.service({fullscreen: true});
        await this.$axios.$delete(`/user/shop/${item.id}`);
        this.addShopEmployeeList();
        this.addShopList(Loading.service({fullscreen: true}).close());
      }
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
          if (!!this.editedItem.real_name) {
            _editedItem.real_name = this.editedItem.real_name;
          }
          if (!!this.editedItem.phone) {
            _editedItem.phone = this.editedItem.phone;
          }
          if (!!this.editedItem.identity) {
            _editedItem.identity = this.editedItem.identity;
          }
          _editedItem.shop_id = this.editedItem.shop_id;
          _editedItem.state = this.editedItem.state ? 1 : 0;
          _editedItem.remarks = this.editedItem.remarks;

          Loading.service({fullscreen: true});
          if (this.editedIndex > -1) {
            await this.$axios.$put(`/user/shop/${this.editedItem.id}`, qs.stringify(_editedItem));
          } else {
          }
          this.close();
          this.addShopEmployeeList(Loading.service({fullscreen: true}).close());
        }
      });
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
