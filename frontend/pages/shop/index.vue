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
    name: "Shop",
    layout: 'shop',
    data: () => ({
      breadcrumbList: [
        {
          text: '主页',
          disabled: true,
          href: '/shop'
        },
      ],
    })
  }
</script>

<style scoped>

</style>
