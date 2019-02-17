import {Message, Loading} from 'element-ui';
import {DOMAIN} from '../utils/constant';

export default function({$axios, store}) {
  $axios.onRequest(config => {
    if (sessionStorage.getItem('token')) {
      config.headers.common['Authorization'] =
        `Bearer ${sessionStorage.getItem('token')}`;
    }
    // token过期自动更新
    if (sessionStorage.ttl && new Date().getTime() > sessionStorage.ttl * 1000 -
      1000 * 30 && store.state.oneself.oneself && !window.tokenLock) {
      (async () => {
        window.tokenLock = true;
        const {data} = await $axios.$get(
          `/auth/client/${store.state.oneself.oneself.openid}`);
        sessionStorage.setItem('token', data.token);
        sessionStorage.setItem('ttl', data.ttl);
        window.tokenLock = false;
      })();
    }
  });
  $axios.onRequestError(error => {
    Message.error(
      error.response && error.response.data.message
        ? error.response.data.message
        : '客户端请求错误');
    Loading.service({fullscreen: true}).close();
  });
  $axios.onResponseError(error => {
    if (error.response && error.response.status === 401) {
      const type = store.$router.history.current.path.split('/').
        filter(e => e && e !== '')[0];
      if (type === 'admin') {
        sessionStorage.clear();
        store.$router.replace('/admin/login');
      } else if (type === 'user') {
        const shopId = store.$router.history.current.query.shopid;
        if (!shopId || shopId === '') {
          return Message.error('参数缺失,请退回公众号重新进入');
        }
        // Todo dev
        // sessionStorage.clear();
        // window.location.href = `${DOMAIN}/wechat/authorize?url=%2Fwechat%2Fgrant%2Flottery%2Fuser%3Fshopid%3D${shopId}`;
      } else if (type === 'shop') {
        // Todo dev
        // sessionStorage.clear();
        // window.location.href = `${DOMAIN}/wechat/authorize?url=https%3A%2F%2Fmrdaisite.club%2Fwechat%2Fgrant%2Fshop`;
      }
    } else if (error.response && error.response.status === 403) {
      const oneself = store.state.oneself.oneself;
      if (!oneself) {
        return Message.error('未认证');
      }
      if (oneself.identity === 0) {
        const shopId = store.$router.history.current.query.shopid;
        if (!shopId || shopId === '') {
          return Message.error('参数缺失,请退回公众号重新进入');
        }
        // Todo dev
        // sessionStorage.clear();
        // window.location.href = `${DOMAIN}/wechat/authorize?url=%2Fwechat%2Fgrant%2Flottery%2Fuser%3Fshopid%3D${shopId}`;
      } else if (oneself.identity === 1) {
        // Todo dev
        // sessionStorage.clear();
        // window.location.href = `${DOMAIN}/wechat/authorize?url=https%3A%2F%2Fmrdaisite.club%2Fwechat%2Fgrant%2Fshop`;
      } else if (oneself.identity === 3) {
        store.$router.replace('/admin/login');
      } else {
        return Message.error('未认证');
      }
    }
    Message.error(
      error.response && error.response.data.message
        ? error.response.data.message
        : '未知错误');
    Loading.service({fullscreen: true}).close();
  });
}
