export const state = () => ({
  systemConfig: null,
});

export const mutations = {
  addSystemConfig(state, systemConfig) {
    state.systemConfig = systemConfig;
  },
};

export const actions = {
  async addSystemConfig({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/system/config`);
    commit('addSystemConfig', data);
    cb();
  },
};
