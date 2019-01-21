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
      setTimeout(() => this.$router.push('/user/lottery'), 2000)
    }
  }
</script>
