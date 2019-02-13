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
                  label-position="right">
                  <v-layout wrap>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="商家名称"
                        prop="shop_name">
                        <el-input v-model="editedItem.shop_name"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item
                        label="商家地址"
                        prop="shop_location">
                        <el-select
                          v-model="editedItem.shop_location"
                          class="w100"
                          filterable>
                          <el-option
                            v-for="item in cityList"
                            :key="item"
                            :label="item"
                            :value="item"/>
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
                        label="合作日期"
                        prop="started_at">
                        <el-date-picker
                          v-model="editedItem.started_at"
                          class="w100"
                          type="datetime"
                          placeholder="选择日期时间"/>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      v-if="!!shopList.length"
                      xs12
                      sm6>
                      <el-form-item label="参加的活动">
                        <el-select
                          v-model="editedItem.activity_id"
                          class="w100"
                          clearable
                          filterable>
                          <el-option
                            v-for="item in activityList"
                            :key="item.id"
                            :label="item['activity_name']"
                            :value="item.id"
                            :disabled="(item.state !== 1)
                              || ((shopList[editedIndex] && !!shopList[editedIndex]['activity_id']) ?
                                ((!!item.shop_id) && (shopList[editedIndex]['activity_id'] !== item.id))
                            : !!item.shop_id)"/>
                        </el-select>
                      </el-form-item>
                    </v-flex>
                    <v-flex
                      xs12
                      sm6>
                      <el-form-item label="商家状态">
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
        :items="shopList"
        :search="search"
        :rows-per-page-items="[ 5, 10, 30]"
        rows-per-page-text="每页行数"
        class="elevation-1">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-center">{{ props.item.id }}</td>
          <td class="text-xs-center">{{ props.item.shop_name || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.shop_location || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.started_at || '暂无' }}</td>
          <td class="text-xs-center">{{ props.item.state ? '合作中' : '合作结束' }}</td>
          <td class="text-xs-center">{{ props.item.remarks || '暂无' }}</td>
          <td class="text-xs-center">
            <v-btn
              v-if="props.item['activity_id'] && activityList.length"
              small
              flat
              @click="changePage(`/admin/activity?activity=${activityList.filter(item=>item.id === props.item['activity_id'])[0]['activity_name']}`)">
              {{ activityList.filter(item=>item.id === props.item['activity_id'])[0]['activity_name'] }}
            </v-btn>
            <div v-else>未参加活动</div>
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
import {mapState, mapActions} from 'vuex';
import qs from 'qs';
import cityList from '@/utils/cityList';
import {Loading} from 'element-ui';

export default {
  name: 'AdminShop',
  layout: 'admin',
  data: () => ({
    cityList: cityList,
    valid: true,
    breadcrumbList: [
      {
        text: '主页',
        disabled: false,
        href: '/admin',
      },
      {
        text: '商家管理',
        disabled: true,
        href: '/admin/shop',
      },
    ],
    dialog: false,
    headers: [
      {text: 'ID', align: 'center', sortable: true, value: 'id'},
      {text: '商家名称', align: 'center', value: 'shop_name'},
      {text: '商家地址', align: 'center', value: 'shop_location'},
      {text: '开始合作时间', align: 'center', value: 'started_atTime'},
      {text: '商家状态', align: 'center', value: 'state'},
      {text: '备注', align: 'center', value: 'remarks'},
      {text: '参与活动', align: 'center', value: 'activity.activity_name'},
      {text: '操作', align: 'center', value: 'action', sortable: false},
    ],
    rules: {
      shop_name: [
        {required: true, message: '请输入商家名称', trigger: 'blur'},
        {min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'change'},
        {pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
      shop_location: [
        {required: true, message: '请选择商家所在地址', trigger: 'change'},
      ],
      remarks: [
        {type: 'string', pattern: /^(\w|[\u4e00-\u9fa5])+$/, message: '请不要包含特殊字符', trigger: 'change'},
      ],
    },
    editedIndex: -1,
    editedItem: {
      shop_name: '',
      shop_location: '',
      started_at: '',
      activity_id: null,
      state: false,
      remarks: '',
    },
    defaultItem: {
      shop_name: '',
      shop_location: '',
      started_at: '',
      startTime: '',
      activity_id: null,
      state: false,
      remarks: '',
    },
    search: '',
  }),
  computed: {
    ...mapState({
      shopList: state => state.shop.shopList ? state.shop.shopList : [],
      activityList: state => state.activity.activityList ? state.activity.activityList : [],
    }),
    formTitle() {
      return this.editedIndex === -1 ? '添加商家' : '修改商家';
    },
  },
  watch: {
    dialog(val) {
      val || this.close();
    },
  },
  mounted() {
    Loading.service({fullscreen: true});
    if (this.$route.query.shop) {
      this.search = this.$route.query.shop;
    }
    this.addShopList();
    this.addActivityList(Loading.service({fullscreen: true}).close());
  },
  methods: {
    ...mapActions({
      addShopList: 'shop/addShopList',
      addActivityList: 'activity/addActivityList',
    }),
    changePage(url) {
      this.$router.push(url);
    },
    editItem(item) {
      const _item = JSON.parse(JSON.stringify(item));
      _item.state = _item.state === 1;
      this.editedIndex = this.shopList.indexOf(item);
      this.editedItem = Object.assign({}, _item);
      this.dialog = true;
    },
    async deleteItem(item) {
      if (confirm(`确定要删除 ${item.shop_name} ?`)) {
        Loading.service({fullscreen: true});
        await this.$axios.$delete(`/shop/${item.id}`);
        this.addShopList();
        this.addActivityList(Loading.service({fullscreen: true}).close());
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
          _editedItem.shop_name = this.editedItem.shop_name;
          _editedItem.shop_location = this.editedItem.shop_location;
          _editedItem.remarks = this.editedItem.remarks;
          _editedItem.started_at = this.editedItem.started_at;
          _editedItem.state = this.editedItem.state ? 1 : 0;
          _editedItem.activity_id = this.editedItem.activity_id ? this.editedItem.activity_id : null;

          Loading.service({fullscreen: true});
          if (this.editedIndex > -1) {
            // 编辑
            await this.$axios.$put(`/shop/${this.editedItem.id}`, qs.stringify(_editedItem));
          } else {
            // 新建
            await this.$axios.$post(`/shop`, qs.stringify(_editedItem));
          }
          this.close();
          this.addShopList();
          this.addActivityList(Loading.service({fullscreen: true}).close());
        }
      });
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
