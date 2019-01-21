export const state = () => ({
  userList: []
});

export const mutations = {
  add(state, user) {
    state.userList.push(user)
  },
  remove(state, id) {
    state.list = state.list.filter(e => e.id !== id);
  },
  update(state, id, user) {
    state.list = state.list.map(e => e.id === id ? user : e);
  }
};

export const actions = {

};
