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
                  label-width="100px"
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动名称"
                        prop="activity_name">
                        <el-input v-model="editedItem.activity_name"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动详情"
                        prop="activity_description">
                        <el-input v-model="editedItem.activity_description"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="活动缩略图"
                        prop="activity_thumbnail">
                        <el-input
                          v-model="editedItem.activity_thumbnail"
                          placeholder="图片URL"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="回复关键词"
                        prop="reply_keyword">
                        <el-input v-model="editedItem.reply_keyword"/>
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
                        label="活动卡券">
                        <el-select
                          v-model="editedItem.card_model_id_list"
                          :multiple-limit="8"
                          class="w100"
                          multiple
                          filterable>
                          <el-option
                            v-for="item in cardModelList"
                            :key="item.id"
                            :label="item.card_name"
                            :value="item.id"/>
                        </el-select>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="活动状态">
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
        :items="activityList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item.activity_name }}</td>
          <td class="text-xs-center">{{ props.item.activity_description }}</td>
          <td class="text-xs-center">
            <v-img
              :src="props.item.activity_thumbnail"
              :lazy-src="props.item.activity_thumbnail"/>
          </td>
          <td class="text-xs-center">{{ props.item.reply_keyword }}</td>
          <td class="text-xs-center">{{ props.item.state ? '使用中' : '使用结束' }}</td>
          <td class="text-xs-center">{{ props.item['customer_num'] }}</td>
          <td class="text-xs-center">{{ props.item.remarks || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item['created_at'] }}</td>
          <td class="text-xs-center">
            <v-btn
              v-if="!!props.item['card_model_id_list'].length"
              small
              flat
              @click="changePage(`/admin/card?activity=${props.item.activity_name}`)">查看
            </v-btn>
            <div v-if="!props.item['card_model_id_list'].length">暂无</div>
          </td>
          <td class="text-xs-center">
            <v-btn
              v-if="!!props.item['shop_id'] && shopList.length"
              small
              flat
              @click="changePage(`/admin/shop?shop=${shopList.filter(item=>item.id===props.item['shop_id'])[0]['shop_name']}`)">
              {{ shopList.filter(item=>item.id===props.item['shop_id'])[0]['shop_name'] }}
            </v-btn>
            <div v-else>未被使用</div>
          </td>
          <td class="text-xs-center">
            <v-icon
              small
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
    </v-card>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import qs from 'qs';
import {Loading} from 'element-ui';

export default {
  name: 'AdminActivity',
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
        text: '抽奖活动管理',
        disabled: true,
        href: '/admin/activity',
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '活动名称', align: 'center', value: 'activity_name'},
      {text: '活动详情', align: 'center', value: 'activity_description'},
      {text: '活动缩略图', align: 'center', value: 'activity_thumbnail'},
      {text: '回复关键词', align: 'center', value: 'reply_keyword'},
      {text: '活动状态', align: 'center', value: 'state'},
      {text: '参与人数', align: 'center', value: 'customer_num'},
      {text: '备注', align: 'center', value: 'remarks'},
      {text: '添加时间', align: 'center', value: 'created_at'},
      {text: '当前卡券', align: 'center', value: 'cardModelList'},
      {text: '使用的商铺', align: 'center', value: 'shop'},
      {text: '操作', align: 'center', value: 'action', sortable: false},
    ],
    rules: {
      activity_name: [
        {required: true, message: '请输入活动名称', trigger: 'blur'},
        {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
        {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      activity_description: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      activity_thumbnail: [
        {type: 'url', message: '活动缩略图URL链接格式错误', trigger: 'change'},
      ],
      reply_keyword: [
        {required: true, message: '请输入活动回复关键词', trigger: 'blur'},
        {min: 1, max: 20, message: '长度在 1 到 20 个字符', trigger: 'change'},
        {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      remarks: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
    },
    editedIndex: -1,
    editedItem: {
      activity_name: '',
      activity_description: '',
      activity_thumbnail: '',
      reply_keyword: '',
      remarks: '',
      state: false,
      shop_id: null,
      card_model_id_list: [],
    },
    defaultItem: {
      activity_name: '',
      activity_description: '',
      activity_thumbnail: '',
      reply_keyword: '',
      remarks: '',
      state: false,
      shop_id: null,
      card_model_id_list: [],
    },
    search: '',
  }),
  computed: {
    ...mapState({
      activityList: state => state.activity.activityList ? state.activity.activityList : [],
      cardModelList: state => state.card.cardModelList ? state.card.cardModelList : [],
      shopList: state => state.shop.shopList ? state.shop.shopList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加活动' : '修改活动';
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
    this.addActivityList();
    this.addCardModelList();
    this.addShopList(Loading.service({fullscreen: true}).close());
  },
  methods: {
    ...mapActions({
      addActivityList: 'activity/addActivityList',
      addCardModelList: 'card/addCardModelList',
      addShopList: 'shop/addShopList',
    }),
    changePage(url) {
      this.$router.push(url);
    },
    editItem(item) {
      const _item = JSON.parse(JSON.stringify(item));
      _item.state = _item.state === 1;
      this.editedIndex = this.activityList.indexOf(item);
      this.editedItem = Object.assign({}, _item);
      this.dialog = true;
    },
    async deleteItem(item) {
      if (confirm(`确定要删除 ${item.activity_name} ?`)) {
        Loading.service({fullscreen: true});
        await this.$axios.$delete(`/activity/${item.id}`);
        this.addActivityList();
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
          _editedItem.activity_name = this.editedItem.activity_name;
          if (!!this.editedItem.activity_description || this.editedItem.activity_description !== '') {
            _editedItem.activity_description = this.editedItem.activity_description;
          }
          if (!!this.editedItem.activity_thumbnail || this.editedItem.activity_thumbnail !== '') {
            _editedItem.activity_thumbnail = this.editedItem.activity_thumbnail;
          }
          _editedItem.reply_keyword = this.editedItem.reply_keyword;
          _editedItem.remarks = this.editedItem.remarks;
          _editedItem.state = this.editedItem.state ? 1 : 0;
          _editedItem.card_model_id_list = this.editedItem.card_model_id_list.length
            ? this.editedItem.card_model_id_list
            : null;

          Loading.service({fullscreen: true});
          if (this.editedIndex > -1) {
            await this.$axios.$put(`/activity/${this.editedItem.id}`, qs.stringify(_editedItem));
          } else {
            await this.$axios.$post(`/activity`, qs.stringify(_editedItem));
          }
          this.close();
          this.addActivityList(Loading.service({fullscreen: true}).close());
        }
      });
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
