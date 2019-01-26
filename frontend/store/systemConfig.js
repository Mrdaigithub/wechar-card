export const state = () => ({
  systemConfig: null,
});

export const mutations = {
  add(state, systemConfig) {
    state.systemConfig = systemConfig;
  },
};

export const actions = {
  async add({commit}) {
    const {data} = await this.$axios.$get(`/system/config`);
    commit('add', data);
  },
};
