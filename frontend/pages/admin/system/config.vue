<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-card-text>
        <v-container grid-list-md>
          <el-form
            v-if="!!systemConfigList"
            label-width="200px"
            label-position="right">
            <v-layout wrap>
              <v-flex
                v-if="systemConfigList.filter(item=>item['config_name']==='lotteryNeedsToFillInTheInformation').length > 0"
                xs12
                sm6>
                <el-form-item
                  :label="systemConfigList.filter(item=>item['config_name']==='lotteryNeedsToFillInTheInformation')[0]['config_description']">
                  <el-switch v-model="lotteryNeedsToFillInTheInformationValue"/>
                </el-form-item>
              </v-flex>
              <v-flex
                v-if="systemConfigList.filter(item=>item['config_name']==='howManyDaysHaveYouWonTheLotteryIn15Days').length > 0"
                xs12
                sm6>
                <el-form-item
                  :label="systemConfigList.filter(item=>item['config_name']==='howManyDaysHaveYouWonTheLotteryIn15Days')[0]['config_description']">
                  <el-input-number
                    v-model="howManyDaysHaveYouWonTheLotteryIn15DaysValue"
                    :min="0"
                    :max="15"
                    :label="systemConfigList.filter(item=>item['config_name']==='howManyDaysHaveYouWonTheLotteryIn15Days')[0]['config_description']"/>
                </el-form-item>
              </v-flex>
            </v-layout>
          </el-form>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer/>
        <v-btn
          :disabled="!valid"
          color="red"
          flat
          @click="save">保存
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex';
import qs from 'qs';
import {Loading} from 'element-ui';

export default {
  name: 'AdminSystemConfig',
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
        text: '系统设置',
        disabled: true,
        href: '/admin/system/config',
      },
    ],
    lotteryNeedsToFillInTheInformationValue: false,
    howManyDaysHaveYouWonTheLotteryIn15DaysValue: 0,
  }),
  computed: {
    ...mapState({
      systemConfigList: state => state.systemConfig.systemConfig ? state.systemConfig.systemConfig : null,
    }),
  },
  watch: {
    systemConfigList(systemConfigList) {
      if (!systemConfigList) {
        return;
      }
      this.lotteryNeedsToFillInTheInformationValue = /true/i.test(systemConfigList.filter(
        item => item['config_name'] === 'lotteryNeedsToFillInTheInformation')[0]['config_value']);
      this.howManyDaysHaveYouWonTheLotteryIn15DaysValue = parseInt(systemConfigList.filter(
        item => item['config_name'] === 'howManyDaysHaveYouWonTheLotteryIn15Days')[0]['config_value']);
    },
  },
  mounted() {
    Loading.service({fullscreen: true});
    this.addSystemConfig(Loading.service({fullscreen: true}).close());
  },
  methods: {
    ...mapActions({
      addSystemConfig: 'systemConfig/addSystemConfig',
    }),
    async save() {
      Loading.service({fullscreen: true});
      for (let item of this.systemConfigList) {
        let _item = JSON.parse(JSON.stringify(item));
        if (_item['config_name'] === 'lotteryNeedsToFillInTheInformation') {
          _item['config_value'] = this.lotteryNeedsToFillInTheInformationValue.toString();
          const {data} = await this.$axios.$put(`/system/config/${item['id']}`, qs.stringify(_item));
        } else if (_item['config_name'] === 'howManyDaysHaveYouWonTheLotteryIn15Days') {
          _item['config_value'] = this.howManyDaysHaveYouWonTheLotteryIn15DaysValue.toString();
          const {data} = await this.$axios.$put(`/system/config/${item['id']}`, qs.stringify(_item));
        }
      }
      this.addSystemConfig(Loading.service({fullscreen: true}).close());
    },
  },
};
</script>

<style scoped lang="stylus">

</style>
