export const state = () => ({
  bottomTab: 'lottery',
});

export const mutations = {
  setBottomTab(state, bottomTab) {
    state.bottomTab = bottomTab;
  },
};
