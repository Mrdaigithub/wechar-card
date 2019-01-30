export const state = () => ({
  activity: null,
});

export const mutations = {
  addActivity(state, activity) {
    state.activity = activity;
  },
};

export const actions = {
  async addActivity({commit}, shopId) {
    const {data} = await this.$axios.$get(`/activity/shop/${shopId}`);
    commit('addActivity', data);
  },
};
