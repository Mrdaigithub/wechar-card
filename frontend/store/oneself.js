export const state = () => ({
  oneself: null,
});

export const mutations = {
  add(state, user) {
    state.oneself = user;
  },
};

export const actions = {
  async add({commit}, openid) {
    const {data} = await this.$axios.$get(`/user/openid/${openid}`);
    commit('add', data);
  },
};
