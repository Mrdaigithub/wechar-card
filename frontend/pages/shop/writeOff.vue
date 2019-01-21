<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-card>
      <v-card-title>
        核销管理表
        <v-spacer/>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="搜索"
          single-line
          hide-details/>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search">
        <template
          slot="items"
          slot-scope="props">
          <td class="text-xs-left">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.name }}</td>
          <td class="text-xs-left">{{ props.item.operator }}</td>
          <td class="text-xs-left">{{ props.item.customer }}</td>
          <td class="text-xs-left">{{ props.item.operatedTime }}</td>
          <td class="text-xs-left">{{ props.item.remarks }}</td>
        </template>
        <template slot="no-data">
          <v-alert
            :value="true"
            color="error"
            icon="warning">
            现在暂无数据 :(
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
    <v-btn
      fixed
      dark
      fab
      bottom
      left
      color="red">
      <v-icon>cloud_download</v-icon>
    </v-btn>
  </div>
</template>

<script>
  export default {
    name: "WriteOff",
    layout: "shop",
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/shop'
        },
        {
          text: '核销管理',
          disabled: true,
          href: '/shop/writeoff'
        },
      ],
      headers: [
        {text: 'ID', align: 'left', value: 'id'},
        {text: '卡券名称', align: 'left', sortable: true, value: 'name'},
        {text: '核销人员', align: 'left', value: 'operator'},
        {text: '客户', align: 'left', value: 'customer'},
        {text: '核销时间', align: 'left', value: 'operatedTime'},
        {text: '备注', align: 'left', value: 'remarks'},
      ],
      desserts: [
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 159,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 1,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 2,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 3,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 4,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 5,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 6,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 7,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 8,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 9,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
        {
          remarks: false,
          name: 'Frozen Yogurt',
          id: 10,
          customer: 'customer100',
          operatedTime: '2018-12-31',
          operator: 'root',
        },
      ],
      search: '',
    }),
    methods: {}
  }
</script>
