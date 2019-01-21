<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminActivity
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminActivity",
    layout: 'admin',
    data: () => ({
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
    })
  }
</script>

<style scoped>

</style>
