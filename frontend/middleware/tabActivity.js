export default function(context) {
  context.store.commit('assist/setBottomTab', context.route.name.split('-')[1]);
}
