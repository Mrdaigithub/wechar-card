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
  async addOneself({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/user/0`);
    commit('addOneself', data);
    cb();
  },
  async addToken({commit}, openid, cb = () => null) {
    const {data} = await this.$axios.$get(`/auth/client/${openid}`);
    commit('addToken', data);
    cb();
  },
  async removeToken({commit}, cb = () => null) {
    await this.$axios.$delete(`/auth`);
    commit('removeToken');
    cb();
  },
  async addSignInLogList({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/signin/user/${arg}`);
    commit('addSignInLogList', data);
    cb();
  },
  async addCardList({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$get(`/card/user/shop/${arg}`);
    commit('addCardList', data);
    cb();
  },
};
