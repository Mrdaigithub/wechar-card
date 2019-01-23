<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-card-text>
        <v-container grid-list-md>
          <el-form
            label-width="200px"
            label-position="right">
            <v-layout wrap>
              <v-flex
                xs12
                sm6>
                <el-form-item :label="configList.needWriteInfo.configName">
                  <el-switch v-model="configList.needWriteInfo.configValue"/>
                </el-form-item>
              </v-flex>
              <v-flex
                xs12
                sm6>
                <el-form-item :label="configList.signInDay.configName">
                  <el-input-number
                    v-model="configList.signInDay.configValue"
                    :min="0"
                    :max="15"
                    :label="configList.signInDay.configName"/>
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
  export default {
    name: "AdminSystemConfig",
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
          text: '系统设置',
          disabled: true,
          href: '/admin/system/config'
        },
      ],
      configList: {
        needWriteInfo: {
          id: 1,
          configName: "抽奖信息填写",
          configValue: true
        },
        signInDay: {
          id: 2,
          configName: "15天签到多少天获得抽奖数",
          configValue: 7
        }
      }
    }),
    computed: {},
    watch: {},
    methods: {
      save() {
      },
    },
  }
</script>

<style scoped lang="stylus">

</style>
