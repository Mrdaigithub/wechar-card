<template>
  <v-app>
    <v-content>
      <nuxt/>
    </v-content>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      title: 'Loading...'
    }
  },
  mounted() {
    this.$router.push('/user/lottery')
  }
}
</script>
