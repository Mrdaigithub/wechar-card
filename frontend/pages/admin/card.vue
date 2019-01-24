<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>卡券管理</v-toolbar-title>
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
            class="mb-2">添加卡券
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
                  label-width="90px"
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="卡券名称"
                        prop="cardName">
                        <el-input v-model="editedItem.cardName"/>
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
                        label="有效日期">
                        <el-date-picker
                          v-model="editedItem.startDateTime"
                          class="w100"
                          type="datetime"
                          placeholder="起 不填为倒计时模式"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="有效日期"
                        prop="endDateTime">
                        <el-date-picker
                          v-model="editedItem.endDateTime"
                          class="w100"
                          type="datetime"
                          placeholder="终"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="中奖率"
                        prop="cardProbability">
                        <el-input-number
                          v-model="editedItem.cardProbability"
                          :min="0"
                          :max="100"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="卡券状态">
                        <el-switch v-model="editedItem.cardState"/>
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
        :items="cardList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.cardName }}</td>
          <td class="text-xs-left">
            <v-img
              :src="props.item.cardThumbnail"
              :lazy-src="props.item.cardThumbnail"
              width="80px"/>
          </td>
          <td class="text-xs-left">{{ props.item.remarks }}</td>
          <td class="text-xs-left">{{ props.item.startDateTime ? `${formatDate(props.item.startDateTime)} -
            ${formatDate(props.item.endDateTime)}` : `${formatDate(props.item.endDateTime)}` }}
          </td>
          <td class="text-xs-left">{{ props.item.cardState ? "有效" : "已失效" }}</td>
          <td class="text-xs-left">{{ `${props.item.cardProbability}%` }}</td>
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
    layout: "admin",
    data: () => ({
      valid: true,
      breadcrumbList: [
        {
          text: "主页",
          disabled: false,
          href: "/admin"
        },
        {
          text: "卡券管理",
          disabled: true,
          href: "/admin/card"
        },
      ],
      dialog: false,
      headers: [
        {text: "ID", align: "left", sortable: true, value: "id"},
        {text: "卡券名称", align: "left", value: "cardName"},
        {text: "卡券缩略图", align: "left", value: "cardThumbnail"},
        {text: "备注", align: "left", value: "remarks"},
        {text: "有效期截止", align: "left", value: "endDateTime"},
        {text: "卡券状态", align: "left", value: "cardState"},
        {text: "中奖率", align: "left", value: "cardProbability"},
        {text: "操作", align: "left", value: "cardName", sortable: false},
      ],
      cardList: [
        {
          id: 159,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
        {
          id: 1,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
        {
          id: 2,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
        {
          id: 3,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
        {
          id: 4,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
        {
          id: 5,
          cardName: "卡券名称",
          cardThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          remarks: "备注",
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          cardState: true,
          cardProbability: 10
        },
      ],
      rules: {
        cardName: [
          {required: true, message: "请输入卡券名称", trigger: "blur"},
          {min: 2, max: 20, message: "长度在 2 到 20 个字符", trigger: "change"},
          {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: "请不要包含特殊字符", trigger: "change"}
        ],
        remarks: [
          {type: "string", pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: "请不要包含特殊字符", trigger: "change"}
        ],
        endDateTime: [
          {type: "date", required: true, message: "请选择日期", trigger: "blur"},
        ]
      },
      editedIndex: -1,
      editedItem: {
        cardName: "",
        remarks: "",
        startDateTime: "",
        endDateTime: "",
        cardProbability: 0,
        cardState: false,
      },
      defaultItem: {
        cardName: "",
        remarks: "",
        startDateTime: "",
        endDateTime: "",
        cardProbability: 0,
        cardState: false,
      },
      search: "",
    }),
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? "添加卡券" : "修改卡券"
      }
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
    methods: {
      editItem(item) {
        this.editedIndex = this.cardList.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true
      },
      deleteItem(item) {
        const index = this.cardList.indexOf(item);
        confirm(`确定要删除 ${item.cardName} ?`) && this.cardList.splice(index, 1)
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
          console.log(this.$refs.editedItem);
          if (valid) {
            if (this.editedIndex > -1) {
              Object.assign(this.cardList[this.editedIndex], this.editedItem)
            } else {
              this.cardList.push(JSON.parse(JSON.stringify(this.editedItem)))
            }
            this.close()
          }
        });
      },
      formatDate(dateTimeObj) {
        if (!dateTimeObj) {
          return "暂无";
        }
        if (!(dateTimeObj instanceof Date)) {
          dateTimeObj = new Date(dateTimeObj);
        }
        return dateTimeObj ? `${dateTimeObj.getFullYear()}-${dateTimeObj.getMonth() + 1}-${new Date().getDate()} ${dateTimeObj.getHours()}:${dateTimeObj.getMinutes()}:${dateTimeObj.getSeconds()}` : "暂无";
      }
    },
  }
</script>

<style scoped lang="stylus">

</style>
