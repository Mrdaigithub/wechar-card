<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>中奖记录管理</v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical/>
        <v-spacer/>
        <v-dialog
          v-model="dialog"
          max-width="1200px">
          <!--<v-btn-->
          <!--slot="activator"-->
          <!--color="primary"-->
          <!--dark-->
          <!--class="mb-2">添加中奖记录-->
          <!--</v-btn>-->
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
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="核销状态">
                        <el-switch v-model="editedItem.writeOff"/>
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
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.shopName }}</td>
          <td class="text-xs-left">{{ props.item.activityName }}</td>
          <td class="text-xs-left">{{ props.item.cardName }}</td>
          <td class="text-xs-left">{{ props.item.username }}</td>
          <td class="text-xs-left">{{ props.item.realName }}</td>
          <td class="text-xs-left">{{ props.item.phone }}</td>
          <td class="text-xs-left">
            <v-avatar
              slot="activator"
              size="48px">
              <img
                :src="props.item.headImgUrl"
                alt="Avatar">
            </v-avatar>
          </td>
          <td class="text-xs-left">{{ props.item.gps }}</td>
          <td class="text-xs-left">{{ formatDate(props.item.createdAt) }}</td>
          <td class="text-xs-left">{{ props.item.writeOff ? '已核销' : '未核销' }}</td>
          <td class="text-xs-left">{{ formatDate(props.item.writeOffDate) }}</td>
          <td class="text-xs-left">{{ props.item.remarks }}</td>
          <td class="justify-center layout px-0">
            <v-icon
              small
              class="mr-2"
              @click="editItem(props.item)">
              edit
            </v-icon>
            <!--<v-icon-->
            <!--small-->
            <!--@click="deleteItem(props.item)">-->
            <!--delete-->
            <!--</v-icon>-->
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
    name: "AdminLogWinning",
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
          text: '中奖记录管理',
          disabled: true,
          href: '/admin/log/winning'
        },
      ],
      dialog: false,
      headers: [
        {text: 'ID', align: 'left', sortable: true, value: 'id'},
        {text: '商家名称', align: 'left', value: 'shopName'},
        {text: '活动名称', align: 'left', value: 'activityName'},
        {text: '卡卷名称', align: 'left', value: 'cardName'},
        {text: '用户名', align: 'left', value: 'username'},
        {text: '真实姓名', align: 'left', value: 'realName'},
        {text: '手机号', align: 'left', value: 'phone'},
        {text: '用户头像', align: 'left', value: 'headImgUrl'},
        {text: '用户位置', align: 'left', value: 'gps'},
        {text: '中奖时间', align: 'left', value: 'createdAt'},
        {text: '核销状态', align: 'left', value: 'writeOff'},
        {text: '核销时间', align: 'left', value: 'writeOffDate'},
        {text: '备注', align: 'left', value: 'remarks'},
        {text: '操作', align: 'left', value: 'shopName', sortable: false},
      ],
      winningLogList: [
        {
          id: 159,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 1,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 2,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 3,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 4,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 5,
          shopName: '商家名称',
          activityName: '活动名称',
          cardName: '卡卷名称',
          username: '用户名',
          realName: '真实姓名',
          phone: 15365658956,
          headImgUrl: 'https://randomuser.me/api/portraits/men/85.jpg',
          gps: '温州',
          writeOff: true,
          writeOffDate: new Date(),
          createdAt: new Date(),
          remarks: "备注备注备注备注备注备注"
        },
      ],
      rules: {
        remarks: [
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
      },
      editedIndex: -1,
      editedItem: {
        writeOff: false,
        remarks: '',
      },
      defaultItem: {
        writeOff: false,
        remarks: ''
      },
      search: '',
    }),
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? '添加中奖记录' : '修改中奖记录'
      }
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
    methods: {
      editItem(item) {
        this.editedIndex = this.winningLogList.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true
      },
      deleteItem(item) {
        const index = this.winningLogList.indexOf(item);
        confirm(`确定要删除 ${item.shopName} ?`) && this.winningLogList.splice(index, 1)
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
              Object.assign(this.winningLogList[this.editedIndex], this.editedItem)
            } else {
              this.winningLogList.push(JSON.parse(JSON.stringify(this.editedItem)))
            }
            this.close()
          }
        });
      },
      formatDate(dateTimeObj) {
        if (!dateTimeObj) {
          return '暂无';
        }
        dateTimeObj = new Date(dateTimeObj);
        return dateTimeObj ? `${dateTimeObj.getFullYear()}-${dateTimeObj.getMonth() + 1}-${new Date().getDate()} ${dateTimeObj.getHours()}:${dateTimeObj.getMinutes()}:${dateTimeObj.getSeconds()}` : '暂无';
      }
    },
  }
</script>

<style scoped lang="stylus">

</style>
