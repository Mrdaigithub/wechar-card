import qs from 'qs';

export const state = () => ({
  oneself: null,
  location: null,
  signInLogList: null,
  cardList: null,
});

export const mutations = {
  addOneself(state, user) {
    state.oneself = user;
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
  async updatePlainUserByOneself({commit}, {arg, cb = () => null}) {
    const {data} = await this.$axios.$put(`/user/plain_user/0`,
      qs.stringify(arg));
    commit('addOneself', data);
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
