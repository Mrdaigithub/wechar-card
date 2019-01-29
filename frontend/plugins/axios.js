import {Message} from 'element-ui';

export default function({$axios, store}) {
  $axios.onRequest(config => {
    if (store.state.token.token) {
      config.headers.common['Authorization'] = `Bearer ${store.state.token.token}`;
    }
  });
  $axios.onResponseError(config => {
    console.log(
      config.response.data.message ? config.response.data.message : '未知错误');
    Message.error(
      config.response.data.message ? config.response.data.message : '未知错误');
    // if (store.state.token.token) {
    //   config.headers.common['Authorization'] = `Bearer ${store.state.token.token}`;
    // }
  });
}
