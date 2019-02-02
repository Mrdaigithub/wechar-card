const pkg = require('./package');

module.exports = {
  mode: 'universal',

  /*
  ** Headers of the page
  */
  head: {
    title: pkg.name,
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {hid: 'description', name: 'description', content: pkg.description},
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'},
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons',
      },
    ],
  },

  /*
  ** Customize the progress-bar color
  */
  loading: {color: '#000'},

  /*
  ** Global CSS
  */
  css: [
    '~/assets/style/app.styl',
    // 'swiper/dist/css/swiper.css',
    'element-ui/lib/theme-chalk/index.css',
  ],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '@/plugins/vuetify',
    '@/plugins/element',
    '@/plugins/axios',
    {src: '@/plugins/laravelEcho', ssr: false},
    // {src: '@/plugins/swiper', ssr: false},
  ],

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://github.com/nuxt-community/axios-module#usage
    '@nuxtjs/axios',
  ],
  /*
  ** Axios module configuration
  */
  axios: {
    // baseURL: 'https://wzyylm.com/api/v1',
    // baseURL: 'http://localhost/api/v1',
    baseURL: 'https://mrdaisite.club/api/v1',
    https: true,
    retry: {retries: 3},
    debug: true,
  },

  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/,
        });
      }
    },
  },
};
