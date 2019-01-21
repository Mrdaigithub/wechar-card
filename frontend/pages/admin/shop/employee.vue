<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      AdminShopEmployee
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "AdminShopEmployee",
    layout: 'admin',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: false,
          href: '/admin'
        },
        {
          text: '商家人员管理',
          disabled: true,
          href: '/admin/employee'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
