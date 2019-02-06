export const state = () => ({
  oneself: null,
  token: null,
  location: null,
  signInLogList: null,
  cardList: null,
});

export const mutations = {
  addOneself(state, user) {
    state.oneself = user;
  },
  addToken(state, token) {
    state.token = token;
  },
  removeToken(state) {
    state.token = null;
  },
  addLocation(state, location) {
    state.location = location;
  },
  addSignInLogList(state, signInLogList) {
    state.signInLogList = signInLogList;
  },
  addCardList(state, cardList) {
    state.cardList = cardList;
  },
};

export const actions = {
  async addOneself({commit}) {
    const {data} = await this.$axios.$get(`/user/0`);
    commit('addOneself', data);
  },
  async addToken({commit}, openid) {
    const {data} = await this.$axios.$get(`/auth/client/${openid}`);
    commit('addToken', data);
  },
  async removeToken({commit}) {
    await this.$axios.$delete(`/auth`);
    commit('removeToken');
  },
  async addSignInLogList({commit}, userId) {
    const {data} = await this.$axios.$get(`/signin/user/${userId}`);
    commit('addSignInLogList', data);
  },
  async addCardList({commit}, shopId) {
    const {data} = await this.$axios.$get(`/card/user/shop/${shopId}`);
    commit('addCardList', data);
  },
};
