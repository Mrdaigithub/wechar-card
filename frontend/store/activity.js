export const state = () => ({
  activityList: null,
});

export const mutations = {
  addActivityList(state, activityList) {
    state.activityList = activityList;
  },
};

export const actions = {
  async addActivityList({commit}, cb = () => null) {
    const {data} = await this.$axios.$get(`/activity`);
    commit('addActivityList', data);
    cb();
  },
};
