<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>商家管理</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical/>
        <v-spacer/>
        <v-dialog
          v-model="dialog"
          max-width="1200px">
          <v-btn
            slot="activator"
            color="primary"
            dark
            class="mb-2">添加商家
          </v-btn>
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
                  label-width="100px"
                  label-position="left">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="商家名称"
                        prop="shopName">
                        <el-input v-model="editedItem.shopName"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="商家地址"
                        prop="shopAddress">
                        <el-input
                          v-model="editedItem.shopAddress"
                          placeholder="城市名 (温州)"/>
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
                        label="合作日期"
                        prop="startDateTime">
                        <el-date-picker
                          v-model="editedItem.startDateTime"
                          type="datetime"
                          placeholder="选择日期时间"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="商家状态">
                        <el-switch v-model="editedItem.shopState"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="参加的活动"
                        required>
                        <el-select
                          v-model="editedItem.activity"
                          filterable>
                          <el-option
                            v-for="item in activityOptions"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"/>
                        </el-select>
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
        :items="shopList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.shopName }}</td>
          <td class="text-xs-left">{{ props.item.shopAddress }}</td>
          <td class="text-xs-left">{{ formatDate(props.item.startDateTime) }}</td>
          <td class="text-xs-left">{{ props.item.shopState ? '合作中' : '合作结束' }}</td>
          <td class="text-xs-left">{{ activityOptions.filter(e=>props.item.activity ===e.value).length >=1 ? activityOptions.filter(e=>props.item.activity ===e.value)[0].label : "暂无" }}</td>
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
            暂无商家 :(
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
    name: "AdminShop",
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
          text: '商家管理',
          disabled: true,
          href: '/admin/shop'
        },
      ],
      dialog: false,
      headers: [
        {text: 'ID', align: 'left', sortable: true, value: 'id'},
        {text: '商家名称', align: 'left', value: 'shopName'},
        {text: '商家地址', align: 'left', value: 'shopAddress'},
        {text: '合作时间', align: 'left', value: 'startDateTimeTime'},
        {text: '商家状态', align: 'left', value: 'shopState'},
        {text: '参与活动', align: 'left', value: 'activity'},
        {text: '备注', align: 'left', value: 'remarks'},
        {text: '操作', align: 'left', value: 'shopName', sortable: false},
      ],
      shopList: [
        {
          id: 159,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 1,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 1,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 2,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 2,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 3,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 3,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 2,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 4,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 1,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 5,
          shopName: '海底捞',
          shopAddress: '地址地址',
          startDateTime: new Date(),
          activity: 4,
          shopState: true,
          remarks: "备注备注备注备注备注备注"
        },
      ],
      activityOptions: [
        {value: 1, label: '活动1'},
        {value: 2, label: '活动2'},
        {value: 3, label: '活动3'},
        {value: 4, label: '活动4'},
      ],
      rules: {
        shopName: [
          {required: true, message: '请输入商家名称', trigger: 'blur'},
          {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
          {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        shopAddress: [
          {required: true, message: '请输入商家地址', trigger: 'blur'},
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        remarks: [
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        startDateTime: [
          {type: 'date', required: true, message: '请选择日期', trigger: 'blur'},
        ],
      },
      editedIndex: -1,
      editedItem: {
        shopName: '',
        shopAddress: '',
        startDateTime: '',
        activity: '',
        shopState: false,
        remarks: '',
      },
      defaultItem: {
        shopName: '',
        shopAddress: '',
        startDateTime: '',
        startTime: '',
        activity: '',
        shopState: false,
        remarks: ''
      },
      search: '',
    }),
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? '添加商家' : '修改商家'
      }
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
    methods: {
      editItem(item) {
        this.editedIndex = this.shopList.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true
      },
      deleteItem(item) {
        const index = this.shopList.indexOf(item);
        confirm(`确定要删除 ${item.shopName} ?`) && this.shopList.splice(index, 1)
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
              Object.assign(this.shopList[this.editedIndex], this.editedItem)
            } else {
              this.shopList.push(this.editedItem)
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
