export const state = () => ({
  oneself: {},
});

export const mutations = {
  init(state, user) {
    state.oneself = user;
  },
};

export const actions = {};
