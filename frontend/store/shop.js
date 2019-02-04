export const state = () => ({
  shopList: null,
  activity: null,
});

export const mutations = {
  addShopList(state, shopList) {
    state.shopList = shopList;
  },
  addShopActivity(state, activity) {
    state.activity = activity;
  },
};

export const actions = {
  async addShopList({commit}) {
    const {data} = await this.$axios.$get(`/shop`);
    commit('addShopList', data);
  },
  /**
   * 添加指定商铺关联的活动
   *
   * @param commit
   * @param shopId
   * @returns {Promise<void>}
   */
  async addShopActivity({commit}, shopId) {
    const {data} = await this.$axios.$get(`/activity/shop/${shopId}`);
    commit('addShopActivity', data);
  },
};
