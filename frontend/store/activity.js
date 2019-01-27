export const state = () => ({
  activity: null,
});

export const mutations = {
  add(state, activity) {
    state.activity = activity;
  },
};

export const actions = {
  async add({commit}, shopId) {
    const {data} = await this.$axios.$get(`/activity/shop/${shopId}`);
    commit('add', data);
  },
};
