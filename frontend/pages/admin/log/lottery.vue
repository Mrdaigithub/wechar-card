<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminLogLottery
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminLogLottery",
    layout: 'admin',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/admin'
        },
        {
          text: '核销记录管理',
          disabled: true,
          href: '/admin/log/lottery'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
