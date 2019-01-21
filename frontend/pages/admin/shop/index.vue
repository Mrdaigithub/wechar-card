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
            class="mb-2">添加新商家
          </v-btn>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <v-form
                  ref="form"
                  v-model="valid"
                  lazy-validation>
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <v-text-field
                        v-model="editedItem.shopName"
                        :rules="rules.nameRules"
                        append-icon="shopping_cart"
                        label="商家名称"/>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <v-text-field
                        v-model="editedItem.shopAddress"
                        :rules="rules.locationRules"
                        append-icon="gps_fixed"
                        label="商家地址"/>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <v-menu
                        ref="dateMenu"
                        :close-on-content-click="false"
                        v-model="dateMenu"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px">
                        <v-text-field
                          slot="activator"
                          :rules="rules.dateRules"
                          v-model="editedItem.startDate"
                          label="合作时间(日期)"
                          append-icon="event"
                          readonly/>
                        <v-date-picker
                          ref="picker"
                          v-model="editedItem.startDate"
                          locale="zh-cn"
                          min="1950-01-01"
                          @change="saveDate(editedItem.startDate)"/>
                      </v-menu>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <v-menu
                        ref="timeMenu"
                        :close-on-content-click="false"
                        v-model="timeMenu"
                        :nudge-right="40"
                        :return-value.sync="editedItem.startTime"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        max-width="290px"
                        min-width="290px">
                        <v-text-field
                          slot="activator"
                          :rules="rules.timeRules"
                          v-model="editedItem.startTime"
                          label="合作时间(时刻)"
                          append-icon="access_time"
                          readonly/>
                        <v-time-picker
                          v-if="timeMenu"
                          v-model="editedItem.startTime"
                          locale="zh-cn"
                          full-width
                          @change="$refs.timeMenu.save(editedItem.startTime)"/>
                      </v-menu>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <v-text-field
                        v-model="editedItem.remarks"
                        append-icon="book"
                        label="备注"/>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <v-switch
                        v-model="editedItem.shopState"
                        label="商家状态"/>
                    </v-flex>
                  </v-layout>
                </v-form>
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
          <td class="text-xs-left">{{ `${props.item.startDate} -- ${props.item.startTime}` }}</td>
          <td class="text-xs-left">{{ props.item.shopState ? '合作中' : '合作结束' }}</td>
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
import rules from '~/utils/rules'

export default {
  name: "AdminShop",
  layout: 'admin',
  data: () => ({
    rules,
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
      {text: '合作时间', align: 'left', value: 'createdTime'},
      {text: '商家状态', align: 'left', value: 'shopState'},
      {text: '备注', align: 'left', value: 'remarks'},
      {text: '操作', align: 'left', value: 'shopName', sortable: false},
    ],
    shopList: [
      {
        id: 159,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 1,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 2,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 3,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 4,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 5,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 6,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
      {
        id: 7,
        shopName: '海底捞',
        shopAddress: "地址地址",
        startDate: "2018-12-31",
        startTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
    ],
    editedIndex: -1,
    editedItem: {
      shopName: '',
      shopAddress: '',
      startDate: '',
      startTime: '',
      shopState: false,
      remarks: '',
    },
    defaultItem: {
      name: '',
      shopAddress: '',
      startDate: '',
      startTime: '',
      shopState: false,
      remarks: ''
    },
    date: null,
    dateMenu: false,
    timeMenu: false,
    search: '',
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? '添加新商家' : '修改商家'
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
        this.editedIndex = -1
      }, 300);
      this.$refs.form.reset();
      this.valid = true;
    },
    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.shopList[this.editedIndex], this.editedItem)
      } else {
        this.shopList.push(this.editedItem)
      }
      this.close()
    },
    saveDate(date) {
      this.$refs.dateMenu.save(date)
    },
  },
}
</script>

<style scoped lang="stylus">

</style>
