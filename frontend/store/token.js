export const state = () => ({
  token: null,
});

export const mutations = {
  add(state, token) {
    state.token = token;
  },
  remove(state) {
    state.token = null;
  },
};

export const actions = {
  async add({commit}, openid) {
    const {data} = await this.$axios.$get(`/auth/client/${openid}`);
    commit('add', data);
  },
  async remove({commit}) {
    await this.$axios.$delete(`/auth`);
    commit('remove');
  },
};
