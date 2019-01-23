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
            @click="addDialog = true">添加店长
          </v-btn>
          <v-card>
            <v-card-title>
              <span>店长扫描此二维码以添加</span>
              <v-spacer/>
            </v-card-title>
            <v-img
              src="http://upload.mnw.cn/2016/0126/1453768615784.jpg"
              contain/>
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
                  label-position="left">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="真实姓名"
                        prop="realName">
                        <el-input
                          v-model="editedItem.realName"/>
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
                        prop="shopName">
                        <el-select
                          v-model="editedItem.shopName"
                          filterable>
                          <el-option
                            v-for="item in shopNameOptions"
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
                        label="人员岗位"
                        prop="userIdentity">
                        <el-select v-model="editedItem.userIdentity">
                          <el-option
                            v-for="item in userIdentityOptions"
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
                        prop="userState">
                        <el-switch v-model="editedItem.userState"/>
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
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.username }}</td>
          <td class="text-xs-left">{{ props.item.realName ? props.item.realName : '暂无' }}</td>
          <td class="text-xs-left">{{ props.item.phone ? props.item.phone : '暂无' }}</td>
          <td class="text-xs-left">
            <v-avatar
              slot="activator"
              size="48px">
              <img
                :src="props.item.headImgUrl"
                alt="Avatar">
            </v-avatar>
          </td>
          <td class="text-xs-left">{{ props.item.openid }}</td>
          <td class="text-xs-left">{{ shopNameOptions.filter(e=>props.item.shopName ===e.value).length >=1 ? shopNameOptions.filter(e=>props.item.shopName ===e.value)[0].label : "暂无" }}</td>
          <td class="text-xs-left">{{ props.item.userState ? '启用' : '未启用' }}</td>
          <td class="text-xs-left">{{ props.item.userIdentity === 1 ? '老板' : '员工' }}</td>
          <td class="text-xs-left">{{ formatDate(props.item.createdAt) }}</td>
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
  export default {
    name: "Adminuser",
    layout: 'admin',
    data: () => ({
      valid: true,
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/admin'
        },
        {
          text: '商家人员管理',
          disabled: true,
          href: '/admin/shop/employee'
        },
      ],
      dialog: false,
      addDialog: false,
      headers: [
        {text: 'ID', align: 'left', sortable: true, value: 'id'},
        {text: '店员名称', align: 'left', value: 'username'},
        {text: '真实姓名', align: 'left', value: 'realName'},
        {text: '电话号码', align: 'left', value: 'phone'},
        {text: '店员头像', align: 'left', value: 'headImgUrl'},
        {text: 'openid', align: 'left', value: 'openid'},
        {text: '店名', align: 'left', value: 'shopName'},
        {text: '人员状态', align: 'left', value: 'userState'},
        {text: '人员岗位', align: 'left', value: 'userIdentity'},
        {text: '创建时间', align: 'left', value: 'createdAt'},
        {text: '备注', align: 'left', value: 'remarks'},
        {text: '操作', align: 'left', value: 'username', sortable: false},
      ],
      userList: [
        {
          id: 159,
          username: '店员名称',
          realName: null,
          phone: null,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 1,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 1,
          username: '店员名称',
          realName: null,
          phone: null,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 2,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 2,
          username: '店员名称',
          realName: null,
          phone: null,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 3,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 3,
          username: '店员名称',
          realName: null,
          phone: null,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 4,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 4,
          username: '店员名称',
          realName: null,
          phone: null,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 3,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 5,
          username: '店员名称',
          realName: "真实姓名",
          phone: 15235656565,
          headImgUrl: "https://randomuser.me/api/portraits/men/85.jpg",
          openid: "54AS56D465W4E65A4SD654",
          shopName: 2,
          userState: true,
          userIdentity: 1,
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
      ],
      rules: {
        realName: [
          {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        phone: [
          {
            pattern: /^(0|86|17951)?(13[0-9]|15[012356789]|166|17[3678]|18[0-9]|14[57])[0-9]{8}$/,
            message: '格式不正确',
            trigger: 'change'
          }
        ],
        remarks: [
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
      },
      editedIndex: -1,
      editedItem: {
        realName: '',
        phone: '',
        shopName: null,
        userIdentity: 1,
        userState: true,
        remarks: ''
      },
      defaultItem: {
        realName: '',
        phone: '',
        shopName: null,
        userIdentity: 1,
        userState: true,
        remarks: ''
      },
      search: '',
      userIdentityOptions: [
        {value: 1, label: '老板'},
        {value: 2, label: '店员'}
      ],
      shopNameOptions: [
        {value: 1, label: 'Google'},
        {value: 2, label: 'Microsoft'},
        {value: 3, label: 'ByteJump'},
        {value: 4, label: 'Alibaba'},
      ],
    }),
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? '添加店员' : '修改店员'
      }
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
    methods: {
      editItem(item) {
        this.editedIndex = this.userList.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true
      },
      deleteItem(item) {
        const index = this.userList.indexOf(item);
        confirm(`确定要删除 ${item.username} ?`) && this.userList.splice(index, 1)
      },
      close() {
        this.dialog = false;
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem);
          this.editedIndex = -1;
          this.$refs.editedItem.resetFields();
          this.valid = true;
        }, 100)
      },
      save() {
        this.$refs.editedItem.validate((valid) => {
          if (valid) {
            if (this.editedIndex > -1) {
              Object.assign(this.userList[this.editedIndex], this.editedItem)
            } else {
              this.userList.push(this.editedItem)
            }
            this.close()
          }
        });
      },
      formatDate(dateTimeObj) {
        return `${dateTimeObj.getFullYear()}-${dateTimeObj.getMonth() + 1}-${new Date().getDate()} ${dateTimeObj.getHours()}:${dateTimeObj.getMinutes()}:${dateTimeObj.getSeconds()}`;
      }
    },
  }
</script>

<style scoped lang="stylus">

</style>
