<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminUserSignIn
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminUserSignIn",
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
          disabled: false,
          href: '/admin/user'
        },
        {
          text: '签到管理',
          disabled: true,
          href: null
        },
      ],
    })
  }
</script>

<style scoped>

</style>
