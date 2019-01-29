export const state = () => ({
  oneself: null,
});

export const mutations = {
  add(state, user) {
    state.oneself = user;
  },
};

export const actions = {
  async add({commit}) {
    const {data} = await this.$axios.$get(`/user/0`);
    commit('add', data);
  },
};
