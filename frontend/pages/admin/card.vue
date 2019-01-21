<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>奖品卡券管理</v-toolbar-title>
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
            class="mb-2">添加新卡券
          </v-btn>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex
                    xs12
                    sm6
                    md4>
                    <v-text-field
                      v-model="editedItem.shopName"
                      label="卡券名称"/>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6
                    md4>
                    <v-menu
                      ref="menu"
                      :close-on-content-click="false"
                      v-model="menu"
                      :nudge-right="40"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px">
                      <v-text-field
                        slot="activator"
                        v-model="editedItem.createdTime"
                        label="有效期"
                        prepend-icon="event"
                        readonly/>
                      <v-date-picker
                        ref="picker"
                        v-model="editedItem.createdTime"
                        :max="new Date().toISOString().substr(0, 10)"
                        locale="zh-cn"
                        min="1950-01-01"
                        @change="saveDate(editedItem.createdTime)"/>
                    </v-menu>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6
                    md4>
                    <v-text-field
                      v-model="editedItem.remarks"
                      label="备注"/>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6
                    md4>
                    <v-switch
                      v-model="editedItem.shopState"
                      label="卡券状态"/>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
            <v-card-actions>
              <v-spacer/>
              <v-btn
                flat
                @click="close">关闭
              </v-btn>
              <v-btn
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
          <td class="text-xs-left">
            {{ props.item.CountdownMode ? `${props.item.endDate} ${props.item.endTime} 失效` :
            `${props.item.startDate} ${props.item.startTime} -- ${props.item.endDate} ${props.item.endTime}` }}
          </td>
          <td class="text-xs-left">{{ props.item.shopState ? '启用' : '停用' }}</td>
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
            暂无卡券 :(
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
  name: "AdminCard",
  layout: 'admin',
  data: () => ({
    breadcrumbList: [
      {
        text: '主页',
        disabled: false,
        href: '/admin'
      },
      {
        text: '奖品卡券管理',
        disabled: true,
        href: '/admin/card'
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'left', sortable: true, value: 'id'},
      {text: '卡券名称', align: 'left', value: 'shopName'},
      {text: '有效期', align: 'left', value: 'createdTime'},
      {text: '卡券状态', align: 'left', value: 'shopState'},
      {text: '备注', align: 'left', value: 'remarks'},
      {text: '操作', align: 'left', value: 'shopName', sortable: false},
    ],
    shopList: [
      {
        id: 159,
        shopName: '海底捞',
        shopAddress: "地址地址",
        CountdownMode: true,
        startDate: "2018-12-20",
        endDate: "2018-12-31",
        startTime: "12:30:30",
        endTime: "12:30:30",
        shopState: true,
        remarks: "备注备注备注备注备注备注"
      },
    ],
    editedIndex: -1,
    editedItem: {
      shopName: '',
      createdTime: '',
      shopState: false,
      remarks: '',
    },
    defaultItem: {
      name: '',
      createdTime: '',
      shopState: false,
      remarks: ''
    },
    date: null,
    menu: false,
    search: '',
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? '添加新卡券' : '修改卡券'
    }
  },
  watch: {
    dialog(val) {
      val || this.close()
    },
    menu(val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
    }
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
      }, 300)
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
      this.$refs.menu.save(date)
    }
  },
}
</script>

<style scoped>

</style>
