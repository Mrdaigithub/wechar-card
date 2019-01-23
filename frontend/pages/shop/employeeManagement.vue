<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container
      fluid
      grid-list-lg>
      <v-layout
        row
        wrap>
        <v-flex
          v-for="(item, index) of employeeList"
          :key="index"
          xs6
          sm6
          md6
          lg4>
          <v-card color="white">
            <v-layout row>
              <v-flex xs5>
                <v-img
                  src="https://cdn.vuetifyjs.com/images/cards/halcyon.png"
                  height="125px"
                  contain
                />
              </v-flex>
              <v-flex xs7>
                <v-card-title primary-name>
                  <div>
                    <div class="headline">{{ item.name }}</div>
                    <v-tooltip top>
                      <span slot="activator">{{ item.job }}</span>
                      <span>人员岗位</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <div slot="activator">{{ item.createdDate }}</div>
                      <span>创建时间</span>
                    </v-tooltip>
                    <v-tooltip top>
                      <div slot="activator">{{ item.remarks }}</div>
                      <span>备注</span>
                    </v-tooltip>
                  </div>
                </v-card-title>
              </v-flex>
            </v-layout>
            <v-divider light/>
            <v-card-actions class="pa-3">
              <v-spacer/>
              <v-btn
                icon
                @click="editPermission(item)">
                <v-icon>edit</v-icon>
              </v-btn>
              <v-btn icon>
                <v-icon>delete</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <v-dialog
      v-model="qrCodeDialog"
      persistent
      max-width="300px">
      <v-card>
        <v-card-title>
          <span>员工扫描此二维码以添加</span>
          <v-spacer/>
        </v-card-title>
        <v-img
          src="http://upload.mnw.cn/2016/0126/1453768615784.jpg"
          contain/>
        <v-card-actions>
          <v-btn
            color="primary"
            flat
            @click="qrCodeDialog = false">关闭
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="permissionDialog"
      max-width="1200px">
      <v-card>
        <v-card-title>
          <span>编辑员工可操作的卡券</span>
          <v-spacer/>
        </v-card-title>
        <v-container
          fluid
          grid-list-lg>
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
                        <div class="headline">{{ item.name }}</div>
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
                <v-card-actions>
                  <v-switch v-model="switchState"/>
                </v-card-actions>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>
    </v-dialog>
    <v-btn
      fixed
      dark
      fab
      bottom
      left
      color="red"
      @click="qrCodeDialog = true">
      <v-icon>add</v-icon>
    </v-btn>
  </div>
</template>

<script>
  export default {
    name: "EmployeeManagement",
    layout: "shop",
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/shop'
        },
        {
          text: '店员管理',
          disabled: true,
          href: '/shop/employeemanagement'
        },
      ],
      employeeList: [
        {
          name: '张三',
          job: '店长',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三2',
          job: '店员',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三',
          job: '店长',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三2',
          job: '店员',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三',
          job: '店长',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三2',
          job: '店员',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三',
          job: '店长',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三2',
          job: '店员',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三',
          job: '店长',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
        {
          name: '张三2',
          job: '店员',
          createdDate: '2018-12-31',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
          remarks: '备注备注备注备注备注备注备注'
        },
      ],
      cardList: [
        {
          name: '1元优惠券',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          name: '1元优惠券',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
        },
        {
          name: '1元优惠券',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/house.jpg',
        },
        {
          name: '1元优惠券',
          src: 'https://cdn.vuetifyjs.com/images/employeeList/road.jpg',
        },
      ],
      qrCodeDialog: false,
      permissionDialog: false,
      switchState: true,
    }),
    methods: {
      changePage(url) {
        this.$router.push(url)
      },
      editPermission(item) {
        this.permissionDialog = true;
      }
    }
  }
</script>

<style scoped>

</style>
