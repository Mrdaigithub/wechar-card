<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminLogWinning
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminLogWinning",
    layout: 'admin',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/admin'
        },
        {
          text: '中奖记录管理',
          disabled: true,
          href: '/admin/log/winning'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
