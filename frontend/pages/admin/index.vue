<template>
  <div>
    <v-breadcrumbs
      :items="breadcrumbList"
      divider=">"/>
    <v-container>
      你好呀
    </v-container>
  </div>
</template>

<script>
  export default {
    name: "Admin",
    layout: 'admin',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: true,
          href: '/admin'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
