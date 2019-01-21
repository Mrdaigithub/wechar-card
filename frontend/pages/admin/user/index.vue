<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminUser
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminUser",
    layout: 'admin',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/admin'
        },
        {
          text: '用户管理',
          disabled: true,
          href: '/admin/user'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
