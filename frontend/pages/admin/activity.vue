<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-toolbar
        flat
        color="white">
        <v-toolbar-title>抽奖活动管理</v-toolbar-title>
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
            class="mb-2">添加活动
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
                  label-width="80px"
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动名称"
                        prop="activityName">
                        <el-input v-model="editedItem.activityName"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动地址"
                        prop="activityAddress">
                        <el-input
                          v-model="editedItem.activityAddress"
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
                        label="使用日期"
                        prop="startDateTime">
                        <el-date-picker
                          v-model="editedItem.startDateTime"
                          class="w100"
                          type="datetime"
                          placeholder="选择日期时间"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动卡券">
                        <el-select
                          v-model="value5"
                          class="w100"
                          multiple
                          placeholder="请选择">
                          <el-option
                            v-for="item in cardOptions"
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
                        label="缩略图"
                        prop="activityThumbnail">
                        <el-upload
                          :on-preview="handlePreview"
                          :on-remove="handleRemove"
                          :before-remove="beforeRemove"
                          :limit="1"
                          :file-list="fileList"
                          class="upload-demo"
                          action="https://jsonplaceholder.typicode.com/posts/"
                          multiple>
                          <el-button 
                            size="small" 
                            type="primary">点击上传</el-button>
                          <div 
                            slot="tip" 
                            class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
                        </el-upload>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="活动状态">
                        <el-switch v-model="editedItem.activityState"/>
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
        :items="activityList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td>{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.activityName }}</td>
          <td class="text-xs-left">{{ props.item.activityDescription }}</td>
          <td class="text-xs-left">
            <v-img
              :src="props.item.activityThumbnail"
              :lazy-src="props.item.activityThumbnail"/>
          </td>
          <td class="text-xs-left">
            <v-btn
              color="info"
              @click="activityProbabilityDialog=true">查看
            </v-btn>
          </td>
          <td class="text-xs-left">{{ formatDate(props.item.createdAt) }}</td>
          <td class="text-xs-left">{{ props.item.activityState ? '使用中' : '使用结束' }}</td>
          <td class="text-xs-left">{{ props.item.activityNum }}</td>
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
            暂无活动 :(
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
      <v-dialog
        v-model="activityProbabilityDialog"
        max-width="1200px">
        <v-card>
          <v-card-text>
            <v-container grid-list-md>
              <v-layout
                row
                wrap>
                <v-flex
                  v-for="(item, index) of cardList"
                  :key="index"
                  xs12
                  sm6
                  md6
                  lg4>
                  <v-card color="white">
                    <v-layout row>
                      <v-flex xs7>
                        <v-card-title primary-name>
                          <div>
                            <div class="headline">{{ item.cardName }}</div>
                            <v-tooltip top>
                              <span slot="activator">{{ item.cardDescription }}</span>
                              <span>卡券详情</span>
                            </v-tooltip>
                          </div>
                        </v-card-title>
                      </v-flex>
                      <v-flex xs5>
                        <v-img
                          src="https://cdn.vuetifyjs.com/images/cards/halcyon.png"
                          height="125px"
                          contain
                        />
                      </v-flex>
                    </v-layout>
                    <v-divider light/>
                    <v-card-actions class="pa-3">
                      <v-tooltip top>
                        <div slot="activator">
                          {{ item.startDateTime ? `${formatDate(item.startDateTime)} -
                          ${formatDate(item.endDateTime)}` : `${formatDate(item.endDateTime)}` }}
                        </div>
                        <span>有效期</span>
                      </v-tooltip>
                    </v-card-actions>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer/>
            <v-btn
              flat
              @click="activityProbabilityDialog=false">关闭
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card>
  </div>
</template>

<script>
  export default {
    name: "AdminActivity",
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
          text: '抽奖活动管理',
          disabled: true,
          href: '/admin/activity'
        },
      ],
      dialog: false,
      activityProbabilityDialog: false,
      headers: [
        {text: 'ID', align: 'left', sortable: true, value: 'id'},
        {text: '活动名称', align: 'left', value: 'activityName'},
        {text: '活动描述', align: 'left', value: 'activityDescription'},
        {text: '活动缩略图', align: 'left', value: 'activityThumbnail'},
        {text: '当前卡券', align: 'left', value: 'activityProbability'},
        {text: '添加时间', align: 'left', value: 'createdAt'},
        {text: '活动状态', align: 'left', value: 'activityState'},
        {text: '参与人数', align: 'left', value: 'activityNum'},
        {text: '备注', align: 'left', value: 'remarks'},
        {text: '操作', align: 'left', value: 'id', sortable: false},
      ],
      activityList: [
        {
          id: 159,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          selectedCardList: [],
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 1,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 2,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 3,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 4,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 5,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
        {
          id: 6,
          activityName: '海底捞',
          activityDescription: '活动描述活动描述活动描述',
          activityThumbnail: "https://randomuser.me/api/portraits/men/85.jpg",
          activityProbability: null,
          createdAt: new Date(),
          activityState: true,
          activityNum: 100,
          remarks: "备注备注备注备注备注备注"
        },
      ],
      rules: {
        activityName: [
          {required: true, message: '请输入活动名称', trigger: 'blur'},
          {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
          {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        activityAddress: [
          {required: true, message: '请输入活动地址', trigger: 'blur'},
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        remarks: [
          {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'}
        ],
        startDateTime: [
          {type: 'date', required: true, message: '请选择日期', trigger: 'blur'},
        ],
        activityProbability: [
          {required: true, message: '请输入卡券中奖概率', trigger: 'blur'},
          {type: 'number', message: '卡券中奖概率必须为数字值'}
        ]
      },
      cardModelList: [
        {
          id: 1,
          cardName: '卡券1',
          cardDescription: '卡券详情1卡券详情1卡券详情1卡券详情1卡券详情1',
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          id: 2,
          cardName: '卡券1',
          cardDescription: '卡券详情1卡券详情1卡券详情1卡券详情1卡券详情1',
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          id: 3,
          cardName: '卡券1',
          cardDescription: '卡券详情1卡券详情1卡券详情1卡券详情1卡券详情1',
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          id: 4,
          cardName: '卡券1',
          cardDescription: '卡券详情1卡券详情1卡券详情1卡券详情1卡券详情1',
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          id: 5,
          cardName: '卡券1',
          cardDescription: '卡券详情1卡券详情1卡券详情1卡券详情1卡券详情1',
          startDateTime: null,
          endDateTime: new Date(new Date().getTime() + 1000000000),
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
      ],
      editedIndex: -1,
      editedItem: {
        activityName: '',
        activityAddress: '',
        startDateTime: '',
        activityState: false,
        selectedCardOptions: [],
        activityThumbnail: null,
        remarks: '',
      },
      defaultItem: {
        activityName: '',
        activityAddress: '',
        startDateTime: '',
        startTime: '',
        activityState: false,
        selectedCardOptions: [],
        activityThumbnail: null,
        remarks: ''
      },
      search: '',
      
      cardOptions: [{
        value: '1',
        label: '卡券1'
      }, {
        value: '2',
        label: '卡券2'
      }, {
        value: '3',
        label: '卡券2'
      }, {
        value: '4',
        label: '卡券4'
      }, {
        value: '5',
        label: '卡券5'
      }],
      value5: [],
    }),
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? '添加活动' : '修改活动'
      }
    },
    watch: {
      dialog(val) {
        val || this.close()
      },
    },
    methods: {
      editItem(item) {
        this.editedIndex = this.activityList.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true
      },
      deleteItem(item) {
        const index = this.activityList.indexOf(item);
        confirm(`确定要删除 ${item.activityName} ?`) && this.activityList.splice(index, 1)
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
              Object.assign(this.activityList[this.editedIndex], this.editedItem)
            } else {
              this.activityList.push(JSON.parse(JSON.stringify(this.editedItem)))
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
