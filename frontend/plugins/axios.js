import {Message, Loading} from 'element-ui';

export default function({$axios, store}) {
  $axios.onRequest(config => {
    if (store.state.oneself.token) {
      config.headers.common['Authorization'] = `Bearer ${store.state.oneself.token}`;
    }
  });
  $axios.onRequestError(error => {
    Message.error(
      error.response.data.message ? error.response.data.message : '客户端请求错误');
    Loading.service({fullscreen: true}).close();
  });
  $axios.onResponseError(error => {
    Message.error(
      error.response.data.message ? error.response.data.message : '未知错误');
    Loading.service({fullscreen: true}).close();
  });
}
