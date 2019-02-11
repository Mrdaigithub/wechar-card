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
            dark>添加卡券
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
                  label-width="120px"
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="卡券名称"
                        prop="card_name">
                        <el-input v-model="editedItem.card_name"/>
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
                        label="卡券缩略图"
                        prop="card_thumbnail">
                        <el-input
                          v-model="editedItem.card_thumbnail"
                          placeholder="图片URL"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="中奖率"
                        prop="probability">
                        <el-input-number
                          v-model="editedItem.probability"
                          :min="0"
                          :max="100"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="有效日期">
                        <el-date-picker
                          v-model="editedItem.end_time_0"
                          :disabled="!!editedItem.end_time_1"
                          class="w100"
                          type="datetime"
                          placeholder="定期模式"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="倒计时">
                        <el-input-number
                          v-model="editedItem.end_time_1"
                          :min="0"
                          :disabled="!!editedItem.end_time_0"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="卡券状态">
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
        :items="cardModelList"
        :search="search"
        :filter="tableFilter"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item['card_name'] }}</td>
          <td class="text-xs-center">
            <v-img
              :src="props.item['card_thumbnail']"
              :lazy-src="props.item['cardThumbnail']"
              width="80px"/>
          </td>
          <td class="text-xs-center">{{ props.item.remarks ? props.item.remarks : '暂无' }}</td>
          <td class="text-xs-center">
            {{ props.item['end_time_0'] ? props.item['end_time_0'] : `${div(props.item['end_time_1'], 60)}分钟之后过期` }}
          </td>
          <td class="text-xs-center">{{ props.item.state ? '启用' : '未启用' }}</td>
          <td class="text-xs-center">{{ `${mul(props.item['probability'], 100)}%` }}</td>
          <td class="text-xs-center">
            <div v-if="props.item['activity_id_list'] && props.item['activity_id_list'].length">
              <v-btn
                v-for="item in props.item['activity_id_list']"
                :key="item"
                small
                flat
                @click="changePage(`/admin/activity?activity=${activityList.filter(v=>v.id===item)[0] ?
                activityList.filter(v=>v.id===item)[0]['activity_name'] : ''}`)">
                {{ activityList.filter(v=>v.id===item)[0] ? activityList.filter(v=>v.id===item)[0]['activity_name'] :
                '暂无' }}
              </v-btn>
            </div>
            <div v-else>未被使用</div>
          </td>
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
import {mapState, mapActions} from 'vuex';
import qs from 'qs';
import calc from 'calculatorjs';
import {Loading} from 'element-ui';

export default {
  name: 'AdminCard',
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
        text: '卡券管理',
        disabled: true,
        href: '/admin/card',
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '卡券名称', align: 'center', value: 'card_name'},
      {text: '卡券缩略图', align: 'center', value: 'card_thumbnail'},
      {text: '备注', align: 'center', value: 'remarks'},
      {text: '有效期截止', align: 'center', value: 'end_time_0'},
      {text: '启用状态', align: 'center', value: 'state'},
      {text: '中奖率', align: 'center', value: 'probability'},
      {text: '活动', align: 'center', value: 'activity_id_list'},
      {text: '操作', align: 'center', value: 'action', sortable: false},
    ],
    rules: {
      card_name: [
        {required: true, message: '请输入卡券名称', trigger: 'blur'},
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
        {min: 2, max: 20, message: '长度在 2 到 20 个字符', trigger: 'change'},
        {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      card_thumbnail: [
        {type: 'url', message: '卡券缩略图URL链接格式错误', trigger: 'change'},
      ],
      remarks: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
    },
    editedIndex: -1,
    editedItem: {
      card_name: '',
      card_thumbnail: '',
      remarks: '',
      end_time_0: null,
      end_time_1: null,
      probability: 0,
      state: false,
    },
    defaultItem: {
      card_name: '',
      card_thumbnail: '',
      remarks: '',
      end_time_0: null,
      end_time_1: null,
      probability: 0,
      state: false,
    },
    search: '',
  }),
  computed: {
    ...mapState({
      cardModelList: state => state.card.cardModelList ? state.card.cardModelList : [],
      activityList: state => state.activity.activityList ? state.activity.activityList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加卡券' : '修改卡券';
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  mounted() {
    Loading.service({fullscreen: true});
    if (this.$route.query.activity) {
      this.search = this.$route.query.activity;
    }
    this.addCardModelList();
    this.addActivityList(Loading.service({fullscreen: true}).close());
  },
  methods: {
    ...mapActions({
      addCardModelList: 'card/addCardModelList',
      addActivityList: 'activity/addActivityList',
    }),
    changePage(url) {
      this.$router.push(url);
    },
    tableFilter(val, search) {
      if (Object.prototype.toString.call(val) === '[object Array]') {
        return this.activityList.filter(item =>
          (item['activity_name'] === search)
          && val.indexOf(item.id) !== -1).length > 0;
      }
      if (!!val) {
        return val.toString().indexOf(search) !== -1;
      }
    },
    editItem(item) {
      const _item = JSON.parse(JSON.stringify(item));
      _item.state = _item.state === 1;
      _item.probability = _item.probability ? this.mul(_item.probability, 100) : null;
      _item.end_time_1 = _item.end_time_1 ? this.div(_item.end_time_1, 60) : null;
      this.editedIndex = this.cardModelList.indexOf(item);
      this.editedItem = Object.assign({}, _item);
      this.dialog = true;
    },
    async deleteItem(item) {
      if (confirm(`确定要删除 ${item.card_name} ?`)) {
        Loading.service({fullscreen: true});
        await this.$axios.$delete(`/card/${item.id}`);
        this.addCardModelList(Loading.service({fullscreen: true}).close());
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
          _editedItem.remarks = this.editedItem.remarks;
          _editedItem.probability = this.div(this.editedItem.probability, 100) || 0;
          _editedItem.state = this.editedItem.state ? 1 : 0;
          if (!!this.editedItem.card_name || this.editedItem.card_name !== '') {
            _editedItem.card_name = this.editedItem.card_name;
          }
          if (!!this.editedItem.card_thumbnail || this.editedItem.card_thumbnail !== '') {
            _editedItem.card_thumbnail = this.editedItem.card_thumbnail;
          }
          if (!!this.editedItem.end_time_0 && this.editedItem.end_time_0 !== '') {
            _editedItem.end_time_0 = this.editedItem.end_time_0;
          } else {
            _editedItem.end_time_1 = this.mul(this.editedItem.end_time_1, 60) || 0;
          }

          Loading.service({fullscreen: true});
          if (this.editedIndex > -1) {
            // 编辑
            await this.$axios.$put(`/card/${this.editedItem.id}`, qs.stringify(_editedItem));
          } else {
            // 新建
            await this.$axios.$post(`/card`, qs.stringify(_editedItem));
          }
          this.close();
          this.addCardModelList(Loading.service({fullscreen: true}).close());
        }
      });
    },
    mul(num1, num2) {
      return calc.mul(num1, num2);
    },
    div(num1, num2) {
      return calc.div(num1, num2);
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
