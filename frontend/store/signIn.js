export const state = () => ({
  signInLogList: null,
});

export const mutations = {
  add(state, signInLogList) {
    state.signInLogList = signInLogList;
  },
};

export const actions = {
  async add({commit}, userId) {
    const {data} = await this.$axios.$get(`/signin/user/${userId}`);
    commit('add', data);
  },
};
