import {Message} from 'element-ui';

export default function({$axios, store}) {
  $axios.onRequest(config => {
    if (store.state.token.token) {
      config.headers.common['Authorization'] = `Bearer ${store.state.token.token}`;
    }
  });
  $axios.onRequestError(error => {
    Message.error(
      error.response.data.message ? error.response.data.message : '客户端请求错误');
  });
  $axios.onResponseError(error => {
    Message.error(
      error.response.data.message ? error.response.data.message : '未知错误');
  });
}
