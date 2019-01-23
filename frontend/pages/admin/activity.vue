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
                  label-position="left">
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
                          type="datetime"
                          placeholder="选择日期时间"/>
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
          <v-card-title>
            <span class="headline">当前卡券中奖率</span>
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
                      label="活动名称"
                      prop="activityProbability">
                      <el-input
                        v-model="editedItem.activityName"
                        placeholder="请输入内容">
                        <template slot="append">%</template>
                      </el-input>
                    </el-form-item>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6>
                    <el-form-item
                      label="活动名称"
                      prop="activityProbability">
                      <el-input
                        v-model="editedItem.activityName"
                        placeholder="请输入内容">
                        <template slot="append">%</template>
                      </el-input>
                    </el-form-item>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6>
                    <el-form-item
                      label="活动名称"
                      prop="activityProbability">
                      <el-input
                        v-model="editedItem.activityName"
                        placeholder="请输入内容">
                        <template slot="append">%</template>
                      </el-input>
                    </el-form-item>
                  </v-flex>
                  <v-flex
                    xs12
                    sm6>
                    <el-form-item
                      label="活动名称"
                      prop="activityProbability">
                      <el-input
                        v-model="editedItem.activityName"
                        placeholder="请输入内容">
                        <template slot="append">%</template>
                      </el-input>
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
        {text: '当前卡券中奖率', align: 'left', value: 'activityProbability'},
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
      editedIndex: -1,
      editedItem: {
        activityName: '',
        activityAddress: '',
        startDateTime: '',
        activityState: false,
        remarks: '',
      },
      defaultItem: {
        activityName: '',
        activityAddress: '',
        startDateTime: '',
        startTime: '',
        activityState: false,
        remarks: ''
      },
      search: '',
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
              this.activityList.push(this.editedItem)
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
